<?php

/**
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
  |articleSortModify   文章顺序修改
  |articleBatchAdd   文章批量添加
  |articleTitleModify   文章标题修改
 */
class ArticleController extends BaseController {

    public function articleAdd() {
        $org_imgs = array();
        $del_imgs = array();
        $id = Input::get('id');
        if ($id) {
            //修改操作
            $article = Articles::find($id);
            $data = MoreImg::where('a_id', $id)->get()->toArray();
            foreach ((array) $data as $v) {
                $org_imgs[] = $v["img"];
            }
            $org_imgs[] = $article->img;
        } else {
            //新增操作
            $article = new Articles();
        }
        $cus_id = Auth::id();
        $article->title = trim(Input::get('title'));
        $article->c_id = Input::get('c_id');
        $article->viewcount = Input::get('viewcount') ? Input::get('viewcount') : 0;
        $article->title_bold = Input::get('title_bold');
        $article->title_color = Input::get('title_color');
        $article->keywords = Input::get('keywords');
        $article->introduction = Input::get('introduction');
        $article->content = trim(Input::get('content'));
        $article->url = trim(Input::get('url'));
        $article->use_url = trim(Input::get('use_url'));
        if ($article->title == "") {
            return Response::json(array('err' => 3001, 'msg' => '标题不能为空'));
        }
        $img_arr = explode(',', Input::get('src'));
        foreach ((array) $org_imgs as $v) {
            if (!in_array($v, (array) $img_arr)) {
                $del_imgs[] = $v;
            }
        }
        if (count($img_arr)) {
            $article->img = $img_arr[0];
            unset($img_arr[0]);
        }
        $is_show = empty(Input::get('is_show')) ? array() : explode(',', Input::get('is_show'));
        $article->pc_show = 0;
        $article->mobile_show = 0;
        $article->wechat_show = 0;
        if (count($is_show)) {
            foreach ($is_show as $val) {
                $article->$val = 1;
            }
        }
        $pubdate = Input::get('pubdate');
        if ($pubdate) {
            $article->created_at = date('Y-m-d H:i:s', strtotime($pubdate));
        }
        $article->cus_id = $cus_id;
        $article->pushed = 1;
        //===ueditor文件统计容量===
        $Capacity = new CapacityController();
        $Capacity->compare_filename($article->content, $article->file_array);
        $article->file_array = $Capacity->reg_ueditor_content($article->content);
        $ue_img=explode(",", $article->file_array);
        //===end===
        $result = $article->save();
        if ($result) {
            if ($id) {
                MoreImg::where('a_id', $id)->delete();
                foreach ((array) $del_imgs as $v) {
                    $imgdel = new ImgDel();
                    $imgdel->mysave($v, 'articles' , 'edit' );
                }
            }            
            if (count($img_arr)) {
                foreach ($img_arr as $img) {
                    $moreimg = new Moreimg();
                    $moreimg->title = '';
                    $moreimg->img = $img;
                    $moreimg->url = '';
                    $moreimg->sort = '';
                    $moreimg->a_id = $article->id;
                    $moreimg->save();
                }
            }
            if(count($ue_img)){
               foreach ($ue_img as $uimg) {
                    $moreimg = new Moreimg();
                    $moreimg->title = '';
                    $moreimg->img = $uimg;
                    $moreimg->url = '';
                    $moreimg->sort = '';
                    $moreimg->a_id = $article->id;
                    $moreimg->from = 'ueditor';
                    $moreimg->save();
                } 
            }

            $this->logsAdd("article",__FUNCTION__,__CLASS__,1,"添加文章",0,$article->id);
            $return_msg = array('err' => 0, 'msg' => '', 'data' => array($article->id));
        } else {
            $return_msg = array('err' => 3001, 'msg' => '文章添加失败');
        }
        return Response::json($return_msg);
    }

    public function articleDelete() {
        $del_imgs = array();
        $cus_id = Auth::id();
        $ids = explode(',', Input::get('id'));
        if (count($ids) > 1) {
            //执行批量删除
            $failed = 0;
            foreach ($ids as $id) {
                $article = Articles::find($id);
                Classify::where('cus_id', $cus_id)->where('id', $article->c_id)->update(['pushed' => 1]);
                $data = MoreImg::where('a_id', $id)->get()->toArray();
                $result = Articles::where('id', '=', $id)->delete();
                if (!$result) {
                    $failed++;
                } else {
                    foreach ((array) $data as $v) {
                        $del_imgs[] = $v["img"];
                    }
                    $del_imgs[] = $article->img;
                }
            }
            foreach ((array) $del_imgs as $v) {
                $imgdel = new ImgDel();
                $imgdel->mysave($v, 'articles');
            }
            if ($failed) {
                $this->logsAdd("article",__FUNCTION__,__CLASS__,5,"批量删除文章，".$failed."条删除失败",0,$ids);
                $return_msg = array('err' => 3001, 'msg' => $failed . '条记录删除失败');
            } else {
                $this->logsAdd("article",__FUNCTION__,__CLASS__,5,"批量删除文章",0,$ids);
                $return_msg = array('err' => 0, 'msg' => '');
            }
        } else {
            //单条删除
            $article = Articles::find($ids[0]);
            Classify::where('cus_id', $cus_id)->where('id', $article->c_id)->update(['pushed' => 1]);
            $data = MoreImg::where('a_id', $ids[0])->get()->toArray();
            $result = Articles::where('id', '=', $ids[0])->delete();
            if ($result) {
                foreach ((array) $data as $v) {
                    $del_imgs[] = $v["img"];
                }
                $del_imgs[] = $article->img;
                foreach ((array) $del_imgs as $v) {                   
                    $imgdel = new ImgDel();
                    $imgdel->mysave($v, 'articles');
                }
                $this->logsAdd("article",__FUNCTION__,__CLASS__,2,"删除文章",0,$ids[0]);
                $return_msg = array('err' => 0, 'msg' => '');
            } else {
                $return_msg = array('err' => 3001, 'msg' => '文章删除失败');
            }
        }
        return Response::json($return_msg);
    }

    /**
     * 文章列表
     * @return type
     */
    public function articleManage() {
        $customer = Auth::user()->name;
        $data = [];
        $classify = new classify;
        $data['catlist'] = $classify->classifyList();
        $search_word = Input::has('search_word') ? Input::get('search_word') : '';
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $c_id = Input::has('c_id') ? Input::get('c_id') : 0;
        $is_star = Input::has('is_star') ? Input::get('is_star') : 0;
        $data['aticlelist'] = $this->articleListData($c_id, $is_star, $per_page, $search_word);
        $data['source_dir'] = asset("customers/$customer/images/s/articles") . '/';
        return Response::json(['err' => 0, 'msg' => '', 'data' => $data]);
    }

    public function articleSortModify() {
        $cus_id = Auth::id();
        $id = Input::get('id');
        $sort = Input::get('sort');
        $result = Articles::where('id', $id)->where('cus_id', $cus_id)->update(['sort' => $sort, 'pushed' => 1]);
        if ($result) {
            $this->logsAdd("article",__FUNCTION__,__CLASS__,3,"修改文章排序",0,$id);
            return Response::json(['err' => 0, 'msg' => '修改成功']);
        } else {
            return Response::json(['err' => 3001, 'msg' => '修改失败']);
        }
    }

    public function articleTitleModify() {
        $cus_id = Auth::id();
        $id = Input::get('id');
        $title = Input::get('title');
        $result = Articles::where('id', $id)->where('cus_id', $cus_id)->update(['title' => $title, 'pushed' => 1]);
        if ($result) {
            $this->logsAdd("article",__FUNCTION__,__CLASS__,3,"修改文章标题",0,$id);
            return Response::json(['err' => 0, 'msg' => '修改成功']);
        } else {
            return Response::json(['err' => 3001, 'msg' => '修改失败']);
        }
    }

    /**
     * 
     * @return type
     */
    public function articleList() {
        $c_id = Input::has('c_id') ? Input::get('c_id') : 0;
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $is_star = Input::has('is_star') ? Input::get('is_star') : 0;
        $data = $this->articleListData($c_id, $is_star, $per_page);
        if ($data != NULL) {
            return Response::json(['err' => 0, 'msg' => '', 'data' => $data]);
        } else {
            return Response::json(['err' => 3001, 'msg' => '不存在文章', 'data' => '']);
        }
    }

    /**
     * 
     * @param type $c_id
     * @param type $is_star
     * @param type $per_page
     * @param type $search_word ===搜索关键字===
     * @return type
     */
    public function articleListData($c_id = 0, $is_star = 0, $per_page = 15, $search_word = '') {
        $cus_id = Auth::id();
        if (!empty($search_word)) {
            if ($c_id) {
                $cus_data = new PrintController();
                $c_ids = explode(',', $cus_data->getChirldenCid($c_id));
                if ($is_star) {
                    $article_list = Articles::whereIn('c_id', $c_ids)->where('title', 'like', '%' . $search_word . '%')->where('is_star', '=', $is_star)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                } else {
                    $article_list = Articles::whereIn('c_id', $c_ids)->where('title', 'like', '%' . $search_word . '%')->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                }
            } else {
                if ($is_star) {
                    $article_list = Articles::where('cus_id', '=', $cus_id)->where('title', 'like', '%' . $search_word . '%')->where('is_star', '=', $is_star)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                } else {
                    $article_list = Articles::where('cus_id', '=', $cus_id)->where('title', 'like', '%' . $search_word . '%')->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                }
            }
        } else {
            if ($c_id) {
                $cus_data = new PrintController();
                $c_ids = explode(',', $cus_data->getChirldenCid($c_id));
                if ($is_star) {
                    $article_list = Articles::whereIn('c_id', $c_ids)->where('is_star', '=', $is_star)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                } else {
                    $article_list = Articles::whereIn('c_id', $c_ids)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                }
            } else {
                if ($is_star) {
                    $article_list = Articles::where('cus_id', '=', $cus_id)->where('is_star', '=', $is_star)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                } else {
                    $article_list = Articles::where('cus_id', '=', $cus_id)->orderBy('is_top', 'DESC')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->paginate($per_page);
                }
            }
        }
        $article_arr = $article_list->toArray();
        if (count($article_arr['data'])) {
            foreach ($article_arr['data'] as $k => $v) {
                $moring = [];
                $article_arr['data'][$k]['c_name'] = Classify::where('id', $v['c_id'])->pluck('name');
                $moring = Moreimg::where('a_id', $v['id'])->orderBy('sort')->lists('img');
                array_unshift($moring, $v['img']);
                $article_arr['data'][$k]['img'] = $moring;
                unset($moring);
            }
            return $article_arr;
        }
    }

    public function articleInfo() {
        $id = Input::get('id');
        $article = Articles::find($id);
        $customer = Auth::user()->name;
        if ($article) {
            if ($article->img != '') {
                $img = array();
                $img[] = asset("customers/$customer/images/l/articles") . '/' . $article->img;
                $moreimg = Moreimg::where('a_id', $id)->lists('img');
                if (count($moreimg)) {
                    foreach ($moreimg as $v) {
                        $img[] = asset("customers/$customer/images/l/articles") . '/' . $v;
                    }
                }
                $article->img = $img;
            }
            $return_msg = array('err' => 0, 'msg' => '', 'data' => $article->toArray());
        } else {
            $return_msg = array('err' => 3001, 'msg' => '该文章不存在');
        }
        return Response::json($return_msg);
    }

    public function articleSort() {
        $cus_id = Auth::id();
        $id = Input::get('id');
        $s_type = Input::get('s_type');
        if ($s_type == 'up') {
            $now_article = Articles::find($id);
            $now_sort = $now_article->sort;
            $search_article = Articles::where('sort', '<', $now_sort)->where('cus_id', '=', $cus_id)->orderBy('sort', 'desc')->first()->toArray();
            if ($search_article === NULL) {
                $return_msg = array('err' => 3001, 'msg' => '已是最小序号');
            } else {
                $now_article->sort = $search_article['sort'];
                $up_rst = $now_article->save();
                if ($up_rst) {
                    $up_rst = Articles::where('id', '=', $search_article['id'])->update(array('sort' => $now_sort));
                    if ($up_rst) {
                        $this->logsAdd("article",__FUNCTION__,__CLASS__,3,"修改文章排序",0,$id);
                        $return_msg = array('err' => 0, 'msg' => '');
                    } else {
                        $now_article->sort = $now_sort;
                        $up_rst = $now_article->save();
                        $i = 1;
                        while (!$up_rst && $i <= 3) {
                            $up_rst = $now_article->save();
                            $i++;
                        }
                        $return_msg = array('err' => 3001, 'msg' => '排序失败');
                    }
                }
            }
        } else {
            $now_article = Articles::find($id);
            $now_sort = $now_article->sort;
            $search_article = Articles::where('sort', '>', $now_sort)->where('cus_id', '=', $cus_id)->first()->toArray();
            if ($search_article === NULL) {
                $return_msg = array('err' => 3001, 'msg' => '已是最大序号');
            } else {
                $now_article->sort = $search_article['sort'];
                $up_rst = $now_article->save();
                if ($up_rst) {
                    $up_rst = Articles::where('id', '=', $search_article['id'])->update(array('sort' => $now_sort));
                    if ($up_rst) {
                        $return_msg = array('err' => 0, 'msg' => '');
                    } else {
                        $now_article->sort = $now_sort;
                        $up_rst = $now_article->save();
                        $i = 1;
                        while (!$up_rst && $i <= 3) {
                            $up_rst = $now_article->save();
                            $i++;
                        }
                        $return_msg = array('err' => 3001, 'msg' => '排序失败');
                    }
                }
            }
        }
        return Response::json($return_msg);
    }

    public function articleMoveClassify() {
        $ids = explode(',', Input::get('id'));
        $c_id = Input::get('target_catid');
        $data = array();
        $err = false;
        $c_c_id = Classify::where('p_id', $c_id)->pluck('id');
        if ($c_c_id) {
            $return_msg = array('err' => 3001, 'msg' => '移动失败,节点栏目不能存在文章', 'data' => array());
        } else {
            if (count($ids) > 1) {
                foreach ($ids as $id) {
                    $article = Articles::find($id);
                    $article->c_id = $c_id;
                    $result = $article->save();
                    if ($result) {
                        $data[] = $id;
                    } else {
                        $err = true;
                    }
                }
                if ($err) {
                    $this->logsAdd("article",__FUNCTION__,__CLASS__,6,"修改文章所属栏目到:".$c_id.",部分移动失败",0,$ids);
                    $return_msg = array('err' => 3001, 'msg' => '部分移动失败', 'data' => $data);
                } else {
                    $this->logsAdd("article",__FUNCTION__,__CLASS__,6,"修改文章所属栏目到:".$c_id,0,$ids);
                    $return_msg = array('err' => 0, 'msg' => '');
                }
            } else {
                $article = Articles::find($ids[0]);
                $article->c_id = $c_id;
                $result = $article->save();
                if ($result) {
                    $this->logsAdd("article",__FUNCTION__,__CLASS__,3,"修改文章所属栏目到:".$c_id,0,$ids[0]);
                    $return_msg = array('err' => 0, 'msg' => '');
                } else {
                    $return_mag = array('err' => 3001, 'msg' => '移动失败');
                }
            }
        }
        return Response::json($return_msg);
    }

    public function articleBatchModify() {
        $ids = explode(',', Input::get('id'));
        $action = Input::get('action');
        $value = Input::get('values');
        $cus_id = Auth::id();
        $relation = array(
            'set_star' => 'is_star',
            'set_top' => 'is_top',
            'set_pcshow' => 'pc_show',
            'set_mobileshow' => 'mobile_show',
            'set_wechatshow' => 'wechat_show'
        );
        if (count($ids) > 1) {
            $data = array();
            $err = false;
            foreach ($ids as $id) {
                $article = Articles::find($id);
                $article->$relation[$action] = $value;
                if ($relation[$action] == 'pc_show') {
                    $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                    if ($pushed == 1 || $pushed == '3') {
                        $pushed = 1;
                    } else {
                        $pushed = 2;
                    }
                } else if ($relation[$action] == 'mobile_show') {
                    $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                    if ($pushed == 1 || $pushed == '2') {
                        $pushed = 1;
                    } else {
                        $pushed = 3;
                    }
                } else {
                    $pushed = 1;
                    $article->pushed = 1;
                }
                websiteInfo::where('cus_id', $cus_id)->update(['pushed' => $pushed]);
                $result = $article->save();
                if ($result) {
                    $data[] = $id;
                } else {
                    $err = true;
                }
            }
            if ($err) {
                $this->logsAdd("article",__FUNCTION__,__CLASS__,6,"批量修改文章状态:{".$relation[$action]."：".$value."},部分变更失败",0,$ids);
                $return_msg = array('err' => 3001, 'msg' => '部分变更失败', 'data' => $data);
            } else {
                $this->logsAdd("article",__FUNCTION__,__CLASS__,6,"批量修改文章状态:{".$relation[$action]."：".$value."}",0,$ids);
                $return_msg = array('err' => 0, 'msg' => '');
            }
        } else {
            $article = Articles::find($ids[0]);
            $article->$relation[$action] = $value;
            if ($relation[$action] == 'pc_show') {
                $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                if ($pushed == 1 || $pushed == '3') {
                    $pushed = 1;
                } else {
                    $pushed = 2;
                }
            } else if ($relation[$action] == 'mobile_show') {
                $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                if ($pushed == 1 || $pushed == '2') {
                    $pushed = 1;
                } else {
                    $pushed = 3;
                }
            } else {
                $pushed = 1;
                $article->pushed = 1;
            }
            websiteInfo::where('cus_id', $cus_id)->update(['pushed' => $pushed]);
            $result = $article->save();
            if ($result) {
                $this->logsAdd("article",__FUNCTION__,__CLASS__,3,"修改文章显示:{".$relation[$action]."：".$value."}",0,$ids);
                $return_msg = array('err' => 0, 'msg' => '');
            } else {
                $return_mag = array('err' => 3001, 'msg' => '变更失败');
            }
        }
        return Response::json($return_msg);
    }

    public function articleBatchAdd() {
        $cus_id = Auth::id();
        $ArticleArray = Input::get('ArticleBatch');
        $c_id = Input::get('c_id');
        $pc_show = Input::get('pc_show');
        $mobile_show = Input::get('mobile_show');
        $data = array();
        foreach ($ArticleArray as $Article) {
            $article = new Articles();
            $article->cus_id = $cus_id;
            $article->c_id = $c_id;
            $article->pc_show = $pc_show;
            $article->mobile_show = $mobile_show;
            $article->is_top = ' ';
            $article->is_star = ' ';
            $article->is_star = ' ';
            $article->sort = 1000000;
            $article->title = $Article['title'];
            $article->img = $Article['img'];
            $article->pushed = 1;
            $ret = $article->save();
            if (!$ret) {
                $return_mag = array('err' => 3001, 'msg' => '添加失败');
                return Response::json($return_msg);
            }else{
                $data[] = $ret;
            }
        }
        $this->logsAdd("article",__FUNCTION__,__CLASS__,4,"批量添加文章",0,$data);
        $return_msg = array('err' => 0, 'msg' => '');
        return Response::json($return_msg);
    }

    /**
     * 关键字搜索
     */
    public function articleWordSearch() {
        $cus_id = Auth::id();
        $keyword = Input::get('keyword');
        $data = $this->articleListData(0, 0, 15, $keyword);
//        $data['source_dir'] = asset("customers/$customer/images/s/articles") . '/';
        $return_msg = array('err' => 0, 'msg' => '', 'data' => $data);
        return Response::json($return_msg);
    }

}

?>
