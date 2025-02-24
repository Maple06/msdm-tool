<?php

namespace App\Imports;

use App\Models\Member; // Pastikan model sudah diimport
use Maatwebsite\Excel\Concerns\ToModel;

class MemberImport implements ToModel{
    /**
     * Transform each row of the sheet into a Member model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $member = Member::where('nrp',$row[0])->first();

        if ($member) {
            return null; // Jika tidak ditemukan, lewati baris ini
        }
        
        return new Member([
            'nrp' => $row['0'],
            'name' => $row['1'],
            'email' => $row['2'],
            'phone' => $row['3'],
            'role' => $row['4'],
            'division_code' => $row['5'],
            'departement_code' => $row['6'],
        ]);
    }
}
