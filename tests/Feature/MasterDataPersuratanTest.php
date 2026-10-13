<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\UnitKerja;
use App\Models\User;
use App\Services\DocumentNumberService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Pengujian Master Data Persuratan:
 *  - Jenis Surat (tabel informatif, pencarian, modal Create/Edit, halaman Detail)
 *  - Klasifikasi Surat (tabel informatif, pencarian, modal Create/Edit)
 *  - Integrasi dengan form Surat Masuk / Surat Keluar
 *  - Penghapusan menu Penomoran Surat tanpa memutus penomoran otomatis.
 */
class MasterDataPersuratanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected UnitKerja $bidang;

    protected UnitKerja $seksi;

    protected JenisSurat $jenisSurat;

    protected KlasifikasiSurat $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->bidang = UnitKerja::create([
            'kode_unit' => 'BLL',
            'nama' => 'Bidang Lalu Lintas',
            'parent_id' => null,
        ]);

        $this->seksi = UnitKerja::create([
            'kode_unit' => 'SMR',
            'nama' => 'Seksi Manajemen Rekayasa Lalu Lintas',
            'parent_id' => $this->bidang->id,
        ]);

        $this->jenisSurat = JenisSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode' => '001',
            'nama' => 'Pimpinan',
            'status' => 'aktif',
        ]);
    }

    private function buatSurat(array $overrides = []): Surat
    {
        $arah = $overrides['arah'] ?? 'masuk';

        return Surat::create(array_merge([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'unit_kerja_id' => $this->seksi->id,
            'arah' => $arah,
            'nomor_surat' => null,
            'perihal' => 'Undangan Rapat Koordinasi',
            'pengirim' => $arah === 'masuk' ? 'Dinas Perhubungan' : null,
            'tanggal_surat' => '2026-10-13',
            'tanggal_diterima' => '2026-10-13',
            'status' => 'final',
            'created_by' => $this->user->id,
        ], $overrides));
    }

    // ════════════════════════════════════════════════════════════
    //  A. JENIS SURAT — Tabel, pencarian, modal Create/Edit
    // ════════════════════════════════════════════════════════════

    public function test_index_jenis_surat_menampilkan_tabel_dan_modal_create_edit(): void
    {
        $response = $this->actingAs($this->user)->get(route('jenis-surat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('jenis-surat.index');

        // Informasi tabel
        $response->assertSee('SK');
        $response->assertSee('Surat Keputusan');
        $response->assertSee('id="jenis-surat-table-body"', false);
        $response->assertSee('name="q"', false);           // pencarian
        $response->assertSee('Belum digunakan');            // status penggunaan

        // Perangkat modal Create & Edit
        $response->assertSee('jenisSuratModalHandler');
        $response->assertSee('id="js-modal-create-title"', false);
        $response->assertSee('id="js-modal-edit-title"', false);
        $response->assertSee('openEdit(', false);
        $response->assertSee('submitCreate()');
        $response->assertSee('submitEdit()');
    }

    public function test_pencarian_jenis_surat_menyaring_baris(): void
    {
        JenisSurat::create(['kode' => 'SE', 'nama' => 'Surat Edaran']);

        $response = $this->actingAs($this->user)
            ->get(route('jenis-surat.index', ['q' => 'Keputusan']));

        $response->assertStatus(200);
        $response->assertSee('Surat Keputusan');
        $response->assertDontSee('Surat Edaran');
        $response->assertSee('id="jenis-surat-row-' . $this->jenisSurat->id . '"', false);
    }

    public function test_pencarian_jenis_surat_kosong_menampilkan_keadaan_kosong(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('jenis-surat.index', ['q' => 'tidak-ada']));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada jenis surat yang cocok dengan pencarian.');
    }

    public function test_tabel_jenis_surat_menampilkan_penggunaan_masuk_dan_keluar(): void
    {
        $this->buatSurat(['arah' => 'masuk']);
        $this->buatSurat(['arah' => 'keluar', 'pengirim' => null]);
        $this->buatSurat(['arah' => 'masuk']);

        $response = $this->actingAs($this->user)->get(route('jenis-surat.index'));

        $response->assertStatus(200);
        $response->assertSee('Masuk 2');
        $response->assertSee('Keluar 1');
        $response->assertSee('data-total="3"', false);
        $response->assertDontSee('Belum digunakan');
    }

    public function test_store_jenis_surat_via_modal_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('jenis-surat.store'), ['kode' => 'SPT', 'nama' => 'Surat Perintah Tugas']);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('jenisSurat.kode', 'SPT');
        $response->assertJsonPath('jenisSurat.nama', 'Surat Perintah Tugas');
        $this->assertDatabaseHas('jenis_surat', ['kode' => 'SPT', 'nama' => 'Surat Perintah Tugas']);
    }

    public function test_store_jenis_surat_kosong_mengembalikan_422(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('jenis-surat.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kode', 'nama']);
        $this->assertDatabaseCount('jenis_surat', 1);
    }

    public function test_store_jenis_surat_kode_duplikat_ditolak(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('jenis-surat.store'), ['kode' => 'SK', 'nama' => 'Nama Lain']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kode']);
        $this->assertDatabaseCount('jenis_surat', 1);
    }

    public function test_update_jenis_surat_via_modal_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('jenis-surat.update', $this->jenisSurat), [
                'kode' => 'SKD',
                'nama' => 'Surat Keputusan Direktur',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('jenis_surat', ['id' => $this->jenisSurat->id, 'kode' => 'SKD']);
    }

    public function test_update_jenis_surat_dengan_kode_sendiri_tetap_diizinkan(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('jenis-surat.update', $this->jenisSurat), [
                'kode' => 'SK',
                'nama' => 'Surat Keputusan (diperbarui)',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('jenis_surat', ['id' => $this->jenisSurat->id, 'nama' => 'Surat Keputusan (diperbarui)']);
    }

    public function test_update_jenis_surat_dengan_kode_milik_lain_ditolak(): void
    {
        JenisSurat::create(['kode' => 'SE', 'nama' => 'Surat Edaran']);

        $response = $this->actingAs($this->user)
            ->putJson(route('jenis-surat.update', $this->jenisSurat), [
                'kode' => 'SE',
                'nama' => 'Surat Keputusan',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kode']);
    }

    public function test_form_create_edit_jenis_surat_fallback_masih_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('jenis-surat.create'))
            ->assertStatus(200)
            ->assertViewIs('jenis-surat.form');

        $this->actingAs($this->user)
            ->get(route('jenis-surat.edit', $this->jenisSurat))
            ->assertStatus(200)
            ->assertViewIs('jenis-surat.form');
    }

    public function test_form_biasa_jenis_surat_tetap_terhubung_backend(): void
    {
        $response = $this->actingAs($this->user)->post(route('jenis-surat.store'), [
            'kode' => '',
            'nama' => '',
        ]);

        $response->assertSessionHasErrors(['kode', 'nama']);
        $this->assertDatabaseCount('jenis_surat', 1);

        $berhasil = $this->actingAs($this->user)->post(route('jenis-surat.store'), [
            'kode' => 'SPT',
            'nama' => 'Surat Perintah Tugas',
        ]);

        $berhasil->assertRedirect(route('jenis-surat.index'));
        $berhasil->assertSessionHas('status');
        $this->assertDatabaseHas('jenis_surat', ['kode' => 'SPT']);
    }

    // ── Halaman Detail ──────────────────────────────────────────

    public function test_detail_jenis_surat_menampilkan_statistik_penggunaan(): void
    {
        $this->buatSurat(['arah' => 'masuk']);
        $this->buatSurat(['arah' => 'keluar', 'pengirim' => null]);

        $response = $this->actingAs($this->user)->get(route('jenis-surat.show', $this->jenisSurat));

        $response->assertStatus(200);
        $response->assertViewIs('jenis-surat.show');
        $response->assertSee('Surat Keputusan');
        $response->assertSee('SK');

        $statistik = $response->viewData('statistik');
        $this->assertSame(2, $statistik['total']);
        $this->assertSame(1, $statistik['masuk']);
        $this->assertSame(1, $statistik['keluar']);

        $response->assertSee('Undangan Rapat Koordinasi');
    }

    public function test_detail_jenis_surat_tanpa_surat_menampilkan_belum_digunakan(): void
    {
        $response = $this->actingAs($this->user)->get(route('jenis-surat.show', $this->jenisSurat));

        $response->assertStatus(200);
        $response->assertSee('Jenis surat ini belum dipakai pada surat mana pun.');
        $response->assertSee('Belum ada surat dengan jenis ini.');
    }

    // ── Penghapusan ─────────────────────────────────────────────

    public function test_destroy_jenis_surat_yang_masih_dipakai_surat_ditolak(): void
    {
        $surat = $this->buatSurat();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('jenis-surat.destroy', $this->jenisSurat));

        $response->assertStatus(409);
        $response->assertJson(['success' => false]);

        $this->assertDatabaseHas('jenis_surat', ['id' => $this->jenisSurat->id]);
        $this->assertSame($this->jenisSurat->id, $surat->fresh()->jenis_surat_id);
    }

    public function test_destroy_jenis_surat_yang_masih_dipakai_surat_menghasilkan_pesan_error(): void
    {
        $this->buatSurat();

        $response = $this->actingAs($this->user)
            ->delete(route('jenis-surat.destroy', $this->jenisSurat));

        $response->assertRedirect(route('jenis-surat.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('jenis_surat', ['id' => $this->jenisSurat->id]);
    }

    public function test_destroy_jenis_surat_tanpa_referensi_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson(route('jenis-surat.destroy', $this->jenisSurat));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('jenis_surat', ['id' => $this->jenisSurat->id]);
    }

    // ════════════════════════════════════════════════════════════
    //  B. KLASIFIKASI SURAT — Tabel, pencarian, modal Create/Edit
    // ════════════════════════════════════════════════════════════

    public function test_index_klasifikasi_menampilkan_tabel_dan_modal_create_edit(): void
    {
        $this->buatSurat();

        $response = $this->actingAs($this->user)->get(route('klasifikasi-surat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('klasifikasi-surat.index');

        // Informasi tabel
        $response->assertSee('001');
        $response->assertSee('Pimpinan');
        $response->assertSee('Aktif');
        $response->assertSee('1 surat');
        $response->assertSee('id="klasifikasi-table-body"', false);
        $response->assertSee('name="q"', false);

        // Perangkat modal Create & Edit
        $response->assertSee('klasifikasiModalHandler');
        $response->assertSee('id="kl-modal-create-title"', false);
        $response->assertSee('id="kl-modal-edit-title"', false);
        $response->assertSee('openEdit(', false);
        $response->assertSee('data-status="aktif"', false);
    }

    public function test_index_klasifikasi_menampilkan_keadaan_ketika_klasifikasi_tanpa_surat(): void
    {
        $response = $this->actingAs($this->user)->get(route('klasifikasi-surat.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum digunakan');
    }

    public function test_pencarian_klasifikasi_menyaring_baris(): void
    {
        $kepegawaian = KlasifikasiSurat::create(['kode' => '850', 'nama' => 'Kepegawaian', 'status' => 'aktif']);

        $response = $this->actingAs($this->user)
            ->get(route('klasifikasi-surat.index', ['q' => 'Kepegawaian']));

        $response->assertStatus(200);
        $response->assertSee('Kepegawaian');
        // Baris harus dinilai dari id, bukan nama: nama "Pimpinan" juga muncul
        // sebagai teks placeholder pada modal.
        $response->assertSee('id="klasifikasi-row-' . $kepegawaian->id . '"', false);
        $response->assertDontSee('id="klasifikasi-row-' . $this->klasifikasi->id . '"', false);
    }

    public function test_store_klasifikasi_via_modal_berhasil(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('klasifikasi-surat.store'), [
            'kode' => '850',
            'nama' => 'Kepegawaian',
            'status' => 'aktif',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('klasifikasi.kode', '850');
        $response->assertJsonPath('klasifikasi.status', 'aktif');
        $this->assertDatabaseHas('klasifikasi_surat', ['kode' => '850', 'status' => 'aktif']);
    }

    public function test_store_klasifikasi_validasi_kosong_mengembalikan_422(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('klasifikasi-surat.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kode', 'nama', 'status']);
        $this->assertDatabaseCount('klasifikasi_surat', 1);
    }

    public function test_store_klasifikasi_status_tidak_valid_ditolak(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('klasifikasi-surat.store'), [
            'kode' => '850',
            'nama' => 'Kepegawaian',
            'status' => 'arsip',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
        $this->assertDatabaseCount('klasifikasi_surat', 1);
    }

    public function test_update_klasifikasi_via_modal_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('klasifikasi-surat.update', $this->klasifikasi), [
                'kode' => '001',
                'nama' => 'Pimpinan & Sekretariat',
                'status' => 'nonaktif',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('klasifikasi_surat', [
            'id' => $this->klasifikasi->id,
            'nama' => 'Pimpinan & Sekretariat',
            'status' => 'nonaktif',
        ]);
    }

    public function test_update_klasifikasi_dengan_kode_milik_lain_ditolak(): void
    {
        KlasifikasiSurat::create(['kode' => '850', 'nama' => 'Kepegawaian', 'status' => 'aktif']);

        $response = $this->actingAs($this->user)
            ->putJson(route('klasifikasi-surat.update', $this->klasifikasi), [
                'kode' => '850',
                'nama' => 'Pimpinan',
                'status' => 'aktif',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kode']);
    }

    public function test_form_create_edit_klasifikasi_fallback_masih_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('klasifikasi-surat.create'))
            ->assertStatus(200)
            ->assertViewIs('klasifikasi-surat.form');

        $this->actingAs($this->user)
            ->get(route('klasifikasi-surat.edit', $this->klasifikasi))
            ->assertStatus(200)
            ->assertViewIs('klasifikasi-surat.form');
    }

    // ── Penghapusan & perlindungan relasi arsip ─────────────────

    public function test_destroy_klasifikasi_yang_dipakai_surat_ditolak_untuk_melindungi_arsip_lama(): void
    {
        $surat = $this->buatSurat();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('klasifikasi-surat.destroy', $this->klasifikasi));

        $response->assertStatus(409);
        $response->assertJson(['success' => false]);

        // Data master dan klasifikasi surat lama tetap utuh (tidak dikosongkan).
        $this->assertDatabaseHas('klasifikasi_surat', ['id' => $this->klasifikasi->id]);
        $this->assertSame($this->klasifikasi->id, $surat->fresh()->klasifikasi_id);
    }

    public function test_destroy_klasifikasi_yang_dipakai_surat_menghasilkan_pesan_error(): void
    {
        $this->buatSurat();

        $response = $this->actingAs($this->user)
            ->delete(route('klasifikasi-surat.destroy', $this->klasifikasi));

        $response->assertRedirect(route('klasifikasi-surat.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('klasifikasi_surat', ['id' => $this->klasifikasi->id]);
    }

    public function test_destroy_klasifikasi_tanpa_referensi_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson(route('klasifikasi-surat.destroy', $this->klasifikasi));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('klasifikasi_surat', ['id' => $this->klasifikasi->id]);
    }

    // ════════════════════════════════════════════════════════════
    //  C. Integrasi dengan persuratan
    // ════════════════════════════════════════════════════════════

    public function test_jenis_surat_baru_muncul_pada_form_surat_masuk(): void
    {
        $this->buatSurat();

        $this->actingAs($this->user)
            ->postJson(route('jenis-surat.store'), ['kode' => 'SPT', 'nama' => 'Surat Perintah Tugas'])
            ->assertStatus(201);

        $response = $this->actingAs($this->user)->get(route('surat-masuk.index'));

        $response->assertStatus(200);
        $response->assertSee('Surat Perintah Tugas');
    }

    public function test_klasifikasi_aktif_tampil_dan_nonaktif_tersembunyi_pada_form_surat_masuk(): void
    {
        $this->buatSurat();

        $this->actingAs($this->user)->postJson(route('klasifikasi-surat.store'), [
            'kode' => '999',
            'nama' => 'Klasifikasi Arsip Nonaktif',
            'status' => 'nonaktif',
        ])->assertStatus(201);

        $response = $this->actingAs($this->user)->get(route('surat-masuk.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Klasifikasi Arsip Nonaktif'); // klasifikasi nonaktif disembunyikan

        // Klaim klasifikasi aktif dibaca dari data view, bukan dari HTML, karena
        // Blade meng-escape tanda kutip JSON pada atribut konfigurasi Alpine.
        $klasifikasiList = $response->viewData('klasifikasiList');
        $this->assertTrue($klasifikasiList->contains('kode', '001'));
        $this->assertFalse($klasifikasiList->contains('kode', '999'));
    }

    public function test_mengubah_master_tidak_mengubah_nomor_surat_tersimpan(): void
    {
        $surat = $this->buatSurat(['nomor_surat' => '001/001/10/2026', 'arah' => 'keluar', 'pengirim' => null]);

        $this->actingAs($this->user)
            ->putJson(route('klasifikasi-surat.update', $this->klasifikasi), [
                'kode' => '001',
                'nama' => 'Pimpinan (diubah)',
                'status' => 'aktif',
            ])
            ->assertStatus(200);

        $this->actingAs($this->user)
            ->putJson(route('jenis-surat.update', $this->jenisSurat), [
                'kode' => 'SK',
                'nama' => 'Surat Keputusan (diubah)',
            ])
            ->assertStatus(200);

        $this->assertSame('001/001/10/2026', $surat->fresh()->nomor_surat);
    }

    // ════════════════════════════════════════════════════════════
    //  D. Penghapusan menu Penomoran Surat
    // ════════════════════════════════════════════════════════════

    public function test_menu_nomor_surat_tidak_punya_route_dan_tautan_sidebar(): void
    {
        $this->assertFalse(Route::has('nomor-surat.index'));

        $this->get('/nomor-surat')->assertNotFound();

        $html = $this->actingAs($this->user)
            ->get(route('jenis-surat.index'))
            ->assertStatus(200)
            ->getContent();

        $this->assertStringNotContainsString('nomor-surat', $html);
        $this->assertStringNotContainsString('Nomor Surat</a>', $html);
    }

    public function test_submenu_persuratan_masih_memuat_jenis_dan_klasifikasi_surat(): void
    {
        $html = $this->actingAs($this->user)
            ->get(route('jenis-surat.index'))
            ->assertStatus(200)
            ->getContent();

        // Tautan sidebar sudah dirender menjadi URL oleh Blade.
        $this->assertStringContainsString('/jenis-surat"', $html);
        $this->assertStringContainsString('/klasifikasi-surat"', $html);
    }

    public function test_penomoran_otomatis_masih_bekerja_setelah_menu_nomor_surat_dihapus(): void
    {
        $tanggal = Carbon::create(2026, 10, 1);

        $nomor1 = DocumentNumberService::generate($this->klasifikasi->id, $tanggal);
        $nomor2 = DocumentNumberService::generate($this->klasifikasi->id, $tanggal);

        $this->assertStringContainsString('001', $nomor1);
        $this->assertStringContainsString('001/', $nomor1);  // kode klasifikasi
        $this->assertStringContainsString('2026', $nomor1);
        $this->assertStringContainsString('002', $nomor2);

        // Pratinjau nomor berikutnya tetap tersedia dan tidak menguras urutan.
        $this->assertStringContainsString('003', DocumentNumberService::previewNextNumber($this->klasifikasi->id, $tanggal));
    }
}
