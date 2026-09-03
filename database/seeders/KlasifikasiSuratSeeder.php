<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KlasifikasiSuratSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => '000',   'nama' => 'Umum',                          'status' => 'aktif'],
            ['kode' => '005',   'nama' => 'Perencanaan',                   'status' => 'aktif'],
            ['kode' => '010',   'nama' => 'Keuangan',                      'status' => 'aktif'],
            ['kode' => '020',   'nama' => 'Kepegawaian',                   'status' => 'aktif'],
            ['kode' => '030',   'nama' => 'Perlengkapan & Aset',           'status' => 'aktif'],
            ['kode' => '050',   'nama' => 'Hukum & Perundang-undangan',    'status' => 'aktif'],
            ['kode' => '100',   'nama' => 'Angkutan Jalan',                'status' => 'aktif'],
            ['kode' => '110',   'nama' => 'Angkutan Sungai & Penyeberangan','status' => 'aktif'],
            ['kode' => '120',   'nama' => 'Angkutan Udara',                'status' => 'aktif'],
            ['kode' => '200',   'nama' => 'Keselamatan & Teknik Sarana',   'status' => 'aktif'],
            ['kode' => '300',   'nama' => 'Pengujian Kendaraan Bermotor',  'status' => 'aktif'],
            ['kode' => '400',   'nama' => 'Parkir & Terminal',             'status' => 'aktif'],
            ['kode' => '500',   'nama' => 'Manajemen Rekayasa Lalu Lintas','status' => 'aktif'],
            ['kode' => '600',   'nama' => 'Pengendalian & Operasional',    'status' => 'aktif'],
            ['kode' => '700',   'nama' => 'Pembinaan & Pengembangan SDM',  'status' => 'aktif'],
        ];

        foreach ($data as $item) {
            DB::table('klasifikasi_surat')->updateOrInsert(
                ['kode' => $item['kode']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
