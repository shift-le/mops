<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use App\Models\Ryoiki;
use App\Models\Hinmei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

public function index()
{
    $hinmeis = Hinmei::active()
        ->with(['tools', 'ryoikis' => fn($q) => $q->active()->orderBy('DISP_ORDER')])
        ->orderBy('DISP_ORDER')
        ->get();

    return view('categorys.index', compact('hinmeis'));
}


}
