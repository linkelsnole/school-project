<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TestResult::with('student');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('fio', 'like', "%{$search}%");
            });
        }

        if ($request->has('test_code') && $request->test_code) {
            $query->where('test_code', $request->test_code);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(20);

        $testCodes = TestResult::distinct()->pluck('test_code', 'test_title');

        return view('admin.results.index', compact('results', 'testCodes'));
    }

    public function show($id)
    {
        $result = TestResult::with('student')->findOrFail($id);
        return view('admin.results.show', compact('result'));
    }

    public function export(Request $request)
    {
        $query = TestResult::with('student');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('fio', 'like', "%{$search}%");
            });
        }

        if ($request->has('test_code') && $request->test_code) {
            $query->where('test_code', $request->test_code);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $results = $query->orderBy('created_at', 'desc')->get();

        $filename = 'results_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($results) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'ФИО',
                'Дата рождения',
                'Тест',
                'Дата прохождения',
                'Статус'
            ]);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->id,
                    $result->student->fio,
                    $result->student->date_of_birth->format('d.m.Y'),
                    $result->test_title,
                    $result->created_at->format('d.m.Y H:i'),
                    'Выполнено'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        try {
            $result = TestResult::findOrFail($id);
            $studentId = $result->student_id;


            $result->delete();


            $otherResults = TestResult::where('student_id', $studentId)->count();


            if ($otherResults === 0) {
                Student::find($studentId)?->delete();
            }

            return response()->json(['success' => true, 'message' => 'Результат успешно удален']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ошибка при удалении: ' . $e->getMessage()], 500);
        }
    }
}
