<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function addreview(Request $request){
        $request->except('_token');
        Review::create($request->input());

        return redirect()->back();
    }
}
