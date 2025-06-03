<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTestProgress;
use App\Models\Test;

class NotebookController extends Controller
{
    public function index(Request $request)
    {

        $tests = Test::active()
            ->with(['categories', 'questions'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->code,
                    'title' => $test->title,
                    'category' => 'Психологические тесты',
                    'description' => $test->description ?: 'Психологический тест для профессионального развития.',
                    'url' => '/tests/' . $test->code,
                    'type' => 'dynamic',
                    'questions_count' => $test->questions->count(),
                    'categories_count' => $test->categories->count()
                ];
            })
            ->toArray();

        $progress = [];
        $user = Auth::user();

        if ($user) {
            $userProgress = UserTestProgress::where('user_id', $user->id)->get()->keyBy('test_id');

            foreach ($tests as $test) {
                $testProgress = $userProgress->get($test['id']);
                $progress[$test['id']] = [
                    'completed' => $testProgress ? $testProgress->isCompleted() : false,
                    'score' => $testProgress ? $testProgress->score : null,
                    'status' => $testProgress ? $testProgress->status : 'pending'
                ];
            }
        }


        $selectedTest = session('selected_test');

        return view('notebook.index', compact('tests', 'progress', 'selectedTest'));
    }
}
