<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('website_info', function($table) {
                        $table->increments('id');
                        $table->integer('cus_id');//用户id
                        $table->integer('pc_tpl_id')->nullable();//PC模版id
                        $table->integer('mobile_tpl_id')->nullable();//手机模版id
                        $table->integer('pc_color_id')->nullable();//PC模版颜色id
                        $table->integer('mobile_color_id')->nullable();//手机模版颜色id
                        $table->integer('pc_htpl_id')->nullable();//历史pc模版id
                        $table->integer('mobile_htpl_id')->nullable();//历史手机模版id
                        $table->integer('pc_hcolor_id')->nullable();//历史pc模版颜色id
                        $table->integer('mobile_hcolor_id')->nullable();//历史手机模版颜色id
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('website_info');
	}

}
