<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('classify', function($table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('img')->nullable();
			$table->string('meta_keywords', 255)->nullable();
			$table->string('meta_description', 1000)->nullable();
			$table->integer('sort')->default(0);
			$table->integer('s_sort')->default(0);
			$table->boolean('type')->default('0'); //默认0；0-不限，1-文字列表，2-图片列表，3-图文列表，4-内容单页，5-功能，6-外链，7-微信功能，8-直达号功能，9-万用表单
			$table->boolean('article_type')->default('1'); //默认1；1-产品类型，2-新闻类型
			$table->integer('page_id')->nullable();
			$table->boolean('pc_show')->default(0); //pc是否显示
			$table->boolean('mobile_show')->default(0); //手机是否显示
			$table->boolean('wechat_show')->default(0); //微信是否显示
			$table->boolean('pushed')->default(0); //pc是否显示
			$table->integer('cus_id');
			$table->string('url')->nullable();
			$table->integer('p_id');
			$table->timestamps(); //时间戳,包括created_at和updated_at
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('classify');
	}

}
