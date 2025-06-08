<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    /**
     * Display a listing of user login activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $activities = LoginActivity::with('user')
            ->latest('login_at')
            ->paginate(15);

        return view('admin.login-activities.index', compact('activities'));
    }
}
