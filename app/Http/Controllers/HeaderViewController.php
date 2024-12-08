<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HeaderViewController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('gioithieu',compact('categories'));
    }
    public function lienhe(){
        $categories = Category::all();
        return view('lienhe',compact('categories'));
    }
    public function huongdan(){
        $categories = Category::all();
        return view('huongdan',compact('categories'));
    }
}
