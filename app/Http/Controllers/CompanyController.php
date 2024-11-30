<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // -----
    // index アクション
    // -----
    public function index() {
        $company = Company::first();
        // dd($company);
        return view('company.index', compact('company'));
     }
}
