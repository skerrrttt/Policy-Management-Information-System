<?php

namespace App\Http\Controllers\AdminCouncil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCouncilController extends Controller
{
    public function index(){
        return view('Proponents.submitproposal');
    }
}
