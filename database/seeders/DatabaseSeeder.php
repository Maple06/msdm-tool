<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $departement = [
            ['id'=>'E-Kraf','name'=>'Ekonomi Kreatif'],
            ['id'=>'MedKom','name'=>'Media dan Komunikasi'],
            ['id'=>'SDM','name'=>'Sumber Daya Manusia'],
            ['id'=>'KesMa','name'=>'Kesejahteraan Manusia'],
            ['id'=>'RisTek','name'=>'Riset dan Teknologi']
        ];

        $division = [
            ['id'=>'KWU','name'=>'Kewirausahaan','dep'=>'E-Kraf'],
            ['id'=>'Sponsor','name'=>'Sponsorship','dep'=>'E-Kraf'],
            ['id'=>'MedPub','name'=>'Media dan Publikasi','dep'=>'MedKom'],
            ['id'=>'HubEks','name'=>'Hubungan Eksternal','dep'=>'MedKom'],
            ['id'=>'MSDM','name'=>'Management Sumber Daya Manusia','dep'=>'SDM'],
            ['id'=>'PSDM','name'=>'Pengembangan Sumber Daya Manusia','dep'=>'SDM'],
            ['id'=>'MiBa','name'=>'Minat dan Bakat','dep'=>'KesMa'],
            ['id'=>'RTH','name'=>'Rumah Tangga Himpunan','dep'=>'KesMa'],
            ['id'=>'Akademik','name'=>'Akademik','dep'=>'RisTek'],
            ['id'=>'PemTek','name'=>'Pengembangan Teknolgi','dep'=>'RisTek'],
        ];

        foreach($departement as $d){
            DB::table('departements')->insert([
                'id'=>$d['id'],
                'name'=>$d['name'],
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        foreach($division as $d){
            DB::table('divisions')->insert([
                'id'=>$d['id'],
                'name'=>$d['name'],
                'departement_code'=>$d['dep'],
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

    }
}
