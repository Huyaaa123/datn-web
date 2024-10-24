<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

        $users = Auth::user();
        $categories = Category::all();
        $orders = $users->orders ?? collect();
        return view("user",compact('users', 'categories','orders'));
    }
}
