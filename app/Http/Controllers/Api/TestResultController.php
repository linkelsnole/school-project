<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestResultController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'test_code' => 'required|string|max:100',
            'test_title' => 'required|string|max:255',
            'answers' => 'required|array',
            'score' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $student = Student::firstOrCreate(
                [
                    'fio' => $request->fio,
                    'date_of_birth' => $request->date_of_birth
                ]
            );

            $existingResult = TestResult::where('student_id', $student->id)
                ->where('test_code', $request->test_code)
                ->first();

            if ($existingResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Тест уже пройден'
                ], 409);
            }

            $testResult = TestResult::create([
                'student_id' => $student->id,
                'test_code' => $request->test_code,
                'test_title' => $request->test_title,
                'answers' => $request->answers,
                'score' => $request->score ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Результат успешно сохранен',
                'result_id' => $testResult->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении результата'
            ], 500);
        }
    }
}
