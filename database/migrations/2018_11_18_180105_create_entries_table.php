<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->date('date')->index();
            $table->time('begin')->nullable();
            $table->time('end')->nullable();
            $table->decimal('break', 8,2)->default(0);
            $table->decimal('regular_hours', 8,2)->default(0);
            $table->decimal('actual_hours', 8,2)->nullable();
            $table->decimal('overtime', 8,2)->nullable();
            $table->decimal('balance', 8,2)->nullable();
            $table->integer('schedule_version')->default(1);
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
