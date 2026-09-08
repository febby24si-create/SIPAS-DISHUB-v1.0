<?php

namespace Tests\Feature\Phase2;

use App\Models\Surat;
use App\Models\User;
use App\Models\JenisSurat;
use App\Models\Pegawai; // Simulating a source model that might be added later, or just use User
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratSourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_surat_source_polymorphic_relation()
    {
        $user = User::factory()->create();
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);
        
        $surat = Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Test Source',
            'tanggal_surat' => '2026-09-01',
            'created_by' => $user->id,
            'source_type' => User::class,
            'source_id' => $user->id,
        ]);

        $this->assertDatabaseHas('surat', [
            'id' => $surat->id,
            'source_type' => User::class,
            'source_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $surat->source);
        $this->assertEquals($user->id, $surat->source->id);
    }
}
