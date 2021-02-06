<?php

namespace App\Services\Tests;

use DB;
use Exception;
use App\Models\Test\Test;
use App\Models\Test\Question;
use Illuminate\Support\Collection;
use App\Models\Test\PossibleAnswer;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Interfaces\TestEditorInterface;

class TestEditor implements TestEditorInterface
{

    public function create(array $params) : Test
    {
        DB::beginTransaction();
        try {
            $test = Test::create([
                'title' => $params['title'],
                'description' => $params['description'] ?? '',
            ]);

            $this->createOrUpdateQuestions($test, $params['questions']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $test;
    }

    public function update(Test $test, array $params) : Test
    {
        DB::beginTransaction();
        try {
            $test->title = $params['title'] ?? $test->title;
            $test->description = $params['description'] ?? $test->description;

            $test->save();

            //можно сделать сложнее для понимания, но проще для БД, но пока так дешевле
            $this->createOrUpdateQuestions($test, $params['questions']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $test;
    }

    private function createOrUpdateQuestions(Test $test, array $questionsData)
    {
        $questionIds = array_column($questionsData, 'id');
        $answerIds = array_reduce($questionsData, function (array $acc, $questionDate) {
            $answerIds = array_column($questionDate['possible_answers'], 'id');
            $acc = array_merge($acc, $answerIds);
            return $acc;
        }, []);

        //удаляем старые вопросы/ответ и получаем сразу все остальные, что бы не делать этого в цикле
        if ($questionIds) {
            $test->questions()
                ->whereNotIn('id', $questionIds)
                ->delete();

            $questions = $test->questions()
                ->get()
                ->keyBy('id');
        } else {
            $questions = collect();
        }

        if ($answerIds) {
            $questionPluckIds = $questions->pluck('id');
            PossibleAnswer::whereNotIn('id', $answerIds)
                ->whereIn('question_id', $questionPluckIds)
                ->delete();

            $answers = PossibleAnswer::whereIn('question_id', $questionPluckIds)
                ->get()
                ->keyBy('id');
        } else {
            $answers = collect();
        }

        foreach ($questionsData as $questionData) {
            if (isset($questionData['id'])) {
                $question = $questions[$questionData['id']] ?? new Question();
            } else {
                $question = new Question();
            }
            $question->test_id = $test->id;
            $question->question = $questionData['question'];
            $question->description = $questionData['description'] ?? '';
            $question->sequence = $questionData['sequence'];
            $question->multiple_answers = $questionData['multiple_answers'] ?? false;
            $question->save();

            foreach ($questionData['possible_answers'] as $answerData) {
                if (isset($answerData['id'])) {
                    $answer = $answers[$answerData['id']] ?? new PossibleAnswer();
                } else {
                    $answer = new PossibleAnswer();
                }

                $answer->question_id = $question->id;
                $answer->answer = $answerData['answer'];
                $answer->sequence = $answerData['sequence'];

                $answer->save();
            }
        }
    }

    /**
     * @param Test $test
     *
     * @return bool
     * @throws Exception
     */
    public function delete(Test $test) : bool
    {
        return $test->delete();
    }
}
