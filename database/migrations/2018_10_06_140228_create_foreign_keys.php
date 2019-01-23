<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('student_event', function(Blueprint $table) {
			$table->foreign('student_id')->references('id')->on('students')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('student_event', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('user_event', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('user_event', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->foreign('student_id')->references('id')->on('students')
						->onDelete('set null')
						->onUpdate('set null');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('set null')
						->onUpdate('set null');
		});
	}

	public function down()
	{
		Schema::table('student_event', function(Blueprint $table) {
			$table->dropForeign('student_event_student_id_foreign');
		});
		Schema::table('student_event', function(Blueprint $table) {
			$table->dropForeign('student_event_event_id_foreign');
		});
		Schema::table('user_event', function(Blueprint $table) {
			$table->dropForeign('user_event_event_id_foreign');
		});
		Schema::table('user_event', function(Blueprint $table) {
			$table->dropForeign('user_event_user_id_foreign');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_event_id_foreign');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->dropForeign('implementations_project_id_foreign');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->dropForeign('implementations_student_id_foreign');
		});
		Schema::table('implementations', function(Blueprint $table) {
			$table->dropForeign('implementations_event_id_foreign');
		});
	}
}