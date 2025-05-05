<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            'Administração',
            'Consultor',
            'Contabilidade',
            'Engenheiro de Software',
            'Logística',
            'Recursos Humanos',
        ];

        foreach ($professions as $name) {
            Profession::firstOrCreate(['profession_name' => $name]);
        }
    }
}
