<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ToolType1;
use App\Models\ToolType2;

class HeaderController extends Controller
{
    public static function getToolTypeOptions()
    {
        $type1s = ToolType1::orderBy('DISP_ORDER')->get();
        $type2s = ToolType2::orderBy('DISP_ORDER')->get();

        return $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
            $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
            return [
                'label' => $label,
                'children' => $items,
            ];
        });
    }
}
