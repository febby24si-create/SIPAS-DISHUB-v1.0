<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected JenisSurat $jenisSurat;
    protected JenisSurat $jenisSurat2;
    protected KlasifikasiSurat $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();

        $this->jenisSurat = JenisSurat::create([
            'kode' => 'UM',
            'nama' => 'Surat Umum',
        ]);

        $this->jenisSurat2 = JenisSurat::create([
            'kode' => 'SK',
            'nama' => 'Surat Keputusan',
        ]);

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode'   => '850',
            'nama'   => 'Kepegawaian',
            'status' => 'aktif',
        ]);
    }

    // ─── HELPER ──────────────────────────────────────────────────────────────

    private function buatSurat(array $overrides = []): Surat
    {
        return Surat::create(array_merge([
            'jenis_surat_id' => $this->jenisSurat->id,
            'arah'           => 'masuk',
            'perihal'        => 'Test Surat',
            'tanggal_surat'  => now()->format('Y-m-d'),
            'status'         => 'final',
            'created_by'     => $this->admin->id,
        ], $overrides));
    }

    // ─── TEST 1 ───────────────────────────────────────────────────────────────

    public function test_halaman_laporan_dapat_diakses()
    {
        $response = $this->actingAs($this->admin)->get(route('laporan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('laporan.index');
        $response->assertViewHasAll([
            'totalMasuk',
            'totalKeluar',
            'totalSemua',
            'totalDraft',
            'perJenis',
        ]);
    }

    // ─── TEST 2 ───────────────────────────────────────────────────────────────

    public function test_halaman_laporan_memerlukan_autentikasi()
    {
        $response = $this->get(route('laporan.index'));

        // Guest harus diarahkan ke halaman login
        $response->assertRedirect(route('login'));
    }

    // ─── TEST 3 ───────────────────────────────────────────────────────────────

    public function test_draft_tidak_masuk_statistik_final()
    {
        // Hanya ada surat draft, tidak ada yang final
        $this->buatSurat(['status' => 'draft', 'arah' => 'masuk']);
        $this->buatSurat(['status' => 'draft', 'arah' => 'keluar']);

        $response = $this->actingAs($this->admin)->get(route('laporan.index'));

        $response->assertViewHas('totalMasuk', 0);
        $response->assertViewHas('totalKeluar', 0);
        $response->assertViewHas('totalSemua', 0);
        // totalDraft harus 2 (semua draft)
        $response->assertViewHas('totalDraft', 2);
    }

    // ─── TEST 4 ───────────────────────────────────────────────────────────────

    public function test_statistik_masuk_hanya_hitung_surat_final()
    {
        $this->buatSurat(['status' => 'final', 'arah' => 'masuk']);  // dihitung
        $this->buatSurat(['status' => 'draft', 'arah' => 'masuk']);  // tidak dihitung
        $this->buatSurat(['status' => 'final', 'arah' => 'keluar']); // bukan masuk

        $response = $this->actingAs($this->admin)->get(route('laporan.index'));

        $response->assertViewHas('totalMasuk', 1);
    }

    // ─── TEST 5 ───────────────────────────────────────────────────────────────

    public function test_statistik_keluar_hanya_hitung_surat_final()
    {
        $this->buatSurat(['status' => 'final', 'arah' => 'keluar']); // dihitung
        $this->buatSurat(['status' => 'draft', 'arah' => 'keluar']); // tidak dihitung
        $this->buatSurat(['status' => 'final', 'arah' => 'masuk']);  // bukan keluar

        $response = $this->actingAs($this->admin)->get(route('laporan.index'));

        $response->assertViewHas('totalKeluar', 1);
    }

    // ─── TEST 6 ───────────────────────────────────────────────────────────────

    public function test_total_draft_konsisten_dengan_dashboard_all_arah()
    {
        // Draft dari semua arah harus terhitung — sama dengan Dashboard
        $this->buatSurat(['status' => 'draft', 'arah' => 'masuk']);
        $this->buatSurat(['status' => 'draft', 'arah' => 'keluar']);
        $this->buatSurat(['status' => 'final', 'arah' => 'masuk']); // bukan draft

        $responseLaporan = $this->actingAs($this->admin)->get(route('laporan.index'));
        $responseDashboard = $this->actingAs($this->admin)->get(route('dashboard'));

        // Keduanya harus menampilkan totalDraft yang sama
        $totalDraftLaporan   = $responseLaporan->viewData('totalDraft');
        $totalDraftDashboard = $responseDashboard->viewData('totalDraft');

        $this->assertEquals(2, $totalDraftLaporan);
        $this->assertEquals($totalDraftDashboard, $totalDraftLaporan);
    }

    // ─── TEST 7 ───────────────────────────────────────────────────────────────

    public function test_filter_periode_tanggal_bekerja()
    {
        // Surat dalam range → dihitung
        $this->buatSurat(['tanggal_surat' => '2026-07-15', 'arah' => 'masuk', 'status' => 'final']);
        // Surat di luar range → tidak dihitung
        $this->buatSurat(['tanggal_surat' => '2026-05-01', 'arah' => 'masuk', 'status' => 'final']);

        $response = $this->actingAs($this->admin)->get(route('laporan.index', [
            'dari'   => '2026-07-01',
            'sampai' => '2026-07-31',
        ]));

        $response->assertViewHas('totalMasuk', 1);
        $response->assertViewHas('totalKeluar', 0);
    }

    // ─── TEST 8 ───────────────────────────────────────────────────────────────

    public function test_filter_arah_masuk_bekerja()
    {
        $this->buatSurat(['arah' => 'masuk',  'status' => 'final']);
        $this->buatSurat(['arah' => 'keluar', 'status' => 'final']);

        $response = $this->actingAs($this->admin)->get(route('laporan.index', [
            'arah' => 'masuk',
        ]));

        // Dengan filter arah=masuk, totalKeluar harus 0
        // totalMasuk tetap 1
        $response->assertViewHas('totalMasuk', 1);
        $response->assertViewHas('totalKeluar', 0);
    }

    // ─── TEST 9 ───────────────────────────────────────────────────────────────

    public function test_filter_jenis_surat_bekerja()
    {
        // Jenis 1 — UM
        $this->buatSurat(['jenis_surat_id' => $this->jenisSurat->id,  'arah' => 'masuk', 'status' => 'final']);
        // Jenis 2 — SK
        $this->buatSurat(['jenis_surat_id' => $this->jenisSurat2->id, 'arah' => 'masuk', 'status' => 'final']);

        $response = $this->actingAs($this->admin)->get(route('laporan.index', [
            'jenis_surat_id' => $this->jenisSurat->id,
        ]));

        // Hanya jenis UM yang terhitung
        $response->assertViewHas('totalMasuk', 1);
        $response->assertViewHas('totalSemua', 1);
    }

    // ─── TEST 10 ──────────────────────────────────────────────────────────────

    public function test_statistik_per_jenis_tidak_menghitung_draft()
    {
        // 2 final, 1 draft — draft tidak boleh muncul di perJenis
        $this->buatSurat(['status' => 'final', 'arah' => 'masuk',  'jenis_surat_id' => $this->jenisSurat->id]);
        $this->buatSurat(['status' => 'final', 'arah' => 'keluar', 'jenis_surat_id' => $this->jenisSurat->id]);
        $this->buatSurat(['status' => 'draft', 'arah' => 'masuk',  'jenis_surat_id' => $this->jenisSurat->id]);

        $response = $this->actingAs($this->admin)->get(route('laporan.index'));

        $perJenis = $response->viewData('perJenis');

        // Harus ada 1 entry untuk jenis UM
        $this->assertCount(1, $perJenis);

        $baris = $perJenis->first();
        // Masuk: 1 final, Keluar: 1 final — draft tidak ikut
        $this->assertEquals(1, $baris['masuk']);
        $this->assertEquals(1, $baris['keluar']);
        $this->assertEquals(2, $baris['total']);
    }
}
