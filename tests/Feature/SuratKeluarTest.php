<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratKeluarTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $jenisSurat;
    protected $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        
        $this->jenisSurat = JenisSurat::create([
            'kode' => 'SK',
            'nama' => 'Surat Keputusan'
        ]);

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode' => '850',
            'nama' => 'Kepegawaian',
            'status' => 'aktif'
        ]);
    }

    public function test_draft_dapat_diedit_dan_difinalisasi()
    {
        // 1. Buat Draft
        $surat = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'arah' => 'keluar',
            'perihal' => 'Draft Awal',
            'tanggal_surat' => now(),
            'status' => 'draft',
            'created_by' => $this->admin->id
        ]);

        // 2. Edit Draft
        $response = $this->actingAs($this->admin)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'perihal' => 'Draft Diedit',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Draft Diedit', $surat->fresh()->perihal);
        $this->assertNull($surat->fresh()->nomor_surat);

        // 3. Finalisasi Draft
        $responseFinal = $this->actingAs($this->admin)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'perihal' => 'Draft Final',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'final',
        ]);

        $responseFinal->assertRedirect();
        $suratFinal = $surat->fresh();
        
        $this->assertEquals('final', $suratFinal->status);
        $this->assertNotNull($suratFinal->nomor_surat); // Nomor resmi ter-generate

        // Activity log tercatat ringkas
        $this->assertDatabaseHas('activity_logs', [
            'surat_id' => $surat->id,
            'aktivitas' => 'Memfinalisasi surat keluar: ' . $suratFinal->nomor_surat
        ]);
    }

    public function test_draft_dapat_diakses_via_edit_route()
    {
        $surat = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Draft via Edit Route',
            'tanggal_surat' => now(),
            'status' => 'draft',
            'created_by' => $this->admin->id
        ]);

        // Halaman edit harus terbuka untuk draft
        $this->actingAs($this->admin)
            ->get(route('surat-keluar.edit', $surat))
            ->assertStatus(200)
            ->assertSee('Draft via Edit Route');
    }

    public function test_draft_tetap_draft_setelah_simpan_tanpa_finalisasi()
    {
        $surat = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Masih Draft',
            'tanggal_surat' => now(),
            'status' => 'draft',
            'created_by' => $this->admin->id
        ]);

        $this->actingAs($this->admin)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $this->jenisSurat->id,
            'perihal' => 'Perihal Diubah',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'draft',
        ])->assertRedirect();

        $fresh = $surat->fresh();
        $this->assertEquals('draft', $fresh->status);
        $this->assertEquals('Perihal Diubah', $fresh->perihal);
        $this->assertNull($fresh->nomor_surat); // Nomor belum diterbitkan
    }

    public function test_surat_final_tidak_dapat_diedit()
    {
        $surat = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'arah' => 'keluar',
            'nomor_surat' => '001/850/2026',
            'perihal' => 'Surat Penting',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        // Hit route edit
        $responseEdit = $this->actingAs($this->admin)->get(route('surat-keluar.edit', $surat));
        $responseEdit->assertStatus(403);

        // Hit route update
        $responseUpdate = $this->actingAs($this->admin)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $this->jenisSurat->id,
            'perihal' => 'Berubah',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'draft'
        ]);
        $responseUpdate->assertStatus(403);

        // Pastikan tidak berubah di DB
        $this->assertEquals('final', $surat->fresh()->status);
        $this->assertEquals('Surat Penting', $surat->fresh()->perihal);
        $this->assertEquals('001/850/2026', $surat->fresh()->nomor_surat);
    }

    public function test_surat_final_tidak_dapat_dihapus()
    {
        $surat = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'arah' => 'keluar',
            'nomor_surat' => '002/850/2026',
            'perihal' => 'Surat Penting 2',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->delete(route('surat-keluar.destroy', $surat));
        $response->assertStatus(403);

        $this->assertDatabaseHas('surat', [
            'id' => $surat->id
        ]);
    }
}
