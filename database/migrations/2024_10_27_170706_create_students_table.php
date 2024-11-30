<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->longText('value');
            $table->timestamps();
        });
        
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->boolean('has_group')->default(0);
            $table->timestamps();
        });
        
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->timestamps();
            
            $table->foreign('group_id')
                ->references('id')
                ->on('groups');
        });
        
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->integer('roll');
            $table->string('name');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('optional_subject_id')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')
                ->references('id')
                ->on('classes');
            $table->foreign('group_id')
                ->references('id')
                ->on('groups');
            $table->foreign('optional_subject_id')
                ->references('id')
                ->on('subjects');
        });
      
        
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->onDelete('cascade');
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');
            $table->foreign('group_id')
                ->references('id')
                ->on('groups');
        });
        
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->boolean('isCompleted')->default(false);
            $table->timestamps();
        });
        
        Schema::create('exam_subject_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->integer('full_mark');
            $table->longText('criteria');
            $table->timestamps();
            $table->foreign('class_id')
                ->references('id')
                ->on('classes');
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams');
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');
        });
        
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('subject_id');
            $table->integer('total_mark_obtain');
            $table->decimal('point');
            $table->string('grade');
            $table->string('status');
            $table->longText('result');
            $table->timestamps();
            
            $table->foreign('student_id')
                ->references('id')
                ->on('students');
            $table->foreign('class_id')
                ->references('id')
                ->on('classes');
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams');
            $table->foreign('subject_id')
                ->references('id')
                ->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('class_subjects');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_subject_distributions');
        Schema::dropIfExists('results');
    }
};
