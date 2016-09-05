<?php

use Illuminate\Support\Facades\Auth;

/**
 * 商家用户注册模块
 * @author xieqixiang
 * 
 */
class MemberController extends BaseController {

    /**
     * index
     */
    public function index() {
        //nihao ma 
    }

    /**
     * 检查是否开启用户管理
     * @return type
     */
    public function isOpenmember() {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $is_openmember = $customer_info->is_openmember;
        if ($is_openmember == 1) {
            $return_msg = array('err' => 0, 'msg' => '开启会员注册', 'data' => 1);
        } else {
            $return_msg = array('err' => 0, 'msg' => '未开启会员注册', 'data' => 0);
        }
        return Response::json($return_msg);
    }

}
