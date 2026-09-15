<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratAdministratifTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected KlasifikasiSurat $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode'   => '005',
            'nama'   => 'Undangan',
            'status' => 'aktif',
        ]);
        
        // Seed Master Data
        $this->artisan('db:seed', ['--class' => 'JenisSuratSeeder']);
    }

    public function test_jenis_surat_berita_acara_tersedia_di_database()
    {
        $ba = JenisSurat::where('kode', 'BA')->first();
        $this->assertNotNull($ba);
        $this->assertEquals('Berita Acara', $ba->nama);
    }

    public function test_pembuatan_draft_berita_acara_tanpa_nomor()
    {
        $ba = JenisSurat::where('kode', 'BA')->first();
        
        $response = $this->actingAs($this->admin)->post(route('surat-keluar.store'), [
            'jenis_surat_id' => $ba->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'perihal'        => 'Berita Acara Rapat',
            'tujuan'         => 'Pihak Terkait',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'draft'
        ]);

        $response->assertRedirect();
        
        $surat = Surat::where('perihal', 'Berita Acara Rapat')->first();
        $this->assertNotNull($surat);
        $this->assertEquals('draft', $surat->status);
        $this->assertNull($surat->nomor_surat); // Belum final, belum ada nomor
    }

    public function test_finalisasi_berita_acara_mendapatkan_nomor_dan_masuk_arsip()
    {
        $ba = JenisSurat::where('kode', 'BA')->first();
        
        // Buat Draft BA
        $surat = Surat::create([
            'jenis_surat_id' => $ba->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'arah'           => 'keluar',
            'perihal'        => 'Berita Acara Serah Terima',
            'tujuan'         => 'Pihak Ketiga',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'draft',
            'created_by'     => $this->admin->id
        ]);

        // Finalisasi BA via Update Route
        $response = $this->actingAs($this->admin)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $ba->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'perihal'        => 'Berita Acara Serah Terima Final',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'final',
        ]);

        $response->assertRedirect();
        $surat->refresh();

        // 1. Status jadi final
        $this->assertEquals('final', $surat->status);
        
        // 2. Mendapatkan Nomor resmi (dari DocumentNumberService)
        $this->assertNotNull($surat->nomor_surat);
        $this->assertStringContainsString('005', $surat->nomor_surat); // Mengandung kode klasifikasi
        
        // 3. Masuk Arsip
        $responseArsip = $this->get(route('arsip.index'));
        $responseArsip->assertSee('Berita Acara Serah Terima Final');
        $responseArsip->assertSee($surat->nomor_surat);
    }

    public function test_berita_acara_dapat_ditemukan_via_pencarian_berdasarkan_jenis()
    {
        $ba = JenisSurat::where('kode', 'BA')->first();
        $nd = JenisSurat::where('kode', 'ND')->first();
        
        Surat::create([
            'jenis_surat_id' => $ba->id,
            'arah'           => 'keluar',
            'perihal'        => 'Berita Acara Pemeriksaan',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'final',
            'created_by'     => $this->admin->id
        ]);

        Surat::create([
            'jenis_surat_id' => $nd->id,
            'arah'           => 'keluar',
            'perihal'        => 'Nota Dinas Pengadaan',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'final',
            'created_by'     => $this->admin->id
        ]);

        // Cari berdasarkan filter jenis_surat = BA
        $response = $this->actingAs($this->admin)->get(route('pencarian.index', [
            'jenis_surat_id' => $ba->id
        ]));

        $response->assertSee('Berita Acara Pemeriksaan');
        $response->assertDontSee('Nota Dinas Pengadaan'); // Tidak muncul
    }

    public function test_berita_acara_terhitung_di_modul_laporan()
    {
        $ba = JenisSurat::where('kode', 'BA')->first();
        
        Surat::create([
            'jenis_surat_id' => $ba->id,
            'arah'           => 'keluar',
            'perihal'        => 'Berita Acara Testing',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'final',
            'created_by'     => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('laporan.index'));
        
        // Pastikan tabel perJenis di Laporan menghitung Berita Acara Keluar = 1
        $perJenis = $response->viewData('perJenis');
        
        $rowBA = $perJenis->firstWhere('jenisSurat.kode', 'BA');
        
        $this->assertNotNull($rowBA);
        $this->assertEquals(0, $rowBA['masuk']);
        $this->assertEquals(1, $rowBA['keluar']);
        $this->assertEquals(1, $rowBA['total']);
    }
}
