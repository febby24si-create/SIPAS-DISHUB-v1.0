<?php

namespace Tests\Feature\Phase2;

use App\Models\Attachment;
use App\Models\User;
use App\Models\Surat;
use App\Models\JenisSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_attachment_polymorphic_relation()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);
        $surat = Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Test',
            'tanggal_surat' => '2026-09-01',
            'created_by' => $user->id,
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $attachment = new Attachment([
            'original_name' => $file->getClientOriginalName(),
            'file_path' => 'attachments/document.pdf',
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $user->id,
        ]);
        
        $surat->attachments()->save($attachment);

        $this->assertDatabaseHas('attachments', [
            'attachable_type' => Surat::class,
            'attachable_id' => $surat->id,
            'original_name' => 'document.pdf',
            'size' => 102400,
            'uploaded_by' => $user->id,
        ]);

        $this->assertEquals($surat->id, $attachment->attachable->id);
    }
}
