<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($id)
    {
        // Placeholder for post detail page
        return view('post-detail', ['id' => $id]);
    }
}