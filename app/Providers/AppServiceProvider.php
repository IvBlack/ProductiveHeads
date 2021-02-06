<?php

namespace App\Providers;

use App\Services\Tests\TestEditor;
use Illuminate\Support\ServiceProvider;
use App\Services\Testing\TestingService;
use App\Models\TestSubjects\TestSubjectEditor;
use App\Services\Interfaces\TestEditorInterface;
use App\Services\Tests\ImportQuestionsConverter;
use App\Services\Interfaces\TestingServiceInterface;
use App\Services\Interfaces\TestSubjectEditorInterface;
use App\Services\Interfaces\ImportQuestionsConverterInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TestEditorInterface::class, TestEditor::class);
        $this->app->bind(ImportQuestionsConverterInterface::class, ImportQuestionsConverter::class);
        $this->app->bind(TestSubjectEditorInterface::class, TestSubjectEditor::class);
        $this->app->bind(TestingServiceInterface::class, TestingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
