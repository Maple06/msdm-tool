<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Activity;
use App\Models\Participant;
use App\Models\Volunteer;

class KehadiranImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $activity = Activity::where('name', $row[0])->first();

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $participant = Participant::where('nrp', $row[1])
            ->where('act_id', $activity->id)
            ->first();

        $volunteer = Volunteer::where('nrp', $row[1])
            ->where('act_id', $activity->id)
            ->first();

        if (!$participant) { // Buat volunteer baru jika tidak ada
            $volunteer = Volunteer::updateOrCreate([
                'act_id' => $activity->id,
                'nrp' => $row[1],
            ]);
        }

        return new Attendance([
            'nrp' => $row[1],
            'participant_of' => $participant ? $participant->id : null,
            'volunteer_of' => $volunteer ? $volunteer->id : null,
            'status' => strtolower($row[2]) === 'hadir' ? 'hadir' : 'tidak hadir',
            'act_id' => $activity->id,
        ]);
    }
}
