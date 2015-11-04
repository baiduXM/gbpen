<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoreimgTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moreimg', function($table) {
                        $table->increments('id');
                        $table->string('title');//url的title属性
                        $table->string('img', 20);//图片名称
                        $table->string('url', 20);//图片url
                        $table->string('a_id', 20);//文章id
                        $table->string('sort', 20);//图片排序
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('moreimg');
	}

}
