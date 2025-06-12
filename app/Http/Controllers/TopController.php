<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopController extends Controller
{
        public function index()
    {
        // 掲示板の新着5件
        $boards = DB::table('KEIJIBAN')
            ->where('DEL_FLG', 0)
            ->orderBy('KEISAI_START_DATE', 'desc')
            ->limit(9)
            ->get();

        // FAQの新着5件
        $faqs = DB::table('FAQ')
            ->where('DEL_FLG', 0)
            ->orderBy('CREATE_DT', 'desc')
            ->limit(5)
            ->get();

        return view('top', compact('boards', 'faqs'));
    }
}