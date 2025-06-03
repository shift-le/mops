<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use App\Models\Ryoiki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/mock-login/user001');
        }

        $ryoikis = Ryoiki::with(['hinmeis.tools'])->orderBy('DISP_ORDER')->get();
        return view('categorys.index', compact('ryoikis'));
    }
}
