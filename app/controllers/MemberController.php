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
     * web浏览
     * 添加文章用户浏览权限，若无权限返回上一页面
     */
    public function browse() {
        $id = Auth::id();
        $article = Articles::find($id);
        
        $is_openmember = $article->is_openmember;
        
//        asdfadsfsadf
        
    }

}
