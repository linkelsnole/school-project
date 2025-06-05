<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\QuestionCategory;
use App\Models\TestQuestion;
use App\Models\TestQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestBuilderController extends Controller
{

    public function index()
    {
        $tests = Test::withCount('questions')->orderBy('created_at', 'desc')->get();
        return view('admin.tests.index', compact('tests'));
    }


    public function show(Test $test)
    {
        $test->load('questions.options');
        return view('admin.tests.show', compact('test'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);


        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;

        while (Test::where('code', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $test = Test::create([
            'code' => $slug,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return redirect()->route('admin.tests.index')->with('success', 'Тест "' . $test->title . '" создан успешно!');
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:tests,code,' . $test->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $test->update($request->only(['code', 'title', 'description', 'status']));

        return redirect()->route('admin.tests.show', $test)->with('success', 'Настройки теста сохранены!');
    }


    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')->with('success', 'Тест удален успешно!');
    }


    public function addCategory(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $maxOrder = $test->categories()->max('order_index') ?? 0;

        $category = QuestionCategory::create([
            'test_id' => $test->id,
            'title' => $request->title,
            'order_index' => $maxOrder + 1
        ]);

        return redirect()->route('admin.tests.show', $test)->with('success', 'Категория добавлена успешно!');
    }


    public function addQuestion(Request $request, Test $test)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,checkbox,text,scale',
            'options' => 'nullable|array',
            'options.*.text' => 'required_with:options|string',
            'options.*.weight' => 'nullable|numeric'
        ]);

        $maxOrder = $test->questions()->max('order_index') ?? 0;

        $question = TestQuestion::create([
            'test_id' => $test->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'order_index' => $maxOrder + 1,
            'is_required' => true
        ]);


        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $index => $optionData) {
                if (!empty($optionData['text'])) {
                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['text'],
                        'option_value' => $optionData['text'],
                        'weight' => $optionData['weight'] ?? 0,
                        'order_index' => $index + 1
                    ]);
                }
            }
        } else {

            if (in_array($request->question_type, ['radio', 'checkbox'])) {
                TestQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'Да',
                    'option_value' => 'yes',
                    'weight' => 1,
                    'order_index' => 1
                ]);

                TestQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'Нет',
                    'option_value' => 'no',
                    'weight' => 0,
                    'order_index' => 2
                ]);
            } elseif ($request->question_type === 'scale') {
                for ($i = 1; $i <= 5; $i++) {
                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => (string)$i,
                        'option_value' => (string)$i,
                        'weight' => $i,
                        'order_index' => $i
                    ]);
                }
            }
        }

        return redirect()->route('admin.tests.show', $test)->with('success', 'Вопрос создан в конструкторе!');
    }


    public function bulkStoreQuestions(Request $request, Test $test)
    {
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:radio,checkbox,text,scale',
            'questions.*.answers' => 'nullable|array',
            'questions.*.answers.*.text' => 'nullable|string',
            'questions.*.answers.*.weight' => 'nullable|numeric'
        ]);

        $maxOrder = $test->questions()->max('order_index') ?? 0;
        $questionsCreated = 0;

        foreach ($request->questions as $questionData) {

            if (empty(trim($questionData['text']))) {
                continue;
            }

            $maxOrder++;

            $question = TestQuestion::create([
                'test_id' => $test->id,
                'question_text' => $questionData['text'],
                'question_type' => $questionData['type'],
                'order_index' => $maxOrder,
                'is_required' => true
            ]);


            if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                foreach ($questionData['answers'] as $index => $answerData) {
                    if (!empty(trim($answerData['text']))) {
                        TestQuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $answerData['text'],
                            'option_value' => $answerData['text'],
                            'weight' => $answerData['weight'] ?? 0,
                            'order_index' => $index + 1
                        ]);
                    }
                }
            } else {

                if (in_array($questionData['type'], ['radio', 'checkbox'])) {
                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => 'Да',
                        'option_value' => 'yes',
                        'weight' => 1,
                        'order_index' => 1
                    ]);

                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => 'Нет',
                        'option_value' => 'no',
                        'weight' => 0,
                        'order_index' => 2
                    ]);
                } elseif ($questionData['type'] === 'scale') {
                    for ($i = 1; $i <= 5; $i++) {
                        TestQuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => (string)$i,
                            'option_value' => (string)$i,
                            'weight' => $i,
                            'order_index' => $i
                        ]);
                    }
                }
            }

            $questionsCreated++;
        }

        return redirect()->route('admin.tests.show', $test)
            ->with('success', "Успешно добавлено вопросов: {$questionsCreated}");
    }

    public function destroyCategory(QuestionCategory $category)
    {
        $test = $category->test;
        $category->delete();
        return redirect()->route('admin.tests.show', $test)->with('success', 'Категория удалена успешно!');
    }


    public function destroyQuestion(TestQuestion $question)
    {
        $test = $question->test;
        $question->delete();
        return redirect()->route('admin.tests.show', $test)->with('success', 'Вопрос удален успешно!');
    }


    public function getQuestionData(TestQuestion $question)
    {
        $question->load('options');

        return response()->json([
            'id' => $question->id,
            'question_text' => $question->question_text,
            'question_type' => $question->question_type,
            'options' => $question->options->map(function($option) {
                return [
                    'id' => $option->id,
                    'option_text' => $option->option_text,
                    'weight' => $option->weight,
                    'order_index' => $option->order_index
                ];
            })
        ]);
    }


    public function updateQuestionText(Request $request, TestQuestion $question)
    {
        $request->validate([
            'question_text' => 'required|string'
        ]);

        $question->update([
            'question_text' => $request->question_text
        ]);

        return response()->json(['success' => true]);
    }


    public function updateQuestion(Request $request, TestQuestion $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,checkbox,text,scale',
            'options' => 'nullable|array',
            'options.*.text' => 'required_with:options|string',
            'options.*.weight' => 'nullable|numeric'
        ]);


        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type
        ]);


        $question->options()->delete();


        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $index => $optionData) {
                if (!empty($optionData['text'])) {
                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['text'],
                        'option_value' => $optionData['text'],
                        'weight' => $optionData['weight'] ?? 0,
                        'order_index' => $index + 1
                    ]);
                }
            }
        } else {

        if (in_array($request->question_type, ['radio', 'checkbox'])) {
            TestQuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'Да',
                'option_value' => 'yes',
                'weight' => 1,
                'order_index' => 1
            ]);

            TestQuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'Нет',
                'option_value' => 'no',
                'weight' => 0,
                'order_index' => 2
            ]);
            } elseif ($request->question_type === 'scale') {
                for ($i = 1; $i <= 5; $i++) {
                    TestQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => (string)$i,
                        'option_value' => (string)$i,
                        'weight' => $i,
                        'order_index' => $i
                    ]);
                }
            }
        }

        return redirect()->route('admin.tests.show', $question->test)->with('success', 'Вопрос обновлен успешно!');
    }
}
