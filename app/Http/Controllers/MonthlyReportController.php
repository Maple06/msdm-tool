<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Dompdf\Options;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $departments = $this->getDepartmentsWithRelations();

        $reportData = $this->buildReportData($departments);

        return view('admin.report.monthly', compact('reportData', 'selectedMonth'));
    }

    public function generatePDF(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $departments = $this->getDepartmentsWithRelations();

        $reportData = $this->buildReportData($departments);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin.report.monthly', compact('reportData', 'selectedMonth'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan.pdf');
    }

    private function getDepartmentsWithRelations()
    {
        return Departement::with([
            'divisions.members.participants.activity',
            'divisions.members.attendances'
        ])->get();
    }

    private function buildReportData($departments)
    {
        $reportData = [];

        foreach ($departments as $department) {
            $departmentData = [
                'id' => $department->id,
                'name' => $department->name,
                'divisions' => [],
            ];

            foreach ($department->divisions as $division) {
                $members = $division->members;
                $averagePerformance = $members->avg('performance_score') ?? 0;

                if (!$members->first() || !isset($members->first()->performance_score)) {
                    $totalParticipation = $members->sum(fn($member) => $member->participants->count());
                    $totalMustAttend = $members->sum(fn($member) =>
                        $member->participants->sum(fn($participant) =>
                            $participant->activity->must_attend ?? 0
                        )
                    );
                    $totalAttendance = $members->sum(fn($member) =>
                        $member->attendances->where('status', 'hadir')->count()
                    );

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
        return $reportData;
    }
}
