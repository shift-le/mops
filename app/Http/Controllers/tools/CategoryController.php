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
    $hinmeis = Hinmei::with(['tools', 'ryoikis'])->get();

    return view('categorys.index', compact('hinmeis'));
}

}
