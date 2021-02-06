<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleAnswersAbility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->boolean('multiple_answers')->default(false);
        });
        Schema::table('user_test_answers', function (Blueprint $table) {
            $table->dropUnique(['user_test_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('multiple_answers');
        });
        Schema::table('user_test_answers', function (Blueprint $table) {
            $table->unique(['user_test_id', 'question_id']);
        });
    }
}
