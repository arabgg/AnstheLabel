<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $profile = UserModel::all();
    
        return view('admin.index', [
            'profile' => $profile
        ]);
    }
}