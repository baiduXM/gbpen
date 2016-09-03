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

    public function CustomerIsOpenmember() {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        $is_openmember = $customer_info->is_openmember;
        $return_msg = array('err' => 3001, 'msg' => '文章添加失败', 'data' => 1);
        return Response::json($return_msg);
    }

}
