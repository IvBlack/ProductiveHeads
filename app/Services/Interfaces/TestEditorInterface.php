<?php

namespace App\Services\Interfaces;

use App\Models\Test\Test;

interface TestEditorInterface
{
    public function create(array $params) : Test;

    public function update(Test $test, array $params) : Test;

    public function delete(Test $test) : bool;
}
