<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('course_id');
            $table->string('course_name');
            $table->string('stream');
            $table->string('batch');
            $table->string('semester');
            $table->string('faculty');
            $table->string('weightage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
