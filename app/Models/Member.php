<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrp',
        'name',
        'email',
        'phone',
        'role',
        'division_code',
        'departement_code',
    ];

    protected $primaryKey = 'nrp';

    public $incrementing = false;

    public function attendances() {
        return $this->hasMany(Attendance::class, 'nrp', 'nrp');
    }

    public function activities() {
        return $this->hasMany(Activity::class, 'nrp', 'nrp');
    }

    public function division() {
        return $this->belongsTo(Division::class, 'division_code', 'id');
    }

    public function departement() {
        return $this->belongsTo(Departement::class, 'departement_code', 'id');
    }

    public function participants(){
        return $this->hasMany(Participant::class,'nrp','nrp');
    }

    public function volunteers(){
        return $this->hasMany(Volunteer::class,'nrp','nrp');
    }

    public function calculatePerformance()
    {
        // Hitung jumlah event yang diikuti member
        $totalParticipation = $this->participants()->count();

        // Ambil semua kegiatan yang diikuti member
        $activityIds = $this->participants()->pluck('act_id');

        // Hitung total kehadiran
        $totalAttendance = $this->attendances()->where('status', 'hadir')->count();

        // Hitung jumlah pertemuan wajib dari semua kegiatan yang diikuti
        $totalMustAttend = Activity::whereIn('id', $activityIds)->sum('must_attend');

        // Hitung total event dalam sistem
        $totalEvents = Activity::count();

        return [
            'total_participation' => $totalParticipation,
            'total_event' => $totalEvents,
            'total_attendance' => $totalAttendance,
            'total_must_attend' => $totalMustAttend,
        ];
    }

    public function calculateMonthlyPerformance($month, $year)
    {
        // Hitung jumlah event yang diikuti member dalam bulan tertentu
        $totalParticipation = $this->participants()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        $totalVolunteer = $this->volunteers()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Ambil semua kegiatan yang diikuti member dalam bulan tertentu
        $participantActivityIds = $this->participants()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->pluck('act_id');

        // Ambil semua kegiatan yang diikuti volunteer dalam bulan tertentu
        $volunteerActivityIds = $this->volunteers() // Pastikan Anda memiliki relasi 'volunteers' di model Anda
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->pluck('act_id');

        // Gabungkan kedua collection ID dan hapus duplikat
        $allActivityIds = $participantActivityIds->merge($volunteerActivityIds)->unique();

        // Ambil nama kegiatan berdasarkan ID yang sudah digabungkan
        $activityName = Activity::whereIn('id', $allActivityIds)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

        // Hitung total kehadiran dalam bulan tertentu
        $totalAttendance = $this->attendances()
            ->where('status', 'hadir')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Hitung jumlah pertemuan wajib dari semua kegiatan yang diikuti dalam bulan tertentu
        $totalMustAttend = Activity::whereIn('id', $participantActivityIds)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('must_attend');

        // Hitung total event dalam sistem untuk bulan tersebut
        $totalEvents = Activity::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Pastikan tidak ada pembagian dengan nol
        $attendancePercentage = ($totalMustAttend > 0 || ($totalMustAttend == 0 && $totalVolunteer > 0)) ? ($totalAttendance / ($totalMustAttend > 0 ? $totalMustAttend : 1)) * 100 : 0;
        $participationPercentage = $totalEvents > 0 ? ($totalParticipation / $totalEvents) * 100 : 0;

        // Gabungkan performa
        $performanceScore = ($attendancePercentage + $participationPercentage) / 2;

        // Beri rekomendasi berdasarkan skor
        $recommendation = $this->getRecommendation($attendancePercentage);

        return [
            'activityName' => $activityName,
            'participation' => $totalParticipation,
            'volunteer' => $totalVolunteer,
            'attendance' => $totalAttendance,
            'total_event' => $totalEvents,
            'must_attend' => $totalMustAttend,
            'month' => $month,
            'year' => $year,
            'attendance_percentage' => round($attendancePercentage, 2),
            'participation_percentage' => round($participationPercentage, 2),
            'performance_score' => round($performanceScore, 2),
            'recommendation' => $recommendation,
        ];
    }

    private function getRecommendation($performanceScore)
    {
        if ($performanceScore >= 80) {
            return 'Keren sekali, tetap konsisten ya! 🔥🔥🔥';
        } elseif ($performanceScore >= 60) {
            return 'Performa yang bagus, Ayo tingkatkan lagi !!!';
        } else {
            return 'Harus lebih rajin datang ya!';
        }
    }

}
