<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImplementationsTable extends Migration {

	public function up()
	{
		Schema::create('implementations', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('project_id')->unsigned()->nullable();
			$table->integer('student_id')->unsigned()->nullable();
			$table->integer('event_id')->unsigned()->nullable();
			$table->string('url_project', 255)->nullable();
			$table->string('url_repo', 255)->nullable();
			$table->float('ponderation');
            $table->integer('isEvaluated');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('implementations');
	}
}
