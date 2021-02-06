<?php


namespace App\Services\Tests;


class ImportQuestionsConverter implements \App\Services\Interfaces\ImportQuestionsConverterInterface
{

    public function convert(string $csv) : array
    {
        $rows = explode("\n", $csv);
        $result = [];
        foreach ($rows as $questionSequence => $row) {
            $cols = explode(';', $row);
            $question = [
                'question' => $cols[0],
                'description' => $cols[1] ?? '',
                'sequence' => $questionSequence,
            ];

            foreach ($cols as $key => $col) {
                if ($key < 2) {
                    continue;
                }
                $question['possible_answers'][] = [
                    'answer' => $col,
                    'sequence' => $key - 2,
                ];
            }
            $result[] = $question;
        }

        return $result;
    }
}
