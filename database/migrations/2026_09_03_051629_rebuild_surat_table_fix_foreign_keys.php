<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rebuild tabel `surat` karena FK constraint di SQLite salah akibat
 * migration asli diubah setelah dijalankan.
 *
 * FK yang salah (contoh):  template_surat_id -> table "template_surat_id"
 * FK yang benar:           template_surat_id -> table "template_surat"
 *
 * SQLite tidak mendukung ALTER TABLE DROP/ADD CONSTRAINT, sehingga
 * satu-satunya cara adalah membuat ulang tabel dengan DDL yang benar.
 * Karena tabel masih kosong pada titik ini, prosesnya aman.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        // 1. Simpan data yang ada (kalau nanti ada isinya)
        DB::statement('CREATE TABLE IF NOT EXISTS surat_backup AS SELECT * FROM surat');

        // 2. Hapus tabel lama
        DB::statement('DROP TABLE IF EXISTS surat');

        // 3. Buat ulang tabel dengan FK yang BENAR
        DB::statement("
            CREATE TABLE \"surat\" (
                \"id\"                integer primary key autoincrement not null,
                \"jenis_surat_id\"    integer not null,
                \"klasifikasi_id\"    integer,
                \"template_surat_id\" integer,
                \"arah\"              varchar check (\"arah\" in ('masuk','keluar')) not null,
                \"nomor_surat\"       varchar,
                \"tanggal_surat\"     date,
                \"tanggal_diterima\"  date,
                \"perihal\"           varchar not null,
                \"pengirim\"          varchar,
                \"tujuan\"            varchar,
                \"file_word\"         varchar,
                \"file_pdf\"          varchar,
                \"file_dokumen\"      varchar,
                \"status\"            varchar not null default 'draft',
                \"created_by\"        integer not null,
                \"created_at\"        datetime,
                \"updated_at\"        datetime,
                foreign key(\"jenis_surat_id\")    references \"jenis_surat\"(\"id\"),
                foreign key(\"klasifikasi_id\")    references \"klasifikasi_surat\"(\"id\") on delete set null,
                foreign key(\"template_surat_id\") references \"template_surat\"(\"id\") on delete set null,
                foreign key(\"created_by\")        references \"users\"(\"id\")
            )
        ");

        // 4. Buat indeks yang ada di migration asli
        DB::statement('CREATE INDEX IF NOT EXISTS surat_nomor_surat_tanggal_surat_perihal_index ON surat (nomor_surat, tanggal_surat, perihal)');

        // 5. Restore data dari backup
        DB::statement("
            INSERT INTO surat
            SELECT id, jenis_surat_id, klasifikasi_id, template_surat_id, arah,
                   nomor_surat, tanggal_surat, tanggal_diterima, perihal, pengirim,
                   tujuan, file_word, file_pdf, file_dokumen, status, created_by,
                   created_at, updated_at
            FROM surat_backup
        ");

        // 6. Hapus tabel backup
        DB::statement('DROP TABLE IF EXISTS surat_backup');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // Tidak ada rollback yang safe untuk ini tanpa backup penuh
        // Pastikan migration ini tidak di-rollback sembarangan
    }
};
