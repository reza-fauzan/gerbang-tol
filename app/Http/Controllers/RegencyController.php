<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;

class RegencyController extends Controller
{
    public function index () {
        $regencies = Regency::all();
        return view('tematik', compact('regencies'), ['title' => 'Peta Tematik']);
    }
}
