<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeysPerformance extends Migration {

	public function up()
	{
    Schema::table('performances', function(Blueprint $table) {
      $table->foreign('student_id')->references('student_id')->on('student_event')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    });
	}

	public function down()
	{
    Schema::table('performances', function(Blueprint $table) {
      $table->dropForeign('performances_student_id_foreign');
    });
	}
}
