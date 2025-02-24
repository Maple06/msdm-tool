<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Member; // Import model Member
use App\Models\Activity; // Import model Activity
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ParticipantController extends Controller
{
    public function index()
    {
        try {
            $participants = Participant::with(['member', 'activity'])->get(); // Eager load relations
            return view('admin.participant.index', compact('participants'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function create()
    {
        $members = Member::all(); // Ambil data member untuk dropdown
        $activities = Activity::all(); // Ambil data activity untuk dropdown
        return view('admin.participant.create', compact('members', 'activities'));
    }

    public function store(Request $request, $activityId)
    {
        try {
            // Validasi input
            $request->validate([
                'participants' => 'required|array',
                'participants.*' => 'exists:members,nrp',
            ]);

            // Ambil daftar NRP yang dipilih
            $selectedParticipants = $request->participants;

            // Loop untuk menyimpan setiap participant
            foreach ($selectedParticipants as $nrp) {
                // Cek apakah sudah terdaftar di activity ini
                $existingParticipant = Participant::where('act_id', $activityId)
                    ->where('nrp', $nrp)
                    ->first();

                if (!$existingParticipant) {
                    Participant::create([
                        'act_id' => $activityId,
                        'nrp' => $nrp,
                    ]);
                }
            }

            return back()->with('success', 'Participants berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    public function edit(Participant $participant)
    {
        $members = Member::all();
        $activities = Activity::all();
        return view('admin.participant.edit', compact('participant', 'members', 'activities'));
    }

    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'nrp' => 'required|exists:members,nrp',
            'act_id' => 'required|exists:activities,id',
        ]);

        try {
            $participant->update($request->all());
            return redirect()->route('participant.index')->with('success', 'Participant berhasil diupdate.');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function destroy(Participant $participant)
    {
        try {
            $participant->delete();
            return redirect()->route('participant.index')->with('success', 'Participant berhasil dihapus.');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function import(Request $request, $id)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getPathname()));

            foreach ($csvData as $row) {
                $nrp = $row[0]; // Anggap kolom pertama di CSV adalah NRP

                $member = Member::where('nrp', $nrp)->first();
                if ($member) {
                    Participant::updateOrCreate([
                        'nrp' => $nrp,
                        'act_id' => $id,
                    ]);
                }
            }

            return back()->with('success', 'Participants berhasil diimport.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengimport CSV.');
        }
    }

}
