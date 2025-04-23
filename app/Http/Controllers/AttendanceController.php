<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KehadiranImport;
use App\Models\Activity;
use App\Models\Member;
use App\Models\Participant;
use App\Models\Volunteer;

class AttendanceController extends Controller
{
    public function index(){
        try {
            $attendances = Attendance::with(['member', 'participant.activity', 'volunteer.activity'])->get();
            return view('admin.attendance.index', compact('attendances'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function create(Request $request){
        try {
            $activities = Activity::all();
            $members = Member::all();
            return view('admin.attendance.create', compact('members','activities'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function store(Request $request){
        try {
            $request->validate([
                'nrp' => 'required|exists:members,nrp',
                'act_id' => 'required|exists:activities,id', // Pastikan validasi act_id ke tabel activities
                'status' => 'required|in:hadir,tidak hadir',
            ]);

            $participant = Participant::where('nrp', $request->nrp)
                ->where('act_id', $request->act_id)
                ->value('id');

            if (!$participant) {
                $volunteer=Volunteer::updateOrCreate([
                    'act_id' =>  $request->act_id,
                    'nrp' =>  $request->nrp,
                ]);
                $request['participant_of'] =null;
                $request['volunteer_of'] = $volunteer['id'];
                Attendance::create($request->all());
                return redirect()->route('attendance.index')->with('success', 'Attendance added successfully');
            }
            $request['volunteer_of'] = null;
            $request['participant_of'] = $participant;

            Attendance::create($request->all());
            return redirect()->route('attendance.index')->with('success', 'Attendance added successfully');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function edit($id){
        try {
            $attendance = Attendance::findOrFail($id);
            $activities = Activity::all();
            $members = Member::all();
            return view('admin.attendance.edit', compact('attendance', 'activities', 'members'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update(Request $request,$id){
        try {
            $request->validate([
                'nrp' => 'required|exists:members,nrp',
                'act_id' => 'required|exists:activities,id',
                'status' => 'required|in:hadir,tidak hadir',
            ]);

            $attendance = Attendance::findOrFail($id);
            $attendance->update($request->all());

            return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function destroy(Request $request){
        try {
            $attendances = Attendance::all();
            return view('admin.attendance.index', compact('attendances'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new KehadiranImport, $request->file('files'));

        return back()->with('success', 'Data absensi berhasil diimport!');
    }
}
