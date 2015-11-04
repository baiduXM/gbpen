<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer', function($table) {
                        $table->increments('id');
                        $table->string('name', 50);
                        $table->string('email', 40)->unique();
                        $table->string('password', 60);
                        $table->string('password_temp', 40)->nullable();//临时密码
                        $table->string('remember_token', 100)->nullable();
                        $table->integer('serv_id')->unsigned()->nullable();
                        $table->string('ftp_address')->nullable();
                        $table->string('ftp_user', 50)->nullable();
                        $table->string('ftp_pwd', 60)->nullable();
                        $table->timestamp('ended_at');
                        $table->boolean('status')->default(1);//用户状态；1-开，0-关
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
		Schema::drop('customer');
	}

}
