<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        $newUsers = User::where('type', 'member')->orderBy('created_at', 'desc')->take(3)->get();

        $activeVouchers = Voucher::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        return view("admin.dashboard",compact("newUsers","activeVouchers"));
    }
}
