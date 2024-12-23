<?php

namespace App\Http\Controllers\LocalSec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LocalCouncilMeeting;
use App\Models\Proposals;
use App\Models\LocalMeetingAgenda;

use App\Models\CouncilType;
use App\Models\MeetingModality;
use App\Models\MeetingVenue;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use App\Notifications\MeetingNotification; 
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;






class LocalSecretaryController extends Controller
{
    /**
     * Display the meeting view for the Local Secretary.
     *
     * @return \Illuminate\View\View
     */

     public function index()
{
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

    $councilTypes = CouncilType::all();
    $modalities = MeetingModality::all();
    $venues = MeetingVenue::all();

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

//    dd($meetings);

    
    // dd($meetings);
    // dd($quarterStatuses);
    return view('LocalSec.meeting', compact('quarters', 'quarterStatuses', 'councilTypes', 'modalities', 'venues', 'meetings', 'allMeetingYears'));
}


     
    public function store(Request $request)
{
    try {
        // Validate the input data
        $validatedData = $request->validate([
            'quarter' => 'required|integer|in:1,2,3,4',
            'description' => 'required|string',
            'venue_id' => 'nullable|exists:meeting_venue,id',
            'meeting_date' => 'nullable|date',
            'modality_id' => 'nullable|exists:meeting_modality,id',
            'council_type_id' => 'required|exists:council_type,id',
            'submission_start' => 'required|date|before_or_equal:submission_end',
            'submission_end' => 'required|date|after_or_equal:submission_start',
        ]);

        // Store the new meeting in the database
        $meeting = LocalCouncilMeeting::create([
            'quarter' => $validatedData['quarter'],
            'meeting_description' => $validatedData['description'],
            'meeting_venue_id' => $validatedData['venue_id'] ?? null,
            'meeting_date' => $validatedData['meeting_date'],
            'meeting_modality_id' => $validatedData['modality_id'],
            'council_type_id' => $validatedData['council_type_id'],
            'submission_start' => $validatedData['submission_start'],
            'submission_end' => $validatedData['submission_end'],
        ]);

         // Get the campus ID of the logged-in local secretary
         $localSecretaryCampusId = auth()->user()->campus_id;

        
        // Fetch users in the same campus for both councils
        $academicCouncilUsers = DB::table('academic_council_membership')
            ->join('users', 'academic_council_membership.users_id', '=', 'users.id')
            ->where('users.campus_id', $localSecretaryCampusId) // Filter by campus
            ->get();


            $adminCouncilUsers = DB::table('admin_council_membership')
            ->join('users', 'admin_council_membership.users_id', '=', 'users.id')
            ->where('users.campus_id', $localSecretaryCampusId) // Filter by campus
            ->get();

     // Merge users into a single collection
     $allUsers = $academicCouncilUsers->merge($adminCouncilUsers);

       // Notify each user
       foreach ($allUsers as $user) {
        $userInstance = User::find($user->users_id);
        if ($userInstance) { // Ensure user exists
            $userInstance->notify(new MeetingNotification([
                'submission_start' => $validatedData['submission_start'],
                'submission_end' => $validatedData['submission_end'],
            ]));
        }
    }

        
        // Respond with a success message
        return response()->json(['message' => 'Meeting created successfully!'], 200);
    } catch (\Exception $e) {
        // Handle errors and return a JSON response
        return response()->json(['error' => 'Failed to create meeting. ' . $e->getMessage()], 500);
    }
}



public function storeVenue(Request $request)
{
    $validated = $request->validate([
        'venue_description' => 'required|string|max:45',
    ]);

    $venue = MeetingVenue::create([
        'venue_description' => $validated['venue_description'],
    ]);

    return response()->json([
        'message' => 'Venue added successfully!',
        'data' => $venue,
    ], 200);
}

public function storeModality(Request $request)
{
    $validated = $request->validate([
        'modality_description' => 'required|string|max:45',
    ]);

    $modality = MeetingModality::create([
        'modality_description' => $validated['modality_description'],
    ]);

    return response()->json([
        'message' => 'Modality added successfully!',
        'data' => $modality,
    ], 200);
}




public function viewMeetingProposals($encryptedId)
{
    try {
        // Decrypt the ID
        $meetingId = decrypt($encryptedId);

        // Get the authenticated user's campus_id
        $userCampusId = auth()->user()->campus_id; // Fetch the campus ID of the logged-in user.
        // dd(auth()->user()->campus_id);


        // Fetch the meeting details and proposals as before...
        $meeting = LocalCouncilMeeting::with(['venue', 'modality', 'councilType'])->findOrFail($meetingId);
        $proposals = DB::table('local_meeting_agenda')
            ->join('proposals', 'local_meeting_agenda.proposals_id', '=', 'proposals.id')
            ->join('users', 'proposals.user_id', '=', 'users.id') // Join with the users table
            ->join('campus', 'users.campus_id', '=', 'campus.campus_id') // Join with the campus table
            ->leftJoin('academic_council_membership', 'users.id', '=', 'academic_council_membership.users_id') // Check Academic Council Membership
            ->leftJoin('admin_council_membership', 'users.id', '=', 'admin_council_membership.users_id') // Check Admin Council Membership
            ->join('proposals_status', 'local_meeting_agenda.proposals_status_id', '=', 'proposals_status.id') // Join with proposals_status
            ->where('local_meeting_agenda.local_council_meeting_id', $meetingId)
            ->where('users.campus_id', $userCampusId) // Use campus_id from users table
            ->where('proposals_status_id', 5)
            ->select(
                'proposals.id as proposal_id',
                'proposals.title',
                'proposals.type', 
                'proposals.subtype', 
                'proposals.created_at as submitted_date',
                'users.first_name',
                'users.last_name',
                'campus.name as campus_name',
                'users.image as proposal_file',
                'proposals_status.status_description as status', // Fetch the status description

                DB::raw("CASE 
                    WHEN academic_council_membership.users_id IS NOT NULL THEN 'Academic Council Member'
                    WHEN admin_council_membership.users_id IS NOT NULL THEN 'Admin Council Member'
                    ELSE 'N/A'
                END as membership")
            )
            ->get();


        // Fetch proposal versions
        $proposalVersions = DB::table('proposal_versions')
        ->join('proposals', 'proposal_versions.proposals_id', '=', 'proposals.id')
        ->select(
            'proposal_versions.id',
            'proposal_versions.proposals_id',
            'proposal_versions.file_paths',
            'proposal_versions.version'
            

            
        )
        ->get();


        return view('LocalSec.meetingProposals', compact('meeting', 'proposals', 'proposalVersions'));
    } catch (DecryptException $e) {
        return redirect()->back()->with('error', 'Invalid meeting ID.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error fetching proposals: ' . $e->getMessage());
    }
}


public function listAgenda(Request $request){
    
}


public function postAgenda(Request $request){
    $selectedProposals = $request->selectedProposals;

    foreach($selectedProposals as $selectedProposal){
       LocalMeetingAgenda::where('proposals_id', $selectedProposal)->update([
        'proposals_status_id' => 5
       ]);
    }

    return response()->json([
        'status_code' => 0
    ]);
}




public function update(Request $request, $id)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'quarter' => 'required|integer|min:1|max:4',
            'description' => 'required|string|max:255',
            'venue_id' => 'required|exists:meeting_venues,id',
            'meeting_date' => 'nullable|date',
            'modality_id' => 'required|exists:meeting_modalities,id',
            'council_type_id' => 'required|exists:council_types,id',
            'submission_start' => 'required|date',
            'submission_end' => 'required|date',
        ]);

        // Find the meeting by ID and update
        $meeting = LocalCouncilMeeting::findOrFail($id);
        $meeting->update([
            'quarter' => $validatedData['quarter'],
            'meeting_description' => $validatedData['description'],
            'venue_id' => $validatedData['venue_id'],
            'meeting_date' => $validatedData['meeting_date'],
            'modality_id' => $validatedData['modality_id'],
            'council_type_id' => $validatedData['council_type_id'],
            'submission_start' => $validatedData['submission_start'],
            'submission_end' => $validatedData['submission_end'],
        ]);

        return response()->json(['message' => 'Meeting updated successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update meeting.'], 500);
    }

    
}

public function edit($id)
{
    $meeting = LocalCouncilMeeting::with(['venue', 'modality', 'councilType'])->findOrFail($id);

    return response()->json([
        'meeting' => $meeting,
        'quarter_text' => $meeting->quarter . ' Quarter', // Convert quarter to human-readable text if needed
    ]);
}




public function destroy($id)
{
    try {
        // First, delete dependent records in local_meeting_agenda
        DB::table('local_meeting_agenda')->where('local_council_meeting_id', $id)->delete();

        // Now delete the meeting
        $meeting = LocalCouncilMeeting::findOrFail($id);
        $meeting->delete();

        return response()->json(['message' => 'Meeting and its agenda deleted successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong while deleting the meeting.'], 500);
    }
}

    public function selectYear(Request $request){
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
    
        $councilTypes = CouncilType::all();
        $modalities = MeetingModality::all();
        $venues = MeetingVenue::all();
    
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
    
        $meetings = LocalCouncilMeeting::whereYear('created_at', $request->year)->get()->toArray();
    
        
    
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
    
    //    dd($meetings);
    
        
        // dd($meetings);
        // dd($quarterStatuses);
        
        return view('LocalSec.select-year', compact('quarters', 'quarterStatuses', 'councilTypes', 'modalities', 'venues', 'meetings', 'allMeetingYears'));
  
    }


   
}
