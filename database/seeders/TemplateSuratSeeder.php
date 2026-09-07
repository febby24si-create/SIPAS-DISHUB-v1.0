<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TemplateSuratSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info(
            'Template Surat dilewati. Upload template .docx melalui menu Template Surat.'
        );
    }
}