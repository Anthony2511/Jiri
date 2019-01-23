<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoresTable extends Migration {

	public function up()
	{
		Schema::create('scores', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('implementation_id')->unsigned()->nullable();
			$table->decimal('score');
			$table->text('comment')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('scores');
	}
}
