<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function FormPage() {
        return view('tamu.form');
    }

    public function Thanks() {
        return view('tamu.thanks');
    }    

    public function Survei() {
        return view('tamu.survei');
    }    

    public function ThankSurvei() {
        return view('tamu.thanks-survei');
    }

}
