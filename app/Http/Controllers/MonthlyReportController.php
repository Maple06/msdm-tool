<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Member; // Pastikan Anda mengimpor model Member
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Dompdf\Options;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan yang dipilih, default ke bulan ini
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Ambil semua departemen dan divisi
        $departments = Departement::with('divisions.members')->get();

        // Buat array laporan
        $reportData = [];

        foreach ($departments as $department) {
            $departmentData = [
                'id' => $department->id,
                'name' => $department->name,
                'divisions' => [],
            ];

            foreach ($department->divisions as $division) {
                $members = $division->members()->get();


                // Hitung rata-rata skor performa. Pastikan kolom 'performance_score' ada di tabel members
                $averagePerformance = $members->avg('performance_score') ?? 0;

                // Jika kolom performance_score tidak ada, hitung berdasarkan data yang ada
                if (!$members->first() || !isset($members->first()->performance_score)) {
                    $totalParticipation = $members->map(function ($member) {
                        return $member->participants()->count();
                    })->sum();
                    $totalMustAttend = $members->map(function ($member) {
                        return $member->participants->map(function ($participant) {
                            return $participant->activity->must_attend ?? 0;
                        })->sum();
                    })->sum();
                    $totalAttendance = $members->map(function ($member) {
                        return $member->attendances()->where('status', 'hadir')->count();
                    })->sum();

                    $attendancePercentage = $totalMustAttend > 0 ? ($totalAttendance / $totalMustAttend) * 100 : 0;
                    $participationPercentage = $members->count() > 0 ? ($totalParticipation / ($members->count() * 10)) * 100 : 0;
                    $averagePerformance = ($attendancePercentage + $participationPercentage) / 2;
                }

                $departmentData['divisions'][] = [
                    'id' => $division->id,
                    'name' => $division->name,
                    'average_performance' => $averagePerformance,
                    'members' => $members,
                ];
            }

            $reportData[] = $departmentData;
        }

        return view('admin.report.monthly', compact('reportData', 'selectedMonth'));
    }

    public function generatePDF(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $departments = Departement::with('divisions.members')->get();
        $reportData = [];

        foreach ($departments as $department) {
            $departmentData = [
                'id' => $department->id,
                'name' => $department->name,
                'divisions' => [],
            ];

            foreach ($department->divisions as $division) {
                $members = $division->members()->get();


                // Hitung rata-rata skor performa. Pastikan kolom 'performance_score' ada di tabel members
                $averagePerformance = $members->avg('performance_score') ?? 0;

                // Jika kolom performance_score tidak ada, hitung berdasarkan data yang ada
                if (!$members->first() || !isset($members->first()->performance_score)) {
                    $totalParticipation = $members->map(function ($member) {
                        return $member->participants()->count();
                    })->sum();
                    $totalMustAttend = $members->map(function ($member) {
                        return $member->participants->map(function ($participant) {
                            return $participant->activity->must_attend ?? 0;
                        })->sum();
                    })->sum();
                    $totalAttendance = $members->map(function ($member) {
                        return $member->attendances()->where('status', 'hadir')->count();
                    })->sum();

                    $attendancePercentage = $totalMustAttend > 0 ? ($totalAttendance / $totalMustAttend) * 100 : 0;
                    $participationPercentage = $members->count() > 0 ? ($totalParticipation / ($members->count() * 10)) * 100 : 0;
                    $averagePerformance = ($attendancePercentage + $participationPercentage) / 2;
                }

                $departmentData['divisions'][] = [
                    'id' => $division->id,
                    'name' => $division->name,
                    'average_performance' => $averagePerformance,
                    'members' => $members,
                ];
            }

            $reportData[] = $departmentData;
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin.report.monthly', compact('reportData', 'selectedMonth'))->render()); // Load view HTML

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan.pdf'); // Download PDF
    }
}
