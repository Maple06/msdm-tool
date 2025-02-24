<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Participant;
use App\Models\Departement;
use App\Models\Division;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Seed Departements
        $departements = [
            ['id' => 'E-Kraf', 'name' => 'Ekonomi Kreatif'],
            ['id' => 'MedKom', 'name' => 'Media dan Komunikasi'],
            ['id' => 'SDM', 'name' => 'Sumber Daya Manusia'],
            ['id' => 'KesMa', 'name' => 'Kesejahteraan Manusia'],
            ['id' => 'RisTek', 'name' => 'Riset dan Teknologi'],
        ];
        Departement::insert($departements);

        // Seed Divisions
        $divisions = [
            ['id' => 'KWU', 'name' => 'Kewirausahaan', 'departement_code' => 'E-Kraf'],
            ['id' => 'Sponsor', 'name' => 'Sponsorship', 'departement_code' => 'E-Kraf'],
            ['id' => 'MedPub', 'name' => 'Media dan Publikasi', 'departement_code' => 'MedKom'],
            ['id' => 'HubEks', 'name' => 'Hubungan Eksternal', 'departement_code' => 'MedKom'],
            ['id' => 'MSDM', 'name' => 'Management Sumber Daya Manusia', 'departement_code' => 'SDM'],
            ['id' => 'PSDM', 'name' => 'Pengembangan Sumber Daya Manusia', 'departement_code' => 'SDM'],
            ['id' => 'MiBa', 'name' => 'Minat dan Bakat', 'departement_code' => 'KesMa'],
            ['id' => 'RTH', 'name' => 'Rumah Tangga Himpunan', 'departement_code' => 'KesMa'],
            ['id' => 'Akademik', 'name' => 'Akademik', 'departement_code' => 'RisTek'],
            ['id' => 'PemTek', 'name' => 'Pengembangan Teknolgi', 'departement_code' => 'RisTek'],
        ];
        Division::insert($divisions);

        $user = [
            
        ];
    }
}
