<?php

class ClassifyController extends BaseController {
    /*
      |-------------------------------------------------------------------------
      | 栏目管理控制器
      |-------------------------------------------------------------------------
      |方法：
      |
      |classifyList      栏目列表
      |classifyDelete    栏目删除
      |classifyInfo      栏目信息
      |classifyModify    栏目添加、修改
      |classifyShow      栏目显隐
      |classifySort      栏目排序
      |toTree            将数组递归为树形结构
     */

    /**
     * 栏目列表
     * @return type
     */
    public function classifyList() {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $classify = Classify::where('cus_id', $cus_id)->orderBy('sort')->orderBy('id')->get()->toArray();
        $showtypetotal = WebsiteInfo::where('website_info.cus_id', $cus_id)->LeftJoin('template', 'mobile_tpl_id', '=', 'template.id')->select('template.list1showtypetotal', 'template.list2showtypetotal', 'template.list3showtypetotal', 'template.list4showtypetotal')->first();
        if (count($classify)) {
            foreach ($classify as &$c_arr) {
                $liststr = 'list' . $c_arr['type'] . 'showtypetotal';
                $c_arr['showtypetotal'] = $showtypetotal->$liststr;
                if ($c_arr['mobile_show']) {
                    $c_arr['show'] = MobileHomepage::where('c_id', $c_arr['id'])->where('cus_id', $cus_id)->pluck('index_show');
                }
            }
        }
        $result['err'] = 0;
        $result['msg'] = '';
        $result['data'] = $this->toTree($classify);
        return Response::json($result);
    }
    /**
     * 栏目id列表
     */
    public function classifyids() {
        $cus_id=Auth::id();
        $classify = Classify::where('cus_id', $cus_id)->orderBy('sort')->orderBy('id')->lists("id");
        return Response::json($classify);
    }

    public function classifyDelete() {
        $failed = '';
        $cus_id = Auth::id();
        $id = explode(',', ltrim(Input::get('id'), ','));
        (count($id) > 1) ? $id : $id = $id[0];
        if (is_array($id)) {
            foreach ($id as $v) {
                $classify = Classify::find($v);
                $c_del_img = $classify->img;
                $ids = Articles::where('c_id', $v)->lists('id');
                $a_del_imgs = Articles::where('c_id', $v)->lists('img');
                if (count($ids)) {
                    $m_del_imgs = MoreImg::whereIn('a_id', (array) $ids)->lists('img');
                } else {
                    $m_del_imgs = array();
                }
                $del_imgs = array_merge((array) $a_del_imgs, (array) $m_del_imgs);
                $d_c_result = Classify::where('id', $v)->where('cus_id', $cus_id)->delete();
                Articles::where('c_id', $v)->where('cus_id', $cus_id)->delete();
                $this->delMobileHomepage($v); //删除手机首页配置
                if ($d_c_result) {
                    foreach ((array) $del_imgs as $val) {
                        $imgdel = new ImgDel();
                        $imgdel->mysave($val);
                    }
                    $imgdel = new ImgDel();
                    $imgdel->mysave($c_del_img, 'category');
                    $success[] = $v;
                } else {
                    $failed .= $v . ',';
                }
            }
        } else {
            $classify = Classify::find($id);
            $c_del_img = $classify->img;
            $ids = Articles::where('c_id', $id)->lists('id');
            $a_del_imgs = Articles::where('c_id', $id)->lists('img');
            if (count($ids)) {
                $m_del_imgs = MoreImg::whereIn('a_id', (array) $ids)->lists('img');
            } else {
                $m_del_imgs = array();
            }
            $del_imgs = array_merge((array) $a_del_imgs, (array) $m_del_imgs);
            $d_c_result = Classify::where('id', $id)->where('cus_id', $cus_id)->delete();
            Articles::where('c_id', $id)->where('cus_id', $cus_id)->delete();
            $this->delMobileHomepage($id);
            $this->delChildClassify($id);
            if ($d_c_result) {
                foreach ((array) $del_imgs as $val) {
                    $imgdel = new ImgDel();
                    $imgdel->mysave($val);
                }
                $imgdel = new ImgDel();
                $imgdel->mysave($c_del_img, 'category');
                $success[] = $id;
            } else {
                return Response::json(['err' => 1001, 'msg' => '栏目' . $id . '存在子目录或文章,删除失败']);
            }
        }
        if (!$failed) {
            CustomerInfo::where('cus_id', $cus_id)->update(['pushed' => 1]);
            $result = ['err' => 0, 'msg' => '', 'data' => $success];
        } else {
            $result = ['err' => 1001, 'msg' => '删除栏目失败'];
        }
        return Response::json($result);
    }

    public function classifyInfo() {
        $cus_id = Auth::id();
        $customer = Auth::user()->name;
        $id = Input::get('id');
        $classify = Classify::Where('cus_id', $cus_id)->find($id)->toArray();
        if ($classify['img'] != '') {
            $classify['img'] = asset("customers/$customer/images/l/category") . '/' . $classify['img'];
        }
        if (is_numeric($classify['page_id']) && $classify['page_id'] > 0) {
            $classify['page_content'] = Page::where('id', $classify['page_id'])->pluck('content');
        }
        $result['err'] = 0;
        $result['msg'] = '';
        $result['data'] = $classify;
        $result['data']['keywords'] = $classify['meta_keywords'];
        $result['data']['description'] = $classify['meta_description'];
        return Response::json($result);
    }

    /**
     * 栏目添加、修改
     * @return type
     */
    public function classifyModify() {
        $cus_id = Auth::id();
        $c_imgs = '';
        $is_forced = Input::get('force');
        $is_passed = true;
        $id = Input::get('id');
        if ($id != NULL) {
            $classify = Classify::find($id);
            $c_imgs = $classify->img;
            $page_id = Classify::where('cus_id', $cus_id)->where('id', $id)->pluck('page_id');
        } else {
            $classify = new Classify();
            $maxsort = Classify::where('cus_id', $cus_id)->max('sort');
            $classify->sort = ($maxsort === NULL) ? 0 : $maxsort + 1;
            $classify->cus_id = $cus_id;
            $page_id = false;
        }
        $classify->p_id = (Input::get('p_id') == "undefined") ? 0 : Input::get('p_id');
        $classify->type = Input::get('type');
        $classify->form_id = Input::get('form_id');
        if ($classify->p_id > 0) {
            if ($this->checkClassifyLevel($classify->p_id, 1, $cus_id)) {
                $p_c_info = Classify::find($classify->p_id);
                if (in_array($p_c_info->type, array(5, 6, 7, 8, 9))) {
                    $result = ['err' => 1001, 'msg' => '该类型不允许添加子栏目', 'data' => []];
                    $is_passed = false;
                } else {
                    $c_c_ids = Classify::where('p_id', $p_c_info->id)->lists('id');
                    if (!count($c_c_ids)) {
                        $a_ids = Articles::where('c_id', $p_c_info->id)->lists('id');
                        if (count($a_ids)) {
                            if (in_array($classify->type, array(4, 5, 6, 7, 8, 9))) {
                                $result = ['err' => 1001, 'msg' => '存在文章的栏目不允许添加该类型子栏目', 'data' => []];
                                $is_passed = false;
                            } else {
                                if (!$is_forced) {
                                    $result = ['err' => 1002, 'msg' => '该栏目存在文章，需转移才能创建子栏目', 'data' => []];
                                    $is_passed = false;
                                }
                            }
                        }
                    }
                }
            } else {
                $result = ['err' => 1001, 'msg' => '添加失败，超过最大限制层级', 'data' => []];
                $is_passed = false;
            }
        }

        if ($is_passed) {
            $classify->name = trim(Input::get('name'));
            $classify->en_name = trim(Input::get('en_name'));
            $images = Input::get('img'); //===新图片
            $classify->img = $images;
            if (!empty($c_imgs) && $c_imgs != 'undefined') {
                if ($c_imgs != $images) {
                    $del_img = $c_imgs;
                }
            }
            $icon = Input::get('icon');
            if (!empty($icon) && $icon != 'undefined') {
                $classify->icon = $icon;
            } else {
                $classify->icon = '';
            }
            $classify->article_type = (Input::get('article_type') != "undefined") ? Input::get('article_type') : 1;
            $classify->meta_keywords = trim(Input::get('keywords'));
            $classify->meta_description = trim(Input::get('description'));
            $classify->url = trim(Input::get('url'));
            $classify->open_page = trim(Input::get('open_page')) ? trim(Input::get('open_page')) : 1;
            $classify->pushed = 1;
            $is_show = Input::get('is_show');
            $shows = explode(',', $is_show);
            if (count($shows)) {
                $classify->pc_show = 0;
                $classify->mobile_show = 0;
                $classify->wechat_show = 0;
                $classify->footer_show = 0;
                foreach ($shows as $show) {
                    if ($show != '') {
                        $classify->$show = 1;
                    }
                }
            }
            if (Input::has('page_content') && Input::get('page_content') != 'undefined') {
                $page_content = Input::get('page_content');
                //===ueditor保存===
                $page = Page::find($page_id);
                $Capacity = new CapacityController();
                if (empty($page)) {
                    $page_file_array = '';
                } else {
                    $page_file_array = $page->file_array;
                }
                $Capacity->compare_filename($page_content, $page_file_array);
                $file_array = $Capacity->reg_ueditor_content($page_content);
                //===end===
                if ($page_id) {
                    Page::where('id', $page_id)->update(array('content' => $page_content, 'file_array' => $file_array));
                } else {
                    $page = new Page;
                    $page->c_id = 0;
                    $page->content = $page_content;
                    $page->file_array = $file_array;
                    if ($page->save()) {
                        $classify->page_id = $page->id;
                    } else {
                        return Response::json(['err' => 10001, 'msg' => '页面内容无法保存']);
                    }
                }
            }
            $size = 0;
            if ($classify->save()) {
                if (isset($del_img)) {
                    $imgdel = new ImgDel();
                    $size = $imgdel->mysave($del_img, 'category');
                }
                $data['id'] = $classify->id;
                if ($is_forced) {
                    Articles::where('cus_id', $cus_id)->whereIn('id', $a_ids)->update(array('c_id' => $classify->id));
                }

                if ($id != NULL) {
                    $result = ['err' => 0, 'msg' => '栏目修改成功' . $size, 'data' => $data];
                } else {
                    if (in_array($classify->type, array(1, 2, 3, 4, 9)) && $classify->p_id == 0) {
                        //===添加type:9万用表单===
                        $mhomepage_config = new MobileHomepage();
                        $mhomepage_config->c_id = $data['id'];
                        $mhomepage_config->index_show = 1;
                        $mhomepage_config->show_num = 0;
                        $mhomepage_config->star_only = 0;
                        $mhomepage_config->s_sort = 0;
                        $mhomepage_config->cus_id = $cus_id;
                        $mhomepage_config->save();
                    }
                    $result = ['err' => 0, 'msg' => '创建栏目成功', 'data' => $data];
                }
            } else {
                if ($id != NULL) {
                    $result = ['err' => 1001, 'msg' => '修改栏目失败', 'data' => []];
                } else {
                    $result = ['err' => 1001, 'msg' => '创建栏目失败', 'data' => []];
                }
            }
        }
        return Response::json($result);
    }

    //确认分类支持级数
    public function checkClassifyLevel($pid, $level, $cus_id) {
        if ($pid == 0 && $level < 5) {
            return true;
        } else {
            $p_id = Classify::where('cus_id', $cus_id)->where('id', $pid)->pluck('p_id');
            $level+=1;
            if ($level < 5) {
                return $this->checkClassifyLevel($p_id, $level, $cus_id);
            } else {
                return false;
            }
        }
    }

    public function classifyShow() {
        $cus_id = Auth::id();
        $id = Input::get('id');
        $operate = Input::get('operate');
        $value = Input::get('value');
        $operate_array = ['pc_show', 'mobile_show', 'wechat_show'];
        $classify = Classify::where('id', $id)->where('cus_id', $cus_id)->first();
        if (in_array($operate, $operate_array)) {
            $update = [$operate => $value];
            if ($operate == 'pc_show') {
                $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                if ($pushed == 1 || $pushed == '3') {
                    $pushed = 1;
                } else {
                    $pushed = 2;
                }
            } else if ($operate == 'mobile_show') {
                $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
                if ($pushed == 1 || $pushed == '2') {
                    $pushed = 1;
                } else {
                    $pushed = 3;
                }
            }
            websiteInfo::where('cus_id', $cus_id)->update(['pushed' => $pushed]);
            if (!$value) {
                $this->closeChildClassify($id, $update, $operate);
                if (Classify::where('id', $id)->where('cus_id', $cus_id)->update($update)) {
                    if ($operate == 'mobile_show') {
                        @MobileHomepage::where('c_id', $c_id)->where('cus_id', $cus_id)->update(['index_show' => 0]);
                    }
                    $result = ['err' => 0, 'msg' => ''];
                } else {
                    $result = ['err' => 1001, 'msg' => '栏目关闭操作失败'];
                }
            } else {
                $is_passed = true;
                if ($classify->p_id != 0) {
                    $p_c_info = Classify::where('id', $classify->p_id)->first();
                    if ($p_c_info->$operate == 0) {
                        $result = ['err' => 1001, 'msg' => '父级栏目未开启,栏目开启失败'];
                        $is_passed = false;
                    }
                }
                if ($is_passed) {
                    if (Classify::where('id', $id)->where('cus_id', $cus_id)->update($update)) {
                        $result = ['err' => 0, 'msg' => ''];
                    } else {
                        $result = ['err' => 1001, 'msg' => '栏目开启操作失败'];
                    }
                }
            }
        } else {
            $result = ['err' => 1001, 'msg' => '栏目显隐控制错误操作'];
        }
        return Response::json($result);
    }

    //关闭子栏目显示
    private function closeChildClassify($id, $update, $operate) {
        $cus_id = Auth::id();
        $c_cids = Classify::where('p_id', $id)->lists('id');
        if (count($c_cids)) {
            foreach ($c_cids as $c_id) {
                $this->closeChildClassify($c_id, $update, $operate);
                if (Classify::where('id', $c_id)->where('cus_id', $cus_id)->update($update)) {
                    if ($operate == 'mobile_show') {
                        @MobileHomepage::where('c_id', $c_id)->where('cus_id', $cus_id)->update(['index_show' => 0]);
                    }
                }
            }
        }
    }

    public function classifySort() {
        $cus_id = Auth::id();
        $indexlist = Input::get('indexlist');
        foreach ($indexlist as $key => $value) {
            $classify = Classify::find($value['id']); //dd($classify);
            $classify->sort = $value['index'];
            $classify->pushed = 1;
            $classify->save();
        }
        $result = ['err' => 0, 'msg' => ''];
        return Response::json($result);
    }

    public function classifyupload() {
        $customer = Auth::user()->name;
        $script_url = route('classify-upload');
        $upload_dir = public_path('customers/' . $customer . '/images/');
        $upload_url = asset('customers/' . $customer . '/images/') . '/';
        $upload = new UploadHandler(['script_url' => $script_url, 'upload_dir' => $upload_dir, 'upload_url' => $upload_url, 'max_number_of_files' => 1]);
    }

    private function toTree($arr, $pid = 0) {
        $tree = array();
        foreach ($arr as $k => $v) {
            if ($v['p_id'] == $pid) {
                $tree[] = $v;
            }
        }
        if (empty($tree)) {
            return null;
        }
        foreach ($tree as $k => $v) {
            $tree[$k]['childmenu'] = $this->toTree($arr, $v['id']);
        }
        return $tree;
    }

    //删除手机首页配置
    private function delMobileHomepage($c_id) {
        $cus_id = Auth::id();
        @MobileHomepage::where('cus_id', $cus_id)->where('c_id', $c_id)->delete();
    }

	//删除分类及其子类
	private function delChildClassify($c_id) {
		$cus_id = Auth::id();
		$del_imgs = array();
		$child_ids = Classify::where('p_id', $c_id)->where('cus_id', $cus_id)->lists('id');
		if (count($child_ids)) {
			foreach ($child_ids as $id) {
				$classify = Classify::find($id);
				$c_del_img = $classify->img;
				$ids = Articles::where('c_id', $id)->lists('id');
				$a_del_imgs = Articles::where('c_id', $id)->lists('img');
				if (count($ids)) {
					$m_del_imgs = MoreImg::whereIn('a_id', (array) $ids)->lists('img');
				} else {
					$m_del_imgs = array();
				}
				$del_imgs = array_merge((array) $a_del_imgs, (array) $m_del_imgs);
				Classify::where('id', $id)->where('cus_id', $cus_id)->delete();
				Articles::where('c_id', $id)->where('cus_id', $cus_id)->delete();
				foreach ((array) $del_imgs as $val) {
					$imgdel = new ImgDel();
					$imgdel->mysave($val);
				}
				$imgdel = new ImgDel();
				$imgdel->mysave($c_del_img, 'category');
				$this->delMobileHomepage($id);
				$this->delChildClassify($id);
			}
		}
	}
        public function classifyNameModify() {
		$cus_id = Auth::id();
		$id = Input::get('id');
                $name = Input::get('name');
		$result = Classify::where('id', $id)->where('cus_id', $cus_id)->update(['name' => $name,'pushed'=>1]);
		if ($result) {
			return Response::json(['err' => 0, 'msg' => '修改成功']);
		} else {
			return Response::json(['err' => 3001, 'msg' => '修改失败']);
		}
	}

}
