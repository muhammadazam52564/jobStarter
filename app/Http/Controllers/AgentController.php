<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use Redirect;

class AgentController extends Controller
{
    public function index()
    {
        return view('agent.dashboard');
    }
}
