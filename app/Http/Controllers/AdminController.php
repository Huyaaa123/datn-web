<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        $newUsers = User::where('type', 'member')->orderBy('created_at', 'desc')->take(3)->get();
        return view("admin.dashboard",compact("newUsers"));
    }
}
