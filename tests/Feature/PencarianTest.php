<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PencarianTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_pencarian_surat_final_berhasil_dan_draft_tidak_muncul()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);

        // Surat Final
        $suratFinal = Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'nomor_surat' => '123/UM/2026',
            'perihal' => 'Surat Final Penting',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        // Surat Draft
        $suratDraft = Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Surat Draft Penting',
            'tanggal_surat' => now(),
            'status' => 'draft',
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('pencarian.index', ['q' => 'Penting']));

        $response->assertStatus(200);
        $response->assertSee('123/UM/2026');
        $response->assertSee('Surat Final Penting');
        $response->assertDontSee('Surat Draft Penting');
    }

    public function test_pencarian_berdasarkan_nomor_dan_perihal_berhasil()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);

        Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'masuk',
            'nomor_surat' => 'NOMOR/ABC',
            'perihal' => 'Perihal Rahasia',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        $response1 = $this->actingAs($this->admin)->get(route('pencarian.index', ['q' => 'NOMOR/ABC']));
        $response1->assertSee('NOMOR/ABC');
        
        $response2 = $this->actingAs($this->admin)->get(route('pencarian.index', ['q' => 'Rahasia']));
        $response2->assertSee('Perihal Rahasia');
    }

    public function test_filter_arah_berhasil()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);

        Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'masuk',
            'perihal' => 'Dokumen Masuk',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Dokumen Keluar',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('pencarian.index', ['arah' => 'masuk']));
        $response->assertSee('Dokumen Masuk');
        $response->assertDontSee('Dokumen Keluar');
    }

    public function test_filter_jenis_surat_berhasil()
    {
        $jenisSurat1 = JenisSurat::create(['kode' => 'A', 'nama' => 'Tipe A']);
        $jenisSurat2 = JenisSurat::create(['kode' => 'B', 'nama' => 'Tipe B']);

        Surat::create([
            'jenis_surat_id' => $jenisSurat1->id,
            'arah' => 'masuk',
            'perihal' => 'Surat Tipe A',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        Surat::create([
            'jenis_surat_id' => $jenisSurat2->id,
            'arah' => 'keluar',
            'perihal' => 'Surat Tipe B',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('pencarian.index', ['jenis_surat_id' => $jenisSurat1->id]));
        $response->assertSee('Surat Tipe A');
        $response->assertDontSee('Surat Tipe B');
    }

    public function test_filter_gabungan_berhasil()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'A', 'nama' => 'Tipe A']);
        $klasifikasi = KlasifikasiSurat::create(['kode' => '100', 'nama' => 'Klasifikasi']);

        Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'klasifikasi_id' => $klasifikasi->id,
            'arah' => 'masuk',
            'perihal' => 'Target Ditemukan',
            'pengirim' => 'Instansi X',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'masuk',
            'perihal' => 'Tidak Sesuai',
            'tanggal_surat' => now(),
            'status' => 'final',
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('pencarian.index', [
            'arah' => 'masuk',
            'jenis_surat_id' => $jenisSurat->id,
            'klasifikasi_id' => $klasifikasi->id,
            'pengirim' => 'Instansi X',
        ]));

        $response->assertSee('Target Ditemukan');
        $response->assertDontSee('Tidak Sesuai');
    }
}
