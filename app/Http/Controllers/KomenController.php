<?php

namespace App\Http\Controllers;



class KomenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Artisan::call('cache:clear');

    dd('cache clear successfully');
    }

   
}
