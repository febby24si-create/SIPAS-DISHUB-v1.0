<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PegawaiModalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Pegawai $pegawai;

    protected UnitKerja $unitKerja;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->unitKerja = UnitKerja::create([
            'kode_unit' => 'B.01',
            'nama' => 'Bidang Lalu Lintas',
            'parent_id' => null,
        ]);

        $this->pegawai = Pegawai::create([
            'nip' => '198501012010011001',
            'nama' => 'Ahmad Pegawai, S.T.',
            'pangkat' => 'Penata',
            'golongan' => 'III/c',
            'jabatan' => 'Kepala Seksi',
            'unit_kerja_id' => $this->unitKerja->id,
            'status_aktif' => true,
            'tempat_lahir' => 'Pekanbaru',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'pendidikan_terakhir' => 'S1 Teknik Sipil',
        ]);
    }

    public function test_pegawai_index_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get(route('pegawai.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Pegawai');
        $response->assertSee('modal-detail-title');
        $response->assertSee('modal-edit-title');
        $response->assertSee('modal-create-title');
        $response->assertSee($this->pegawai->nama);
    }

    public function test_pegawai_show_returns_json_when_requested_via_ajax(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('pegawai.show', $this->pegawai));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'pegawai' => [
                    'id' => $this->pegawai->id,
                    'nip' => $this->pegawai->nip,
                    'nama' => $this->pegawai->nama,
                    'unit_kerja_id' => $this->unitKerja->id,
                ],
            ]);
    }

    public function test_pegawai_show_fallback_returns_blade_view(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('pegawai.show', $this->pegawai));

        $response->assertStatus(200);
        $response->assertViewIs('pegawai.show');
        $response->assertSee($this->pegawai->nama);
    }

    public function test_pegawai_update_returns_json_when_requested_via_ajax(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('pegawai.update', $this->pegawai), [
                'nip' => $this->pegawai->nip,
                'nama' => 'Ahmad Pegawai, M.T.',
                'jabatan' => 'Kepala Bidang',
                'pangkat' => 'Penata Tingkat I',
                'golongan' => 'III/d',
                'unit_kerja_id' => $this->unitKerja->id,
                'status_aktif' => 1,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pegawai berhasil diperbarui.',
                'pegawai' => [
                    'id' => $this->pegawai->id,
                    'nama' => 'Ahmad Pegawai, M.T.',
                    'jabatan' => 'Kepala Bidang',
                ],
            ]);

        $this->assertDatabaseHas('pegawai', [
            'id' => $this->pegawai->id,
            'nama' => 'Ahmad Pegawai, M.T.',
            'jabatan' => 'Kepala Bidang',
        ]);
    }

    public function test_pegawai_update_validation_error_returns_422_json(): void
    {
        // Missing required 'nama'
        $response = $this->actingAs($this->user)
            ->putJson(route('pegawai.update', $this->pegawai), [
                'nip' => $this->pegawai->nip,
                'nama' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nama']);
    }

    public function test_pegawai_edit_page_fallback_is_still_accessible(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('pegawai.edit', $this->pegawai));

        $response->assertStatus(200);
        $response->assertViewIs('pegawai.form');
        $response->assertSee($this->pegawai->nip);
    }

    public function test_pegawai_create_page_fallback_is_still_accessible(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('pegawai.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pegawai.form');
        $response->assertSee('Tambah Pegawai');
    }

    public function test_pegawai_store_creates_record_and_returns_201_json(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('pegawai.store'), [
                'nip' => '199002022015021002',
                'nama' => 'Budi Santoso, S.Kom.',
                'pangkat' => 'Penata Muda',
                'golongan' => 'III/a',
                'jabatan' => 'Pranata Komputer Pertama',
                'unit_kerja_id' => $this->unitKerja->id,
                'status_aktif' => 1,
                'tempat_lahir' => 'Dumai',
                'tanggal_lahir' => '1990-02-02',
                'jenis_kelamin' => 'Laki-laki',
                'pendidikan_terakhir' => 'S1 Teknik Informatika',
                'tmt_pangkat' => '2020-04-01',
                'tmt_jabatan' => '2020-04-01',
                'tmt' => '2020-04-01',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pegawai berhasil ditambahkan beserta histori awalnya.',
                'pegawai' => [
                    'nip' => '199002022015021002',
                    'nama' => 'Budi Santoso, S.Kom.',
                    'jabatan' => 'Pranata Komputer Pertama',
                    'unit_kerja_id' => $this->unitKerja->id,
                ],
            ]);

        $this->assertDatabaseHas('pegawai', [
            'nip' => '199002022015021002',
            'nama' => 'Budi Santoso, S.Kom.',
        ]);

        $this->assertDatabaseHas('riwayat_jabatan_pangkat', [
            'keterangan' => 'Data awal Pegawai',
            'jabatan' => 'Pranata Komputer Pertama',
        ]);
    }

    public function test_pegawai_store_validation_error_returns_422_json(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('pegawai.store'), [
                'nip' => '',
                'nama' => '',
                'tmt' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nip', 'nama', 'tmt']);
    }
}
