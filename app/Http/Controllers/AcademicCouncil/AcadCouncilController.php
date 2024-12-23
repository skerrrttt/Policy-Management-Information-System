<?php

namespace App\Http\Controllers\AcademicCouncil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Proposals;

class AcadCouncilController extends Controller
{

    public function index(){
        return view('Proponents.submitproposal');
    }

    public function agenda(){
        return view('Proponents.Agenda');
    }
    
    public function submitProposal(Request $request)
    {
        try {
            $user = Auth::user();

            // Validation
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'submitted_as' => !$user->academicCouncilMembership ? 'nullable|string|max:255' : 'sometimes|nullable',
                'type' => $user->academicCouncilMembership ? 'in:Academic Matter' : 'required|string|max:255',
                'file' => 'required|mimes:pdf|max:2048', // Maximum 2MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error!',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // File Upload
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('proposals', $fileName, 'public');

            // Create Proposal Record
            $proposal = Proposals::create([
                'title' => $request->title,
                'submitted_as' => $user->academicCouncilMembership ? 'Academic Council' : $request->submitted_as,
                'type' => $user->academicCouncilMembership ? 'Academic Matter' : $request->type,
                'file_path' => $filePath,
                'proponent_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Proposal submitted successfully!',
                'proposal' => $proposal,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to submit proposal!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
