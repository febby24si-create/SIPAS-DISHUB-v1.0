<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Feature Test: Modal Surat Keluar
 *
 * Memverifikasi bahwa implementasi modal AJAX pada halaman index
 * Surat Keluar tidak merusak aturan bisnis yang sudah ada.
 *
 * Catatan SQLite: ArsipController menggunakan YEAR() yang tidak tersedia
 * di SQLite. Test ini menggunakan pemeriksaan route sebagai pengganti.
 */
class SuratKeluarModalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected JenisSurat $jenisSurat;

    protected KlasifikasiSurat $klasifikasi;

    protected UnitKerja $bidang;

    protected UnitKerja $seksi;

    protected Surat $suratFinal;

    protected Surat $suratDraft;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->jenisSurat = JenisSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);

        $this->klasifikasi = KlasifikasiSurat::create([
            'kode' => '850',
            'nama' => 'Kepegawaian',
            'status' => 'aktif',
        ]);

        $this->bidang = UnitKerja::create(['nama' => 'Bidang Lalu Lintas', 'kode_unit' => 'BLL', 'parent_id' => null]);
        $this->seksi = UnitKerja::create(['nama' => 'Seksi Manajemen Rekayasa', 'kode_unit' => 'SMR', 'parent_id' => $this->bidang->id]);

        $this->suratFinal = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'unit_kerja_id' => $this->seksi->id,
            'arah' => 'keluar',
            'nomor_surat' => '001/SK/10/2026',
            'perihal' => 'Surat Final Test',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'final',
            'created_by' => $this->user->id,
        ]);

        $this->suratDraft = Surat::create([
            'jenis_surat_id' => $this->jenisSurat->id,
            'unit_kerja_id' => $this->seksi->id,
            'arah' => 'keluar',
            'perihal' => 'Draft Surat Test',
            'tanggal_surat' => now()->format('Y-m-d'),
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);
    }

    // ── Test 1: Index dapat dirender ────────────────────────────────────

    public function test_index_surat_keluar_dapat_dirender(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('surat-keluar.index'));

        $response->assertStatus(200);
        $response->assertSee('Surat Keluar');
        // Hanya surat final yang muncul di index
        $response->assertSee('Surat Final Test');
        // Draft tidak muncul di index
        $response->assertDontSee('Draft Surat Test');
    }

    // ── Test 2: JSON detail surat ────────────────────────────────────────

    public function test_show_surat_keluar_mengembalikan_json_untuk_ajax(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('surat-keluar.show', $this->suratFinal));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'surat' => [
                    'id' => $this->suratFinal->id,
                    'perihal' => 'Surat Final Test',
                    'status' => 'final',
                    'is_final' => true,
                    'nomor_surat' => '001/SK/10/2026',
                ],
            ]);
    }

    public function test_show_surat_keluar_tetap_render_html_untuk_request_biasa(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('surat-keluar.show', $this->suratFinal));

        $response->assertStatus(200);
        $response->assertSee('Detail Surat Keluar');
        $response->assertSee('Surat Final Test');
    }

    // ── Test 3: Store via AJAX ──────────────────────────────────────────

    public function test_store_surat_keluar_via_ajax_berhasil_membuat_data(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('surat-keluar.store'), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'klasifikasi_id' => $this->klasifikasi->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Surat Baru Via Modal',
                'tujuan' => 'Dinas Perhubungan Pusat',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'surat' => [
                    'perihal' => 'Surat Baru Via Modal',
                    'status' => 'final',
                    'is_final' => true,
                ],
            ]);

        $this->assertDatabaseHas('surat', [
            'perihal' => 'Surat Baru Via Modal',
            'arah' => 'keluar',
            'status' => 'final',
        ]);

        // Nomor surat harus ter-generate secara otomatis
        $saved = Surat::where('perihal', 'Surat Baru Via Modal')->first();
        $this->assertNotNull($saved?->nomor_surat, 'Nomor surat harus di-generate otomatis untuk surat final.');
    }

    public function test_store_draft_via_ajax_tidak_menghasilkan_nomor_surat(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('surat-keluar.store'), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Draft Baru Via Modal',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'draft',
            ]);

        $response->assertStatus(201);

        $saved = Surat::where('perihal', 'Draft Baru Via Modal')->first();
        $this->assertNotNull($saved);
        $this->assertEquals('draft', $saved->status);
        $this->assertNull($saved->nomor_surat, 'Draft tidak boleh mendapat nomor surat.');
    }

    // ── Test 4: Validasi gagal ──────────────────────────────────────────

    public function test_store_tanpa_perihal_menghasilkan_422(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('surat-keluar.store'), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                // perihal tidak diisi
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['perihal']);
    }

    public function test_store_dengan_seksi_yang_tidak_cocok_bidang_menghasilkan_422(): void
    {
        $bidangLain = UnitKerja::create(['nama' => 'Bidang Lain', 'kode_unit' => 'BLN', 'parent_id' => null]);

        $response = $this->actingAs($this->user)
            ->postJson(route('surat-keluar.store'), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $bidangLain->id, // Bidang lain
                'unit_kerja_id' => $this->seksi->id, // Seksi dari bidang berbeda
                'perihal' => 'Test Tidak Cocok',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['unit_kerja_id']);
    }

    // ── Test 5: Update (Draft only) ────────────────────────────────────

    public function test_update_draft_via_ajax_berhasil(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('surat-keluar.update', $this->suratDraft), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Draft Diperbarui Via Modal',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'draft',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'surat' => [
                    'perihal' => 'Draft Diperbarui Via Modal',
                    'status' => 'draft',
                ],
            ]);

        $this->assertEquals('Draft Diperbarui Via Modal', $this->suratDraft->fresh()->perihal);
        $this->assertNull($this->suratDraft->fresh()->nomor_surat, 'Draft yang masih draft tidak boleh mendapat nomor.');
    }

    public function test_finalisasi_draft_via_ajax_menghasilkan_nomor_surat(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('surat-keluar.update', $this->suratDraft), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'klasifikasi_id' => $this->klasifikasi->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Draft Difinalkan',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'surat' => ['status' => 'final', 'is_final' => true],
            ]);

        $fresh = $this->suratDraft->fresh();
        $this->assertEquals('final', $fresh->status);
        $this->assertNotNull($fresh->nomor_surat, 'Finalisasi harus menghasilkan nomor surat.');
    }

    // ── Test 6: Surat final tidak dapat diedit ────────────────────────

    public function test_update_surat_final_menghasilkan_403(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('surat-keluar.update', $this->suratFinal), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Coba Ubah Final',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'draft',
            ]);

        $response->assertStatus(403);

        // Data tidak berubah
        $this->assertEquals('Surat Final Test', $this->suratFinal->fresh()->perihal);
        $this->assertEquals('001/SK/10/2026', $this->suratFinal->fresh()->nomor_surat);
    }

    public function test_edit_page_surat_final_menghasilkan_403(): void
    {
        $this->actingAs($this->user)
            ->get(route('surat-keluar.edit', $this->suratFinal))
            ->assertStatus(403);
    }

    // ── Test 7: Upload file ────────────────────────────────────────────

    public function test_store_dengan_file_upload_berhasil(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->postJson(route('surat-keluar.store'), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Surat Dengan File',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
                'file_dokumen' => UploadedFile::fake()->create('surat.pdf', 512, 'application/pdf'),
            ]);

        $response->assertStatus(201);

        $saved = Surat::where('perihal', 'Surat Dengan File')->first();
        $this->assertNotNull($saved);
        $this->assertNotNull($saved->file_dokumen);
        Storage::disk('public')->assertExists($saved->file_dokumen);
    }

    public function test_update_tanpa_file_baru_mempertahankan_file_lama(): void
    {
        Storage::fake('public');

        // Setup file lama
        $oldPath = 'surat-keluar/lama.pdf';
        Storage::disk('public')->put($oldPath, 'old content');
        $this->suratDraft->update(['file_dokumen' => $oldPath]);

        // Update tanpa file baru
        $this->actingAs($this->user)
            ->putJson(route('surat-keluar.update', $this->suratDraft), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Update Tanpa File Baru',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'draft',
            ])->assertStatus(200);

        $this->suratDraft->refresh();
        $this->assertEquals($oldPath, $this->suratDraft->file_dokumen, 'File lama harus dipertahankan.');
        Storage::disk('public')->assertExists($oldPath);
    }

    // ── Test 8: Akses tanpa auth ditolak ──────────────────────────────

    public function test_index_tanpa_auth_diredirect_login(): void
    {
        $this->get(route('surat-keluar.index'))->assertRedirect(route('login'));
    }

    public function test_show_json_tanpa_auth_diredirect_login(): void
    {
        $this->getJson(route('surat-keluar.show', $this->suratFinal))
            ->assertStatus(401);
    }

    // ── Test 9: Nomor surat aman ────────────────────────────────────────

    public function test_nomor_surat_tidak_berubah_saat_draft_diperbarui(): void
    {
        // Finalisasi dahulu agar ada nomor
        $this->suratDraft->update(['status' => 'draft', 'nomor_surat' => null]);

        $before = $this->suratFinal->nomor_surat;

        // Update draft biasa — tidak memengaruhi surat final
        $this->actingAs($this->user)
            ->putJson(route('surat-keluar.update', $this->suratDraft), [
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Edit Draft Lagi',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'draft',
            ])->assertStatus(200);

        $this->assertEquals($before, $this->suratFinal->fresh()->nomor_surat, 'Nomor surat final tidak boleh berubah.');
        $this->assertNull($this->suratDraft->fresh()->nomor_surat, 'Draft yang masih draft tidak boleh punya nomor.');
    }

    // ── Test 10: Halaman lama masih bisa diakses ────────────────────────

    public function test_halaman_create_lama_masih_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('surat-keluar.create'))
            ->assertStatus(200)
            ->assertSee('Input Surat Keluar');
    }

    public function test_halaman_edit_draft_lama_masih_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('surat-keluar.edit', $this->suratDraft))
            ->assertStatus(200)
            ->assertSee('Draft Surat Test');
    }

    public function test_halaman_show_lama_masih_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('surat-keluar.show', $this->suratFinal))
            ->assertStatus(200)
            ->assertSee('Surat Final Test');
    }

    // ── Test 11: Halaman Draft masih bisa diakses ──────────────────────

    public function test_halaman_draft_menampilkan_surat_draft(): void
    {
        $this->actingAs($this->user)
            ->get(route('surat-keluar.draft'))
            ->assertStatus(200)
            ->assertSee('Draft Surat Test');
    }

    // ── Test 12: Route arsip masih terdaftar ────────────────────────────

    /**
     * ArsipController menggunakan YEAR() yang tidak tersedia di SQLite.
     * Verifikasi hanya melalui tabel route tanpa menghit request.
     */
    public function test_arsip_routes_masih_terdaftar(): void
    {
        $this->assertTrue(Route::has('arsip.index'), 'Route arsip.index harus terdaftar');
        $this->assertTrue(Route::has('arsip.show'), 'Route arsip.show harus terdaftar');
    }

    // ── Test 13: Request form konvensional tetap redirect ───────────────

    public function test_store_konvensional_tanpa_ajax_tetap_redirect(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('surat-keluar.store'), [
                '_token' => csrf_token(),
                'jenis_surat_id' => $this->jenisSurat->id,
                'bidang_id' => $this->bidang->id,
                'unit_kerja_id' => $this->seksi->id,
                'perihal' => 'Form Konvensional',
                'tanggal_surat' => now()->format('Y-m-d'),
                'status' => 'final',
            ]);

        // Harus redirect ke show, bukan JSON
        $response->assertRedirect();
    }
}
