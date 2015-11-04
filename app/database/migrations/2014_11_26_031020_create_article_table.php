<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article', function($table) {
                        $table->increments('id');
                        $table->string('title');
                        $table->boolean('title_bold')->default(0);
                        $table->string('title_color', 255)->nullable();
                        $table->string('img')->nullable();
                        $table->integer('viewcount')->default(0);
                        $table->string('keywords', 255)->nullable();
                        $table->string('introduction', 1000)->nullable();//文章简介，同时用作文章页description
                        $table->text('content')->nullable();//文章内容
                        $table->boolean('is_top')->default(0);//是否置顶；1-是，0-否
                        $table->boolean('is_star')->default(0);//是否推荐；1-是，0-否
                        $table->boolean('pc_show')->default(0);//PC显示
                        $table->boolean('mobile_show')->default(0);//手机显示
                        $table->boolean('wechat_show')->default(0);//微信显示
                        $table->boolean('pushed')->default(0);//pc是否显示
                        $table->integer('sort')->default(0);//排序
                        $table->integer('cus_id');//用户id
                        $table->integer('c_id');//所属栏目id
                        $table->timestamps();//时间戳,包括created_at和updated_at
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article');
	}

}
