<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;

class TestPageController extends Controller
{
    public function showDynamicTest($code)
    {
        $test = Test::where('code', $code)
            ->where('status', 'active')
            ->with(['categories.questions.options', 'questions.options'])
            ->firstOrFail();

        return view('test-page.dynamic', compact('test'));
    }
}
