<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateToColorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template_to_color', function($table) {
                        $table->increments('id');
                        $table->integer('template_id');//模版id
                        $table->integer('color_id');//颜色id
                        $table->string('color_code',7);//颜色十六进制编号
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('template_to_color');
	}

}
