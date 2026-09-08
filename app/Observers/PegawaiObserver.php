<?php

namespace App\Observers;

use App\Models\Pegawai;
use App\Models\RiwayatJabatanPangkat;

class PegawaiObserver
{
    /**
     * Handle the Pegawai "created" event.
     */
    public function created(Pegawai $pegawai): void
    {
        // Initial History
        RiwayatJabatanPangkat::create([
            'pegawai_id' => $pegawai->id,
            'pangkat' => $pegawai->pangkat,
            'golongan' => $pegawai->golongan,
            'jabatan' => $pegawai->jabatan,
            'tmt' => $pegawai->tmt_input ?? now(),
            'keterangan' => 'Data awal Pegawai',
        ]);
    }

    /**
     * Handle the Pegawai "updated" event.
     */
    public function updated(Pegawai $pegawai): void
    {
        // Career History
        if ($pegawai->wasChanged(['pangkat', 'golongan', 'jabatan'])) {
            RiwayatJabatanPangkat::create([
                'pegawai_id' => $pegawai->id,
                'pangkat' => $pegawai->pangkat,
                'golongan' => $pegawai->golongan,
                'jabatan' => $pegawai->jabatan,
                'tmt' => $pegawai->tmt_input ?? now(),
                'keterangan' => 'Pembaruan data karier',
            ]);
        }
    }
}
