<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentEventTable extends Migration {

	public function up()
	{
		Schema::create('student_event', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('student_id')->unsigned()->nullable();
			$table->integer('event_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('student_event');
	}
}