<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
 
class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Detection::count(),
            'b3'       => Detection::where('category', 'B3')->count(),
            'non_b3'   => Detection::where('category', 'Non-B3')->count(),
            'accuracy' => Detection::avg('confidence') ? round(Detection::avg('confidence') * 100, 1) : null,
        ];
 
        return view('home', compact('stats'));
    }
}
