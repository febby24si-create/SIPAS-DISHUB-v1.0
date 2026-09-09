<?php

namespace Tests\Feature;

use App\Models\GajiBerkala;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GajiBerkalaTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $pegawai;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'id' => 1]);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);

        $this->pegawai = Pegawai::create([
            'nama' => 'PNS KGB Test',
            'nip' => '198501012010121001',
            'pangkat' => 'Penata',
            'golongan' => 'III/c',
            'jabatan' => 'Staff',
            'status_aktif' => true,
        ]);
    }

    public function test_admin_can_create_gaji_berkala_with_tmt_calculation_and_attachment()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('dokumen_kgb.pdf', 100);

        $response = $this->actingAs($this->admin)->post(route('kepegawaian.kgb.store'), [
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 3000000,
            'gaji_pokok_baru' => 3200000,
            'tmt_sebelumnya' => '2024-04-01',
            'catatan' => 'KGB Rutin',
            'file_pendukung' => $file,
        ]);

        $kgb = GajiBerkala::first();
        
        $this->assertNotNull($kgb);
        $this->assertEquals('draft', $kgb->status);
        $this->assertEquals(3000000, $kgb->gaji_pokok_lama);
        $this->assertEquals(3200000, $kgb->gaji_pokok_baru);
        $this->assertEquals('2024-04-01', $kgb->tmt_sebelumnya->format('Y-m-d'));
        // 7. TMT berikutnya = TMT sebelumnya + 24 bulan
        $this->assertEquals('2026-04-01', $kgb->tmt_berikutnya->format('Y-m-d'));

        // 11. Attachment tersimpan
        $this->assertCount(1, $kgb->attachments);
        
        // 10. ActivityLog tercatat
        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => GajiBerkala::class,
            'subject_id' => $kgb->id,
            'aktivitas' => 'Membuat pengajuan gaji berkala: ' . $kgb->nomor_usulan,
        ]);

        $response->assertRedirect(route('kepegawaian.kgb.show', $kgb));
    }

    public function test_strict_workflow_transitions()
    {
        $kgb = GajiBerkala::create([
            'nomor_usulan' => 'KGB-TEST1',
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 3000000,
            'gaji_pokok_baru' => 3200000,
            'tmt_sebelumnya' => '2024-04-01',
            'tmt_berikutnya' => '2026-04-01',
            'status' => 'draft',
        ]);

        // 6. Transisi ilegal ditolak (draft -> selesai)
        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), [
            'status' => 'selesai',
        ])->assertSessionHas('error', 'Transisi status tidak valid.');
        $this->assertEquals('draft', $kgb->fresh()->status);

        // 2. draft -> verifikasi berhasil
        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), [
            'status' => 'verifikasi',
        ])->assertSessionHas('status', 'Status KGB berhasil diperbarui.');
        $this->assertEquals('verifikasi', $kgb->fresh()->status);

        // 3. verifikasi -> disetujui berhasil
        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), [
            'status' => 'disetujui',
        ])->assertSessionHas('status', 'Status KGB berhasil diperbarui.');
        $this->assertEquals('disetujui', $kgb->fresh()->status);

        // 4. disetujui -> selesai berhasil
        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), [
            'status' => 'selesai',
        ])->assertSessionHas('status', 'Status KGB berhasil diperbarui.');
        $this->assertEquals('selesai', $kgb->fresh()->status);

        // Check contextual activity log
        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => GajiBerkala::class,
            'subject_id' => $kgb->id,
            'aktivitas' => 'Status KGB KGB-TEST1: DISETUJUI → SELESAI',
        ]);
    }

    public function test_verifikasi_to_ditolak_transition()
    {
        $kgb = GajiBerkala::create([
            'nomor_usulan' => 'KGB-TEST2',
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 3000000,
            'gaji_pokok_baru' => 3200000,
            'tmt_sebelumnya' => '2024-04-01',
            'tmt_berikutnya' => '2026-04-01',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), ['status' => 'verifikasi']);
        
        // 5. verifikasi -> ditolak berhasil
        $this->actingAs($this->admin)->put(route('kepegawaian.kgb.status', $kgb), [
            'status' => 'ditolak',
        ])->assertSessionHasNoErrors();
        
        $this->assertEquals('ditolak', $kgb->fresh()->status);
    }

    public function test_kgb_histori_uses_only_selesai_status()
    {
        // 9. KGB draft/ditolak tidak digunakan sebagai histori
        GajiBerkala::create([
            'nomor_usulan' => 'KGB-DRAFT',
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 2000000,
            'gaji_pokok_baru' => 2200000,
            'tmt_sebelumnya' => '2020-04-01',
            'tmt_berikutnya' => '2022-04-01',
            'status' => 'draft',
        ]);

        GajiBerkala::create([
            'nomor_usulan' => 'KGB-DITOLAK',
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 2200000,
            'gaji_pokok_baru' => 2400000,
            'tmt_sebelumnya' => '2022-04-01',
            'tmt_berikutnya' => '2024-04-01',
            'status' => 'ditolak',
        ]);

        // 8. KGB terakhir berstatus selesai menjadi sumber data histori
        $kgbSelesai = GajiBerkala::create([
            'nomor_usulan' => 'KGB-SELESAI',
            'pegawai_id' => $this->pegawai->id,
            'gaji_pokok_lama' => 2400000,
            'gaji_pokok_baru' => 2600000,
            'tmt_sebelumnya' => '2024-04-01',
            'tmt_berikutnya' => '2026-04-01',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->admin)->get(route('kepegawaian.kgb.create'));
        $response->assertStatus(200);

        // View holds $riwayatKgb which maps Pegawai ID to their last completed KGB
        $riwayatData = $response->viewData('riwayatKgb');
        
        $this->assertArrayHasKey($this->pegawai->id, $riwayatData->toArray());
        $this->assertEquals($kgbSelesai->id, $riwayatData[$this->pegawai->id]->id);
    }
}
