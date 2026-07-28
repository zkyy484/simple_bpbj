<?php

namespace App\Http\Controllers\SuperAdmin;

use illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $admins = auth()->guard('web')->user();

     return view('super-admin.dashboard', compact('admins'));
    }

    
}
