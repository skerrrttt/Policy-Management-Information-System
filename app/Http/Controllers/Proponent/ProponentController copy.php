<?php

namespace App\Http\Controllers\Proponent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposals;
use App\Models\LocalCouncilMeeting;

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
            // Validate the incoming request
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string',
                'subtype' => 'nullable|string',
                'file' => 'required|file|mimes:pdf|max:2048',
            ]);
    
            $user = Auth::user();
            

            $originalFileName = $request->file('file')->getClientOriginalName();
            $uniqueFileName = time() . '-' . $originalFileName; // Add a timestamp to ensure uniqueness
            
            // Store the file with the new name
            $filePath = $request->file('file')->storeAs('proposals', $uniqueFileName, 'public');
    
            // Create the proposal
            $proposal = Proposals::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'type' => $validated['type'],
                'subtype' => $validated['subtype'] ?? null,
 
            ]);
    
            // Create the initial version of the proposal
            ProposalVersion::create([
                'proposals_id' => $proposal->id,
                'file_paths' => $filePath,
                'version' => '1.0',
            ]);
    
            return response()->json(['message' => 'Proposal submitted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while submitting the proposal: ' . $e->getMessage()], 500);
        }
    }
    
}
