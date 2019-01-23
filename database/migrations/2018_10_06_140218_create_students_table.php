<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration {

	public function up()
	{
		Schema::create('students', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('email');
            $table->boolean('is_active')->default(1);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('students');
	}
}
