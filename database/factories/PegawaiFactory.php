<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nip'          => $this->faker->unique()->numerify('####################'),
            'nama'         => $this->faker->name(),
            'pangkat'      => 'Penata',
            'golongan'     => 'III/c',
            'jabatan'      => 'Staf',
            'unit_kerja_id'=> null,
            'status_aktif' => true,
        ];
    }
}
