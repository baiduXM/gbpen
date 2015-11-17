<?php

class ArticleController extends BaseController{
    /*
	|--------------------------------------------------------------------------
	| 文章管理控制器
	|--------------------------------------------------------------------------
	|方法：
    |
    |articleCreate     文章添加
	|articleDelete  文章删除
    |articleList    文章列表
    |articleInfo    文章详情
    |articleSort    文章排序
    |articleMoveClassify 文章移动分类
    |articleBatchModify  文章批量设置
    |
	*/ 
    public function articleAdd(){
        $id=Input::get('id');
        if($id){
            //修改操作
            $article=Articles::find($id);
        }else{
            //新增操作
            $article=new Articles();
        }
        $cus_id=Auth::id();
        $article->title=trim(Input::get('title'));
        
        if(strlen(Input::get('c_id'))!=0){
        $article->c_id=Input::get('c_id');}
        else{$article->c_id='0';}
        $article->viewcount=Input::get('viewcount')?Input::get('viewcount'):0;
        $article->title_bold=Input::get('title_bold');
        $article->title_color=Input::get('title_color');
        $article->keywords=Input::get('keywords');
        $article->introduction=Input::get('introduction');
        $article->content=trim(Input::get('content')); 
        if($article->title=="" || $article->content==""){
            return Response::json(array('err'=>3001,'msg'=>'标题或内容不能为空'));
        }
        $article->pushed=1;
        $img_arr=explode(',',Input::get('src'));
        if(count($img_arr)){
            $article->img=$img_arr[0];
            unset($img_arr[0]);
        }
        $is_show=empty(Input::get('is_show'))?array():explode(',',Input::get('is_show'));
        $article->pc_show=0;
        $article->mobile_show=0;
        $article->wechat_show=0;
        if(count($is_show)){
            foreach($is_show as $val){
                $article->$val=1;
            }
        }
        $pubdate=Input::get('pubdate');
        if($pubdate){
           $article->created_at=date('Y-m-d H:i:s',strtotime($pubdate));
        }     
        $article->cus_id=$cus_id;
        $result=$article->save();
        dd($result);
        if($result){
            if($id){
                MoreImg::where('a_id',$id)->delete();
            }
            if(count($img_arr)){
                foreach($img_arr as $img){
                    $moreimg=new Moreimg();
                    $moreimg->title='';
                    $moreimg->img=$img;
                    $moreimg->url='';
                    $moreimg->sort='';
                    $moreimg->a_id=$article->id;
                    $moreimg->save();
                }                
            }
            $return_msg=array('err'=>0,'msg'=>'','data'=>array($article->id));
        }else{
            $return_msg=array('err'=>3001,'msg'=>'文章添加失败');
        }
        return Response::json($return_msg);
    }
    
    public function articleDelete(){
        $ids=explode(',',Input::get('id'));
        if(count($ids) > 1){
            //执行批量删除
            $failed=0;
            foreach($ids as $id){
                $result=Articles::where('id','=',$id)->delete(); 
                if(!$result){
                    $failed++;
                }else{
                    
                }
            }
            if($failed){
                 $return_msg=array('err'=>3001,'msg'=>$failed.'条记录删除失败');
            }else{
                 $return_msg=array('err'=>0,'msg'=>'');
            }
        }else{
            //单条删除
            $result=Articles::where('id','=',$ids[0])->delete();
            if($result){
                 $return_msg=array('err'=>0,'msg'=>'');
            }else{
                 $return_msg=array('err'=>3001,'msg'=>'文章删除失败');
            }
        }
        return Response::json($return_msg);
    }

    public function articleManage(){
        $data = []; 
        $classify = new classify;
        $data['catlist'] = $classify->classifyList();
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $c_id = Input::has('c_id') ? Input::get('c_id') : 0;
        $is_star=Input::has('is_star') ? Input::get('is_star') : 0;
        $data['aticlelist'] = $this->articleListData($c_id,$is_star,$per_page);
        return Response::json(['err'=>0,'msg'=>'','data'=>$data]);
    }
    
    public function articleList(){
        $c_id = Input::has('c_id') ? Input::get('c_id') : 0;
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $is_star=Input::has('is_star') ? Input::get('is_star') : 0;
        $data = $this->articleListData($c_id,$is_star,$per_page);
        if($data != NULL){
            return Response::json(['err'=>0,'msg'=>'','data'=>$data]);
        }else{
            return Response::json(['err'=>3001,'msg'=>'不存在文章','data'=>'']);
        }

    }

    public function articleListData($c_id=0,$is_star=0,$per_page=15){        
        $cus_id=Auth::id();
        if($c_id){
            $cus_data=new PrintController();
            $c_ids=explode(',',$cus_data->getChirldenCid($c_id));
            if($is_star){
                $article_list=Articles::whereIn('c_id',$c_ids)->where('is_star','=',$is_star)->paginate($per_page);
            }else{
                $article_list=Articles::whereIn('c_id',$c_ids)->paginate($per_page);
            }
        }else{
            if($is_star){
                $article_list=Articles::where('cus_id','=',$cus_id)->where('is_star','=',$is_star)->paginate($per_page);
            }else{
                $article_list=Articles::where('cus_id','=',$cus_id)->paginate($per_page);
            }
        }
        $article_arr=$article_list->toArray();
        if(count($article_arr['data'])){
            foreach($article_arr['data'] as $k => $v){
                $moring = [];
                $article_arr['data'][$k]['c_name'] = Classify::where('id',$v['c_id'])->pluck('name');
                $moring = Moreimg::where('a_id',$v['id'])->orderBy('sort')->lists('img');
                array_unshift($moring,$v['img']);
                $article_arr['data'][$k]['img'] = $moring;
                unset($moring);
            }
            return $article_arr;
        }
    }
    
    public function articleInfo(){
        $id=Input::get('id');
        $article=Articles::find($id);
        $customer=Auth::user()->name;
        if($article){
            if($article->img!=''){
                $img=array();
                $img[]=asset("customers/$customer/images/l/articles").'/'.$article->img;
                $moreimg=Moreimg::where('a_id',$id)->lists('img');
                if(count($moreimg)){
                    foreach($moreimg as $v){
                        $img[]=asset("customers/$customer/images/l/articles").'/'.$v;
                    }
                }
                $article->img=$img;
            }
            $return_msg=array('err'=>0,'msg'=>'','data'=>$article->toArray());  
        }else{
            $return_msg=array('err'=>3001,'msg'=>'该文章不存在');
        }
        return Response::json($return_msg);
    }
    
    public function articleSort(){
        $cus_id=Auth::id();
        $id=Input::get('id');
        $s_type=Input::get('s_type');
        if($s_type=='up'){
            $now_article= Articles::find($id);
            $now_sort=$now_article->sort;
            $search_article=Articles::where('sort','<',$now_sort)->where('cus_id','=',$cus_id)->orderBy('sort','desc')->first()->toArray();
            if($search_article===NULL){
                 $return_msg=array('err'=>3001,'msg'=>'已是最小序号');
            }else{
                $now_article->sort=$search_article['sort'];
                $up_rst=$now_article->save();
                if($up_rst){
                   $up_rst=Articles::where('id','=',$search_article['id'])->update(array('sort'=>$now_sort));
                   if($up_rst){
                       $return_msg=array('err'=>0,'msg'=>'');
                   }else{
                       $now_article->sort=$now_sort;
                       $up_rst=$now_article->save();
                       $i=1;
                       while(!$up_rst && $i<=3){
                           $up_rst=$now_article->save();
                            $i++;
                       }
                       $return_msg=array('err'=>3001,'msg'=>'排序失败');
                   }
                }
            }
        }else{
            $now_article= Articles::find($id);
            $now_sort=$now_article->sort;
            $search_article=Articles::where('sort','>',$now_sort)->where('cus_id','=',$cus_id)->first()->toArray();
            if($search_article===NULL){
                $return_msg=array('err'=>3001,'msg'=>'已是最大序号');
            }else{
                $now_article->sort=$search_article['sort'];
                $up_rst=$now_article->save();
                if($up_rst){
                   $up_rst=Articles::where('id','=',$search_article['id'])->update(array('sort'=>$now_sort));
                   if($up_rst){
                       $return_msg=array('err'=>0,'msg'=>'');
                   }else{
                       $now_article->sort=$now_sort;
                       $up_rst=$now_article->save();
                       $i=1;
                       while(!$up_rst && $i<=3){
                           $up_rst=$now_article->save();
                            $i++;
                       }
                       $return_msg=array('err'=>3001,'msg'=>'排序失败');
                   }
                }
            }
        }
        return Response::json($return_msg);
    }
    
    public function articleMoveClassify(){
        $ids=explode(',',Input::get('id'));
        $c_id=Input::get('target_catid');
        $data=array();
        $err=false;
        $c_c_id=Classify::where('p_id',$c_id)->pluck('id');
        if($c_c_id){
            $return_msg=array('err'=>3001,'msg'=>'移动失败,节点栏目不能存在文章','data'=>array());
        }else{
            if(count($ids) > 1){
                foreach($ids as $id){
                    $article=Articles::find($id);
                    $article->c_id=$c_id;
                    $result=$article->save();
                    if($result){
                        $data[]=$id;
                    }else{
                        $err=true;
                    }
                }
                if($err){
                    $return_msg=array('err'=>3001,'msg'=>'部分移动失败','data'=>$data);
                }else{
                    $return_msg=array('err'=>0,'msg'=>'');
                }
            }else{
                $article=Articles::find($ids[0]);
                $article->c_id=$c_id;
                $result=$article->save();
                if($result){
                    $return_msg=array('err'=>0,'msg'=>'');
                }else{
                    $return_mag=array('err'=>3001,'msg'=>'移动失败');
                } 
            }
        }
        return Response::json($return_msg);
    }
    
    public function articleBatchModify(){
        $ids=explode(',',Input::get('id'));
        $action=Input::get('action');
        $value=Input::get('values');
        $relation=array(
            'set_star'=>'is_star',
            'set_top'=>'is_top',
            'set_pcshow'=>'pc_show',
            'set_mobileshow'=>'mobile_show',
            'set_wechatshow'=>'wechat_show'
            );
        if(count($ids) > 1){
            $data=array();
            $err=false;
            foreach($ids as $id){
                $article=Articles::find($id);
                $article->$relation[$action]=$value;
                $result=$article->save();
                if($result){
                    $data[]=$id;
                }else{
                    $err=true;
                }
            }
            if($err){
                $return_msg=array('err'=>3001,'msg'=>'部分变更失败','data'=>$data);
            }else{
                $return_msg=array('err'=>0,'msg'=>'');
            }
        }else{
             $article=Articles::find($ids[0]);
             $article->$relation[$action]=$value;
             $result=$article->save();
            if($result){
                $return_msg=array('err'=>0,'msg'=>'');
            }else{
                $return_mag=array('err'=>3001,'msg'=>'变更失败');
            }
        }
        return Response::json($return_msg);
    }
}

?>
