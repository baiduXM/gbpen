<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template', function($table) {
                        $table->increments('id');
                        $table->string('name', 50);//模版目录名
                        $table->string('tpl_name', 255);//模版名称
                        $table->string('classify');//模版分类
                        $table->string('demo')->nullable();//演示站点
                        $table->boolean('type')->default(1);//所属类型;1-pc,2-mobile
                        $table->integer('used')->default(0);//使用次数
                        $table->integer('cus_id')->default(0);//用户id
                        $table->integer('former_id')->default(0);//用户idformer_id
                        $table->text('description')->nullable();//模板description
                        $table->integer('list1showtypetotal')->default(0);//文字列表展示样式种类
                        $table->integer('list2showtypetotal')->default(0);//图片列表展示样式种类
                        $table->integer('list3showtypetotal')->default(0);//图文列表展示样式种类
                        $table->integer('list4showtypetotal')->default(0);//内容单页展示样式种类
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
		Schema::drop('template');
	}

}
