<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('website_config', function($table) {
                        $table->increments('id');
                        $table->integer('cus_id');//用户id
                        $table->integer('type')->default(1);//模版类型；1-PC,2-手机
                        $table->integer('template_id');//模版id
                        $table->string('key', 50);//标识所属页面
                        $table->text('value');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('website_config');
	}

}
