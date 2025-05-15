<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use App\Models\Ryoiki;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $ryoikis = Ryoiki::with(['hinmeis.tools'])->orderBy('DISP_ORDER')->get();
        return view('categorys.index', compact('ryoikis'));
    }
}
