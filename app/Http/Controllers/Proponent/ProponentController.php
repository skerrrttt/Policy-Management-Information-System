<?php

namespace App\Http\Controllers\Proponent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposals;
use App\Models\LocalCouncilMeeting;
use App\Models\LocalMeetingAgenda;
use Illuminate\Support\Facades\DB;

use App\Models\ProposalVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProponentController extends Controller
{
    public function index()
    {
        // Check for an active schedule
        $currentDate = now();
        $activeSchedule = LocalCouncilMeeting::where('submission_start', '<=', $currentDate)
            ->where('submission_end', '>=', $currentDate)
            ->first();

            $user = Auth::user();

    
        return view('Proponents.submitproposal', [
            'activeSchedule' => $activeSchedule,
            'userCampus' => $user->campus->name ?? 'N/A', // Assuming a relationship 'campus' exists
        ]);
    }

    public function submitProposal(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string',
                'subtype' => 'nullable|string',
                'file' => 'required|file|mimes:pdf,docx',
            ]);

            // Get the logged-in user's ID
            $userId = Auth::id();

            // Check for active meeting
            $activeMeeting = LocalCouncilMeeting::where('submission_start', '<=', now())
                ->where('submission_end', '>=', now())
                ->first();

            if (!$activeMeeting) {
                return response()->json(['error' => 'No active meeting available for proposal submission.'], 400);
            }

            // Store the proposal
            $proposal = Proposals::create([
                'user_id' => $userId,
                'title' => $validatedData['title'],
                'type' => $validatedData['type'],
                'subtype' => $validatedData['subtype'] ?? null,
            ]);

            // Save the file
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->storeAs('proposals', $originalName, 'public');
            DB::table('proposal_versions')->insert([
                'proposals_id' => $proposal->id,
                'file_paths' => $filePath,
                'version' => '1.0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add proposal to local meeting agenda
            LocalMeetingAgenda::create([
                'proposals_id' => $proposal->id,
                'local_council_meeting_id' => $activeMeeting->id,
                'proposals_status_id' => 5, // Assuming status ID for "submitted"
                'requested_action_id' => 1, // Assuming a default action ID
            ]);

            return response()->json(['message' => 'Proposal submitted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit proposal: ' . $e->getMessage()], 500);
        }
    }

    public function agendaIndex(){
        $quarters = [
            1 => '1st Quarter',
            2 => '2nd Quarter',
            3 => '3rd Quarter',
            4 => '4th Quarter',
        ];
    
        // Fetch all meetings grouped by quarter
        $meetings = LocalCouncilMeeting::select('quarter', 'council_type_id')
            ->get()
            ->groupBy('quarter');
    
       
        // Prepare quarter statuses
        $quarterStatuses = [];
        foreach ($quarters as $key => $quarter) {
            $quarterStatuses[$key] = [
                'academic' => false,
                'administrative' => false,
                'joint_meeting' => false,
            ];
    
            if ($meetings->has($key)) {
                $currentQuarterMeetings = $meetings[$key];
    
                // Check for Joint Meeting (council_type_id = 3)
                if ($currentQuarterMeetings->contains('council_type_id', 3)) {
                    $quarterStatuses[$key]['academic'] = true;
                    $quarterStatuses[$key]['administrative'] = true;
                    $quarterStatuses[$key]['joint_meeting'] = true;
                } else {
                    // Check for Academic Meeting (council_type_id = 2)
                    $quarterStatuses[$key]['academic'] = $currentQuarterMeetings->contains('council_type_id', 1);
    
                    // Check for Administrative Meeting (council_type_id = 4)
                    $quarterStatuses[$key]['administrative'] = $currentQuarterMeetings->contains('council_type_id', 2);
                }
            }
        }
    
        $meetings = LocalCouncilMeeting::get()->toArray();
    
        
    
        $testQuarters = [1, 2, 3, 4];
        $existingQuarters = array_column($meetings, 'quarter'); // Extract existing quarters
        
        foreach ($testQuarters as $test) {
            if (!in_array($test, $existingQuarters)) { // Check if quarter doesn't exist in $meetings
                $meetings[] = ['quarter' => $test]; // Add a new entry for the missing quarter
            }
        }
    
        usort($meetings, function ($a, $b) {
            return $a['quarter'] <=> $b['quarter']; // Compare by 'quarter' in ascending order
        });
    
        $allMeetingYears = LocalCouncilMeeting::select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->pluck('year');
        return view('Proponents.Agenda', compact('meetings', 'allMeetingYears'));
    }
    
}
