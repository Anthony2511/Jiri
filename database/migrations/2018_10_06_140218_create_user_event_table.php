<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserEventTable extends Migration {

	public function up()
	{
		Schema::create('user_event', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('user_event');
	}
}