<?php

namespace Tests\Feature;

use App\Helpers\DateGroupHelper;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Feature Test: UI Arsip Digital — pengelompokan tanggal, ikon, dan halaman detail.
 *
 * Waktu dibekukan agar label "Hari Ini"/"Kemarin" dan urutan kelompok deterministik.
 */
class ArsipDigitalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected JenisSurat $jenisSurat;

    protected KlasifikasiSurat $klasifikasi;

    protected UnitKerja $bidang;

    protected UnitKerja $seksi;

    /** Waktu acuan pengujian: Selasa, 13 Oktober 2026 pukul 10:00 WIB. */
    protected const TANGGAL_UJI = '2026-10-13 10:00:00';

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d H:i:s', self::TANGGAL_UJI, DateGroupHelper::timezone()));

        $this->user = User::factory()->create();

        $this->jenisSurat = JenisSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode' => '850',
            'nama' => 'Kepegawaian',
            'status' => 'aktif',
        ]);

        $this->bidang = UnitKerja::create(['nama' => 'Bidang Lalu Lintas', 'kode_unit' => 'BLL', 'parent_id' => null]);
        $this->seksi = UnitKerja::create(['nama' => 'Seksi Manajemen Rekayasa', 'kode_unit' => 'SMR', 'parent_id' => $this->bidang->id]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    // ── 1: Pengelompokan Hari Ini / Kemarin / tanggal sebelumnya ────────

    public function test_arsip_diinput_hari_ini_masuk_kelompok_hari_ini(): void
    {
        $this->buatArsip('Surat Hari Ini', $this->now());

        $tbody = $this->tbody();

        $this->assertStringContainsString('Hari Ini', $tbody);
        $this->assertStringContainsString($this->label(0), $tbody);
        $this->assertSame(['2026-10-13'], $this->groupKeys($tbody));
    }

    public function test_arsip_diinput_kemarin_masuk_kelompok_kemarin(): void
    {
        $this->buatArsip('Surat Kemarin', $this->now()->subDay());

        $tbody = $this->tbody();

        $this->assertStringContainsString('Kemarin', $tbody);
        $this->assertStringContainsString($this->label(1), $tbody);
        $this->assertSame(['2026-10-12'], $this->groupKeys($tbody));
    }

    public function test_arsip_lebih_lama_dikelompokkan_menurut_tanggalnya(): void
    {
        $this->buatArsip('Surat Lama', $this->now()->subDays(3));

        $tbody = $this->tbody();

        // 2026-10-10 = Sabtu, 10 Oktober 2026
        $this->assertStringContainsString('Sabtu, 10 Oktober 2026', $tbody);
        $this->assertStringNotContainsString('Hari Ini', $tbody);
        $this->assertStringNotContainsString('Kemarin', $tbody);
        $this->assertSame(['2026-10-10'], $this->groupKeys($tbody));
    }

    public function test_kelompok_kosong_tidak_ditampilkan(): void
    {
        $this->buatArsip('Hanya Kemarin', $this->now()->subDay());

        $tbody = $this->tbody();

        $this->assertStringNotContainsString('Hari Ini', $tbody);
        $this->assertStringNotContainsString('data-date-group="2026-10-13"', $tbody);
        $this->assertSame(['2026-10-12'], $this->groupKeys($tbody));
    }

    // ── 2 & 3: Urutan terbaru ke terlama, nomor urut, pagination ────────

    public function test_urutan_kelompok_dan_baris_dari_pencatatan_terbaru_ke_terlama(): void
    {
        $this->buatArsip('Surat Lama Sekali', $this->now()->subDays(3));
        $this->buatArsip('Surat Hari Ini', $this->now()->subMinutes(5));
        $this->buatArsip('Surat Kemarin', $this->now()->subDay());

        $tbody = $this->tbody();

        $this->assertSame(['2026-10-13', '2026-10-12', '2026-10-10'], $this->groupKeys($tbody));
        $this->assertBerurutan($tbody, [
            'Hari Ini',
            'Surat Hari Ini',
            'Kemarin',
            'Surat Kemarin',
            'Sabtu, 10 Oktober 2026',
            'Surat Lama Sekali',
        ]);
        $this->assertSame([1, 2, 3], $this->nomorUrut($tbody));
    }

    public function test_nomor_urut_lanjut_antar_halaman_dan_jumlah_memakai_satuan_arsip(): void
    {
        for ($i = 0; $i < 12; $i++) {
            $this->buatArsip('Arsip Nomor '.$i, $this->now()->subMinutes($i));
        }

        $halaman1 = $this->tbody();
        $halaman2 = $this->tbody(['page' => 2]);

        // Nomor urut melanjut (bukan kembali ke 1) walau halaman berikutnya.
        $this->assertSame(range(1, 10), $this->nomorUrut($halaman1));
        $this->assertSame([11, 12], $this->nomorUrut($halaman2));

        // Satu tanggal yang berlanjut ke halaman berikutnya tetap berlabel Hari Ini.
        $this->assertSame(['2026-10-13'], $this->groupKeys($halaman1));
        $this->assertSame(['2026-10-13'], $this->groupKeys($halaman2));

        // Jumlah arsip per kelompok dihitung dari data yang ditampilkan.
        $this->assertStringContainsString('10 arsip', $halaman1);
        $this->assertStringContainsString('2 arsip', $halaman2);
    }

    // ── 3: Pencarian & filter tetap berfungsi ───────────────────────────

    public function test_pencarian_dan_filter_tetap_berfungsi_pada_tabel_berkelompok(): void
    {
        $this->buatArsip('Undangan Rapat Koordinasi', $this->now());
        $this->buatArsip('Permohonan Data Dishub', $this->now()->subDay(), ['arah' => 'keluar']);

        $cari = $this->arsipHtml(['q' => 'Undangan']);
        $tbodyCari = $this->tbodyFrom($cari);

        $this->assertStringContainsString('Undangan Rapat Koordinasi', $tbodyCari);
        $this->assertStringNotContainsString('Permohonan Data Dishub', $tbodyCari);
        $this->assertSame(['2026-10-13'], $this->groupKeys($tbodyCari));

        $keluar = $this->arsipHtml(['arah' => 'keluar']);
        $tbodyKeluar = $this->tbodyFrom($keluar);

        $this->assertStringContainsString('Permohonan Data Dishub', $tbodyKeluar);
        $this->assertStringNotContainsString('Undangan Rapat Koordinasi', $tbodyKeluar);

        $tahun = $this->arsipHtml(['tahun' => '2026']);
        $this->assertStringContainsString('Undangan Rapat Koordinasi', $this->tbodyFrom($tahun));

        $bidang = $this->arsipHtml(['bidang_id' => $this->bidang->id]);
        $this->assertStringContainsString('Undangan Rapat Koordinasi', $this->tbodyFrom($bidang));
    }

    public function test_hanya_surat_final_yang_tampil_termasuk_saat_filter_tahun_aktif(): void
    {
        $this->buatArsip('Arsip Final', $this->now());
        $this->buatArsip('Draft Belum Final', $this->now()->subHour(), ['status' => 'draft']);

        foreach ([[], ['tahun' => '2026']] as $query) {
            $tbody = $this->tbody($query);

            $this->assertStringContainsString('Arsip Final', $tbody);
            $this->assertStringNotContainsString('Draft Belum Final', $tbody);
        }
    }

    // ── 4: Ikon tabel & tooltip/label aksesibel ─────────────────────────

    public function test_ikon_kolom_dan_tombol_aksi_tersedia_dengan_label(): void
    {
        $surat = $this->buatArsip('Arsip Untuk Ikon', $this->now());

        $html = $this->arsipHtml();
        $tbody = $this->tbodyFrom($html);

        // Ikon dekoratif pada header kolom memakai outline SVG dan aria-hidden.
        $this->assertGreaterThanOrEqual(8, substr_count($html, 'aria-hidden="true"'));
        $this->assertStringContainsString('<svg width="13" height="13"', $html);

        // Header kelompok tanggal memakai komponen bersama (ikon kalender + jumlah kelompok).
        $this->assertStringContainsString('class="dtg-row"', $tbody);
        $this->assertStringContainsString('class="dtg-ic" aria-hidden="true"', $tbody);
        $this->assertStringContainsString('1 arsip', $tbody);

        // Tombol aksi Detail: ikon + tooltip + label aksesibel, dan menunjuk ke halaman detail.
        $this->assertStringContainsString('title="Detail"', $tbody);
        $this->assertStringContainsString('aria-label="Detail"', $tbody);
        $this->assertStringContainsString('href="'.route('arsip.show', $surat).'"', $tbody);

        // Ikon dekoratif pada bilah pencarian dan tombol filter.
        $this->assertStringContainsString('aria-label="Cari arsip berdasarkan perihal surat"', $html);
        $this->assertStringContainsString('aria-label="Filter arah surat"', $html);
    }

    // ── 5: Halaman Detail Arsip tetap memakai show.blade.php ────────────

    public function test_tombol_detail_membuka_halaman_detail_arsip(): void
    {
        $surat = $this->buatArsip('Permohonan Pengadaan Rambu', $this->now(), [
            'nomor_surat' => '001/850/2026',
        ]);

        $this->actingAs($this->user)
            ->get(route('arsip.show', $surat))
            ->assertStatus(200)
            ->assertViewIs('arsip.show');
    }

    public function test_halaman_detail_arsip_menampilkan_metadata_dan_ikon(): void
    {
        $surat = $this->buatArsip('Permohonan Pengadaan Rambu', $this->now(), [
            'nomor_surat' => '001/850/2026',
        ]);

        $html = $this->actingAs($this->user)
            ->get(route('arsip.show', $surat))
            ->assertStatus(200)
            ->assertViewIs('arsip.show')
            ->getContent();

        // Semua metadata yang tersedia tetap tampil.
        $this->assertStringContainsString('Permohonan Pengadaan Rambu', $html);
        $this->assertStringContainsString('001/850/2026', $html);
        $this->assertStringContainsString('Tanggal Surat', $html);
        $this->assertStringContainsString('Tanggal Pencatatan', $html);
        $this->assertStringContainsString('Jenis Surat', $html);
        $this->assertStringContainsString('Klasifikasi', $html);
        $this->assertStringContainsString('Unit Kerja', $html);
        $this->assertStringContainsString($this->seksi->nama, $html);
        $this->assertStringContainsString($this->bidang->nama, $html);

        // Ikon label memakai SVG outline aria-hidden, bukan emoji.
        $this->assertStringContainsString('<svg width="13" height="13"', $html);
        $this->assertGreaterThanOrEqual(10, substr_count($html, 'aria-hidden="true"'));

        // Grup metadata yang jelas.
        $this->assertStringContainsString('Informasi Surat', $html);
        $this->assertStringContainsString('Dokumen Surat Resmi', $html);
        $this->assertStringContainsString('Lampiran/Dokumen Pendukung', $html);
        $this->assertStringContainsString('Riwayat / Log Aktivitas', $html);
    }

    // ── 6: Dark mode — pola inline yang ditimpa override CSS tetap ada ──

    public function test_tampilan_memakai_pola_warna_inline_yang_ditangani_dark_mode(): void
    {
        $this->buatArsip('Arsip Dark Mode', $this->now());

        $html = $this->arsipHtml();

        // Selektor [data-theme="dark"] di app.css menimpa warna literal ini.
        $this->assertStringContainsString('background:white; border-radius:20px', $html);
        $this->assertStringContainsString('color:#1e293b', $html);
        $this->assertStringContainsString('background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px', $html);
    }

    // ── 7: Modul Surat Masuk & Surat Keluar tidak berubah ───────────────

    public function test_tabel_surat_masuk_dan_keluar_memakai_satuan_surat_seperti_sebelumnya(): void
    {
        $this->buatArsip('Masuk Untuk Regresi', $this->now(), ['arah' => 'masuk']);

        $masuk = $this->actingAs($this->user)->get(route('surat-masuk.index'))->assertStatus(200)->getContent();
        $this->assertStringContainsString('Jumlah surat pada kelompok tanggal ini', $masuk);
        $this->assertStringContainsString('1 surat', $masuk);

        $keluar = $this->actingAs($this->user)->get(route('surat-keluar.index'))->assertStatus(200)->getContent();
        $this->assertStringContainsString('Jumlah surat pada kelompok tanggal ini', $keluar);
    }

    // ── Helper pengujian ────────────────────────────────────────────────

    private function now(): Carbon
    {
        return Carbon::now('Asia/Jakarta')->copy();
    }

    /** Label tanggal lengkap dengan locale Indonesia, mis. "Selasa, 13 Oktober 2026". */
    private function label(int $hariYangLalu): string
    {
        return $this->now()->subDays($hariYangLalu)->locale('id')->translatedFormat('l, d F Y');
    }

    private function buatArsip(string $perihal, Carbon $createdAt, array $overrides = []): Surat
    {
        $arah = $overrides['arah'] ?? 'masuk';

        $surat = Surat::create(array_merge([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'unit_kerja_id' => $this->seksi->id,
            'arah' => $arah,
            'nomor_surat' => null,
            'perihal' => $perihal,
            'pengirim' => $arah === 'masuk' ? 'Dinas Perhubungan' : null,
            'tanggal_surat' => $createdAt->copy()->format('Y-m-d'),
            'tanggal_diterima' => $createdAt->copy()->format('Y-m-d'),
            'status' => 'final',
            'created_by' => $this->user->id,
        ], $overrides));

        // created_at tidak fillable: diset langsung untuk kebutuhan pengujian.
        $surat->created_at = $createdAt->copy();
        $surat->updated_at = $createdAt->copy();
        $surat->save();

        return $surat->fresh();
    }

    /** Muat halaman Arsip Digital sebagai user yang sudah masuk dan kembalikan HTML-nya. */
    private function arsipHtml(array $query = []): string
    {
        return $this->actingAs($this->user)
            ->get(route('arsip.index', $query))
            ->assertStatus(200)
            ->getContent();
    }

    private function tbody(array $query = []): string
    {
        return $this->tbodyFrom($this->arsipHtml($query));
    }

    /** Ambil isi <tbody> tabel arsip agar tidak tertukar dengan elemen lain di halaman. */
    private function tbodyFrom(string $html): string
    {
        $start = strpos($html, '<tbody id="arsip-table-body">');
        $this->assertNotFalse($start, 'tbody arsip-table-body tidak ditemukan.');

        $end = strpos($html, '</tbody>', $start);
        $this->assertNotFalse($end, 'tbody arsip tidak tertutup dengan benar.');

        return substr($html, $start, $end - $start);
    }

    /** @return array<int, string> */
    private function groupKeys(string $tbody): array
    {
        preg_match_all('~data-date-group="([^"]+)"~', $tbody, $matches);

        return $matches[1];
    }

    /** @return array<int, int> */
    private function nomorUrut(string $tbody): array
    {
        preg_match_all('~<td style="color:#94a3b8; font-size:13px;">(\d+)\.</td>~', $tbody, $matches);

        return array_map('intval', $matches[1]);
    }

    /** Pastikan potongan teks muncul berurutan di dalam keluaran. */
    private function assertBerurutan(string $haystack, array $needles): void
    {
        $posisi = -1;

        foreach ($needles as $needle) {
            $ditemukan = strpos($haystack, $needle, $posisi + 1);
            $this->assertNotFalse($ditemukan, 'Teks "'.$needle.'" tidak ditemukan atau urutannya salah.');
            $posisi = $ditemukan;
        }
    }
}
