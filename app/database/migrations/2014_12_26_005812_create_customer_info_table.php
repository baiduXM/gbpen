<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_info', function($table) {
                        $table->increments('id');
                        $table->integer('cus_id');//用户id
                        $table->string('company')->nullable();//公司名称
                        $table->string('pc_domain', 120);//域名
                        $table->string('mobile_domain', 120);//域名
                        $table->string('favicon', 80);//模版名称
                        $table->string('logo', 80);//模版名称
                        $table->string('logo_small',80);//模版分类
                        $table->string('title')->nullable();//模版图片
                        $table->string('keywords')->nullable();//演示站点
                        $table->string('description')->nullable();//所属类型;1-pc,2-mobile
                        $table->text('pc_header_script')->nullable();//PC头部脚本
                        $table->text('mobile_header_script')->nullable();//手机头部脚本
                        $table->text('footer')->nullable();//PC底部版权
                        $table->text('mobile_footer')->nullable();//手机底部版权
                        $table->text('pc_footer_script')->nullable();//PC底部脚本
                        $table->text('mobile_footer_script')->nullable();//手机底部脚本
                        $table->integer('pc_page_count')->default(12);//pc每页个数
                        $table->integer('pc_page_links')->default(5);//pc分页链接显示个数
                        $table->integer('mobile_page_count')->default(12);//手机每页个数
                        $table->integer('mobile_page_links')->default(5);//手机分页链接显示个数
                        $table->string('telephone')->nullable();//电话
                        $table->string('mobile')->nullable();//手机
                        $table->string('address')->nullable();//地址
                        $table->string('fax',50)->nullable();//传真
                        $table->string('email',50)->nullable();//地址
                        $table->string('qq',50)->nullable();//QQ
                        $table->string('contact_name')->nullable();//联系人
                        $table->timestamp('pushed_at')->nullable();//推送时间
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
		Schema::drop('customer_info');
	}

}
