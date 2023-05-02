<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function aboutus()
    {
        return inertia('AboutUs');
    }

    public function howitworks()
    {
        return inertia('HowItWorks');
    }

    public function privacypolicy()
    {
        return inertia('PrivacyPolicy');
    }

    public function termofuse()
    {
        return inertia('TermOfUse');
    }
}
