<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }


    public function about()
    {
        return view('frontend.about');
    }
    public function services()
    {
        return view('frontend.services');
    }
    
    public function departments()
    {
        return view('frontend.departments');
    }
    
    public function department_single()
    {
        return view('frontend.department_single');
    }
    
    public function doctors()
    {
        return view('frontend.doctors');
    }
    
    public function doctor_single()
    {
        return view('frontend.doctor_single');
    }
    
    public function appointment()
    {
        return view('frontend.appointment');
    }
    
    public function blog_with_sidebar()
    {
        return view('frontend.blog_with_sidebar');
    }
    
    public function single_blog()
    {
        return view('frontend.single_blog');
    }
    
    public function contact()
    {
        return view('frontend.contact');
    }

}


