<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileHomepageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mobile_homepage', function($table) {
                        $table->increments('id');
                        $table->boolean('index_show', 50)->default(1);//首页内容是否展示
                        $table->integer('m_index_showtype')->default(1);//首页展示类型
                        $table->integer('s_sort')->default(0);//首页展示内容排序
                        $table->integer('show_num')->default(5);//首页展示数量
                        $table->boolean('star_only')->default(0);//只显示推荐
                        $table->integer('c_id')->nullable();//栏目id
                        $table->integer('cus_id')->nullable();//用户id
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mobile_homepage');
	}

}
