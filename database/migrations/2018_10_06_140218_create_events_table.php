<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	public function up()
	{
		Schema::create('events', function(Blueprint $table) {
			$table->increments('id');
			$table->string('course_name');
			$table->string('academic_year', 11);
			$table->tinyInteger('exam_session')->unsigned();
			$table->string('status', 50)->default('0');
			$table->date('date_event');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}
