<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerformancesTable extends Migration {

	public function up()
	{
		Schema::create('performances', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->decimal('calculated_score')->nullable();
			$table->decimal('manual_score')->nullable();
			$table->integer('student_id')->unsigned();
			$table->integer('event_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('performances');
	}
}
