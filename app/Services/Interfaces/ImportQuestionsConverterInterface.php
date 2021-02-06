<?php


namespace App\Services\Interfaces;


interface ImportQuestionsConverterInterface
{
    public function convert(string $csv) : array;
}
