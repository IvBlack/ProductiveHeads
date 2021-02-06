<?php

namespace App\Http\Controllers\Admin;

use App\Models\Test\Test;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Tests\TestResource;
use App\Http\Requests\Tests\ImportRequest;
use Illuminate\Support\HigherOrderTapProxy;
use App\Http\Requests\Tests\CreateTestRequest;
use App\Http\Resources\Admin\Tests\TestInfoResource;
use App\Http\Requests\Tests\UpdateTestRequest;
use App\Http\Requests\Tests\SearchTestRequest;
use App\Http\Resources\BasicResourceCollection;
use App\Services\Interfaces\TestEditorInterface;
use App\Http\Requests\TestSubjects\AddTestsRequest;
use App\Repositories\Interfaces\TestRepositoryInterface;
use App\Services\Interfaces\ImportQuestionsConverterInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TestController extends Controller
{
    /**
     * @param TestRepositoryInterface $testRepository
     *
     * @return JsonResponse
     */
    public function index(TestRepositoryInterface $testRepository)
    {
        return TestInfoResource::collection($testRepository->getList());
    }

    /**
     * @param TestRepositoryInterface $testRepository
     * @param SearchTestRequest $request
     *
     * @return BasicResourceCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
    public function search(TestRepositoryInterface $testRepository, SearchTestRequest $request)
    {
        return TestInfoResource::collection($testRepository->search($request->input('search'), $request->input('exclude')));
    }

    public function create(CreateTestRequest $request, TestEditorInterface $testEditor)
    {
        $test = $testEditor->create($request->all());

        return TestResource::make($test);
    }

    public function show(Test $test)
    {
        return TestResource::make($test);
    }

    public function update(Test $test, TestEditorInterface $testEditor, UpdateTestRequest $request)
    {
        $test = $testEditor->update($test, $request->all());
        return TestResource::make($test);
    }

    public function delete(Test $test, TestEditorInterface $testEditor)
    {
        $testEditor->delete($test);
        return true;
    }

    public function import(ImportRequest $request, ImportQuestionsConverterInterface $converter)
    {
        return $converter->convert($request->file('file')->get());
    }
}

