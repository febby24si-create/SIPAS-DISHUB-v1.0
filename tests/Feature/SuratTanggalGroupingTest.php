<?php

namespace Tests\Feature;

use App\Helpers\DateGroupHelper;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Feature Test: Pengelompokan tabel berdasarkan tanggal input (created_at).
 *
 * Berlaku untuk tabel Surat Masuk, Surat Keluar, dan Draft Surat Keluar.
 * Waktu dibekukan agar label tanggal dan urutan kelompok bersifat deterministik.
 */
class SuratTanggalGroupingTest extends TestCase
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

    // ── 1 & 2 & 3: Label kelompok Hari Ini, Kemarin, dan tanggal lama ────

    public function test_surat_yang_diinput_hari_ini_masuk_kelompok_hari_ini(): void
    {
        $this->buatSurat('masuk', 'Surat Hari Ini', $this->now());

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertStringContainsString('Hari Ini', $tbody);
        $this->assertStringContainsString($this->label(0), $tbody);
        $this->assertSame(['2026-10-13'], $this->groupKeys($tbody));
    }

    public function test_surat_yang_diinput_kemarin_masuk_kelompok_kemarin(): void
    {
        $this->buatSurat('masuk', 'Surat Kemarin', $this->now()->subDay());

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertStringContainsString('Kemarin', $tbody);
        $this->assertStringContainsString($this->label(1), $tbody);
        $this->assertSame(['2026-10-12'], $this->groupKeys($tbody));
    }

    public function test_surat_lebih_lama_dikelompokkan_menurut_tanggalnya(): void
    {
        $this->buatSurat('masuk', 'Surat Lama', $this->now()->subDays(3));

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        // 2026-10-10 = Sabtu, 10 Oktober 2026
        $this->assertStringContainsString('Sabtu, 10 Oktober 2026', $tbody);
        $this->assertStringNotContainsString('Hari Ini', $tbody);
        $this->assertStringNotContainsString('Kemarin', $tbody);
        $this->assertSame(['2026-10-10'], $this->groupKeys($tbody));
    }

    // ── 4: Urutan kelompok & data terbaru ke terlama ────────────────────

    public function test_urutan_kelompok_dari_pencatatan_terbaru_ke_terlama(): void
    {
        $this->buatSurat('masuk', 'Surat Lama Sekali', $this->now()->subDays(3));
        $this->buatSurat('masuk', 'Surat Hari Ini', $this->now()->subMinutes(5));
        $this->buatSurat('masuk', 'Surat Kemarin', $this->now()->subDay());

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertSame(['2026-10-13', '2026-10-12', '2026-10-10'], $this->groupKeys($tbody));
        $this->assertBerurutan($tbody, [
            'Hari Ini',
            'Surat Hari Ini',
            'Kemarin',
            'Surat Kemarin',
            'Sabtu, 10 Oktober 2026',
            'Surat Lama Sekali',
        ]);
    }

    // ── 5: Nomor urut tetap lanjut antar halaman ────────────────────────

    public function test_nomor_urut_tetap_lanjut_antar_halaman_dan_label_kelompok_tetap_benar(): void
    {
        for ($i = 0; $i < 12; $i++) {
            $this->buatSurat('masuk', 'Surat Nomor '.$i, $this->now()->subMinutes($i));
        }

        $halaman1 = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');
        $halaman2 = $this->getTbody(route('surat-masuk.index', ['page' => 2]), 'surat-masuk-table-body');

        // Nomor urut melanjut (bukan kembali ke 1) walau halaman berikutnya.
        $this->assertSame(range(1, 10), $this->nomorUrut($halaman1));
        $this->assertSame([11, 12], $this->nomorUrut($halaman2));

        // Satu tanggal yang berlanjut ke halaman berikutnya tetap berlabel Hari Ini.
        $this->assertStringContainsString('Hari Ini', $halaman1);
        $this->assertStringContainsString('Hari Ini', $halaman2);
        $this->assertSame(['2026-10-13'], $this->groupKeys($halaman1));
        $this->assertSame(['2026-10-13'], $this->groupKeys($halaman2));

        // Jumlah surat pada kelompok dihitung dari halaman aktif.
        $this->assertStringContainsString('10 surat', $halaman1);
        $this->assertStringContainsString('2 surat', $halaman2);
    }

    // ── 6: Pencarian & filter tetap berfungsi ───────────────────────────

    public function test_pencarian_dan_filter_tetap_berfungsi_di_tabel_berkelompok(): void
    {
        $this->buatSurat('masuk', 'Undangan Rapat Koordinasi', $this->now());
        $this->buatSurat('masuk', 'Permohonan Data Dishub', $this->now()->subDay());

        $html = $this->actingAs($this->user)
            ->get(route('surat-masuk.index', ['q' => 'Undangan']))
            ->assertStatus(200)
            ->getContent();

        $tbody = $this->tbody($html);

        $this->assertStringContainsString('Undangan Rapat Koordinasi', $tbody);
        $this->assertStringNotContainsString('Permohonan Data Dishub', $tbody);
        $this->assertSame(['2026-10-13'], $this->groupKeys($tbody));
        $this->assertStringContainsString('Hari Ini', $tbody);
        $this->assertStringNotContainsString('Kemarin', $tbody);

        // Filter status juga tetap dihormati.
        $statusHtml = $this->actingAs($this->user)
            ->get(route('surat-masuk.index', ['status' => 'diproses']))
            ->assertStatus(200)
            ->getContent();

        $this->assertStringNotContainsString('Undangan Rapat Koordinasi', $this->tbody($statusHtml));
    }

    // ── 7: Tanggal berbeda tidak tercampur ──────────────────────────────

    public function test_surat_dengan_tanggal_berbeda_tidak_tercampur_dalam_kelompok_yang_sama(): void
    {
        $this->buatSurat('masuk', 'Surat Hari Ini A', $this->now());
        $this->buatSurat('masuk', 'Surat Hari Ini B', $this->now()->subMinute());
        $this->buatSurat('masuk', 'Surat Kemarin A', $this->now()->subDay());

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertSame(['2026-10-13', '2026-10-12'], $this->groupKeys($tbody));
        $this->assertSame(2, substr_count($tbody, 'data-date-group='));

        // Isi kelompok Hari Ini hanya berisi surat hari ini.
        $hariIni = substr($tbody, strpos($tbody, 'data-date-group="2026-10-13"'), strpos($tbody, 'data-date-group="2026-10-12"') - strpos($tbody, 'data-date-group="2026-10-13"'));
        $this->assertStringContainsString('Surat Hari Ini A', $hariIni);
        $this->assertStringContainsString('Surat Hari Ini B', $hariIni);
        $this->assertStringNotContainsString('Surat Kemarin A', $hariIni);
        $this->assertStringContainsString('2 surat', $hariIni);
    }

    // ── Kelompok kosong tidak ditampilkan ───────────────────────────────

    public function test_kelompok_tanpa_data_tidak_ditampilkan(): void
    {
        $this->buatSurat('masuk', 'Hanya Kemarin', $this->now()->subDay());

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertStringNotContainsString('Hari Ini', $tbody);
        $this->assertStringNotContainsString('2026-10-13', $tbody);
        $this->assertSame(['2026-10-12'], $this->groupKeys($tbody));
    }

    // ── 8: Zona waktu Asia/Jakarta & pergantian tengah malam ────────────

    public function test_pergantian_tengah_malam_memakai_zona_waktu_asia_jakarta(): void
    {
        // 23:30 hari sebelumnya (waktu Jakarta) tetap dianggap Kemarin.
        $this->buatSurat('masuk', 'Menjelang Tengah Malam', Carbon::createFromFormat('Y-m-d H:i:s', '2026-10-12 23:30:00', 'Asia/Jakarta'));
        // 00:15 hari berjalan (waktu Jakarta) sudah masuk Hari Ini.
        $this->buatSurat('masuk', 'Lewat Tengah Malam', Carbon::createFromFormat('Y-m-d H:i:s', '2026-10-13 00:15:00', 'Asia/Jakarta'));

        $tbody = $this->getTbody(route('surat-masuk.index'), 'surat-masuk-table-body');

        $this->assertSame(['2026-10-13', '2026-10-12'], $this->groupKeys($tbody));
        $this->assertBerurutan($tbody, ['Hari Ini', 'Lewat Tengah Malam', 'Kemarin', 'Menjelang Tengah Malam']);
    }

    public function test_helper_mengonversi_created_at_zona_lain_ke_asia_jakarta(): void
    {
        // 12 Okt 2026 20:00 UTC = 13 Okt 2026 03:00 WIB → harus masuk kelompok Hari Ini.
        // Memakai objek polos agar nilai created_at benar-benar berzona UTC
        // (cast tanggal Eloquent menormalkan nilai ke zona waktu aplikasi).
        $item = new \stdClass;
        $item->created_at = Carbon::parse('2026-10-12 20:00:00', 'UTC');

        $paginator = new LengthAwarePaginator([$item], 1, 10, 1);

        $groups = DateGroupHelper::groupByInputDate($paginator, 'Asia/Jakarta');

        $this->assertCount(1, $groups);
        $this->assertSame('2026-10-13', $groups[0]['key']);
        $this->assertSame('Hari Ini', $groups[0]['prefix']);
        $this->assertSame('Selasa, 13 Oktober 2026', $groups[0]['label']);
    }

    // ── Tabel Surat Keluar & Draft ──────────────────────────────────────

    public function test_tabel_surat_keluar_dikelompokkan_berdasarkan_tanggal_input(): void
    {
        $this->buatSurat('keluar', 'Surat Keluar Hari Ini', $this->now(), 'final', ['nomor_surat' => '001/850/2026']);
        $this->buatSurat('keluar', 'Surat Keluar Kemarin', $this->now()->subDay(), 'final', ['nomor_surat' => '002/850/2026']);

        $tbody = $this->getTbody(route('surat-keluar.index'), 'sk-table-body');

        $this->assertSame(['2026-10-13', '2026-10-12'], $this->groupKeys($tbody));
        $this->assertBerurutan($tbody, ['Hari Ini', 'Surat Keluar Hari Ini', 'Kemarin', 'Surat Keluar Kemarin']);
        $this->assertSame([1, 2], $this->nomorUrut($tbody));
    }

    public function test_halaman_draft_dikelompokkan_dan_nomor_urut_dipertahankan(): void
    {
        $this->buatSurat('keluar', 'Draft Hari Ini', $this->now(), 'draft');
        $this->buatSurat('keluar', 'Draft Kemarin', $this->now()->subDay(), 'draft');

        $tbody = $this->getTbody(route('surat-keluar.draft'), 'skd-tbody');

        $this->assertSame(['2026-10-13', '2026-10-12'], $this->groupKeys($tbody));
        // Nomor urut per halaman (1, 2) tetap seperti implementasi sebelumnya.
        $this->assertSame([1, 2], $this->nomorUrut($tbody));
        $this->assertBerurutan($tbody, ['Hari Ini', 'Draft Hari Ini', 'Kemarin', 'Draft Kemarin']);
    }

    // ── 9: Modal & aksi tetap tersedia ──────────────────────────────────

    public function test_modal_dan_tombol_aksi_tetap_tersedia_setelah_pengelompokan(): void
    {
        $suratMasuk = $this->buatSurat('masuk', 'Surat Untuk Modal', $this->now());

        $html = $this->actingAs($this->user)
            ->get(route('surat-masuk.index'))
            ->assertStatus(200)
            ->getContent();

        // Modal Tambah/Detail/Edit tidak hilang.
        $this->assertStringContainsString('sm-modal-create-title', $html);
        $this->assertStringContainsString('sm-modal-detail-title', $html);
        $this->assertStringContainsString('sm-modal-edit-title', $html);

        // Baris data tetap membawa id, tombol aksi, dan penanda kelompok.
        $tbody = $this->tbody($html);
        $this->assertStringContainsString('id="surat-masuk-row-'.$suratMasuk->id.'"', $tbody);
        $this->assertStringContainsString('data-surat-row', $tbody);
        $this->assertStringContainsString('openDetail('.$suratMasuk->id.')', $tbody);
        $this->assertStringContainsString('title="Detail"', $tbody);
        $this->assertStringContainsString('title="Edit"', $tbody);

        // Template kelompok Hari Ini tersedia untuk penyisipan baris via AJAX.
        $this->assertStringContainsString('id="sm-today-group-template"', $html);
        $this->assertStringContainsString('data-today="1"', $html);
    }

    public function test_dokumen_dan_tombol_hapus_draft_tetap_berfungsi(): void
    {
        $draft = $this->buatSurat('keluar', 'Draft Dengan Aksi', $this->now(), 'draft');

        $html = $this->actingAs($this->user)
            ->get(route('surat-keluar.draft'))
            ->assertStatus(200)
            ->getContent();

        $tbody = $this->tbody($html, 'skd-tbody');
        $this->assertStringContainsString('id="skd-row-'.$draft->id.'"', $tbody);
        $this->assertStringContainsString('data-url="'.route('surat-keluar.destroy', $draft).'"', $tbody);
        $this->assertStringContainsString('title="Hapus"', $tbody);
        $this->assertStringContainsString('skd-btn-danger', $html);

        // Halaman Surat Keluar tetap memiliki unduh dokumen pada modal Detail.
        $skHtml = $this->actingAs($this->user)->get(route('surat-keluar.index'))->getContent();
        $this->assertStringContainsString('sk-modal-detail-title', $skHtml);
        $this->assertStringContainsString('sk-modal-create-title', $skHtml);
        $this->assertStringContainsString('sk-modal-edit-title', $skHtml);
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

    private function buatSurat(
        string $arah,
        string $perihal,
        Carbon $createdAt,
        string $status = 'final',
        array $overrides = []
    ): Surat {
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
            'status' => $status,
            'created_by' => $this->user->id,
        ], $overrides));

        // created_at tidak fillable: diset langsung untuk kebutuhan pengujian.
        $surat->created_at = $createdAt->copy();
        $surat->updated_at = $createdAt->copy();
        $surat->save();

        return $surat->fresh();
    }

    private function getTbody(string $route, string $tbodyId): string
    {
        $html = $this->actingAs($this->user)->get($route)->assertStatus(200)->getContent();

        return $this->tbody($html, $tbodyId);
    }

    /** Ambil isi <tbody> tertentu agar tidak tertukar dengan elemen lain di halaman. */
    private function tbody(string $html, string $tbodyId = 'surat-masuk-table-body'): string
    {
        $start = strpos($html, '<tbody id="'.$tbodyId.'">');
        $this->assertNotFalse($start, 'tbody '.$tbodyId.' tidak ditemukan.');

        $end = strpos($html, '</tbody>', $start);
        $this->assertNotFalse($end, 'tbody '.$tbodyId.' tidak tertutup dengan benar.');

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
