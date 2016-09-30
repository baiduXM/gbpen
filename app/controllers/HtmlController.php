<?php

/**
 * @author 小余、財財
 * @package HtmlController
 * @copyright 厦门易尔通
 */

/**
 * 静态页面控制器
 *
 */
class HtmlController extends BaseController {

    /**
     * html生成进度
     * 
     * @var int
     */
    private $html_precent;

    /**
     * html生成进度
     * 
     * @var int
     */
    private $last_html_precent;

    /**
     * 推送进度
     * 
     * @var int
     */
    private $percent;

    /**
     * 上一次推送进度
     * 
     * @var int
     */
    private $lastpercent = 0;

    /**
     * 用户id
     * 
     * @var int
     */
    private $cus_id;

    /**
     * 用户名
     * 
     * @var string
     */
    private $customer;

    /**
     * 推送数据存储$pushpc $pushmobile
     * 
     * @var array
     */
    private $pushpc;
    private $pushmobile;

    /**
     * $allpush:全站推送 $pcpush:pc推送 $mobilepush:手机推送 $quickbarpush:quickbar推送 $mobilehomepagepush:手机首页推送
     * 
     * @var int
     */
    private $allpush;
    private $pcpush;
    private $mobilepush;
    private $quickbarpush;
    private $mobilehomepagepush;
    
    /**
     *$pushcid推送栏目id
     * $end是否推送quickbar等特殊内容和结束推送
     */
    private $pushcid;
    private $end;
    function __construct() {
        if(Auth::check()){
            $this->cus_id = Auth::id();
            $this->customer = Auth::user()->name;
        }
    }

    /**
     * 首页生成静态文件并返回生成文件名
     * 
     * @return string
     */
    private function homgepagehtml($type = 'pc') {
        $this->getPrecent();
        ob_start();
        if ($type == 'pc') {
            $publicdata = $this->pushpc;
        } else {
            $publicdata = $this->pushmobile;
        }
        $path = $type == 'pc' ? public_path('customers/' . $this->customer . '/index.html') : public_path('customers/' . $this->customer . '/mobile/index.html');
        $template = new PrintController('online', $type);
        if ($type == 'pc') {
            echo $template->homepagePush($publicdata);
        } else {
            echo $template->mhomepagePush($publicdata);
        }
        file_put_contents($path, $ouput = ob_get_contents());
        ob_end_clean();
        // $quickbar_json=$template->quickBarJson();
        return $path;
    }

    /**
     * 栏目页静态文件生成并返回生成文件名的数组
     * 
     * @param array $ids
     * @return multitype:string
     */
    private function categoryhtml($ids = [], $type = 'pc') {
        $result = array();
        $template = new PrintController('online', $type);
        $per_page = CustomerInfo::where('cus_id', $this->cus_id)->pluck($type . "_page_count");
        if ($type == 'pc') {
            $publicdata = $this->pushpc;
        } else {
            $publicdata = $this->pushmobile;
        }
        foreach ((array) $ids as $id) {
            $c_ids = explode(',', $template->getChirldenCid($id, 1));
            $a_c_type = Classify::where('id', $id)->pluck('type'); //取得栏目的type
            $pc_page_count_switch = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_count_switch'); //页面图文列表图文显示个数是否分开控制开关
            if (isset($pc_page_count_switch) && $pc_page_count_switch == 1 && $type == 'pc') {
                if ($a_c_type == 1) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_txt_count'); //每页文字显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where($type . '_show', '1')->count();
                    $page_count = ceil($total / $page_number);
                }
                if ($a_c_type == 3) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_imgtxt_count'); //每页图文显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where($type . '_show', '1')->count();
                    $page_count = ceil($total / $page_number);
                }
                if ($a_c_type == 2) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_img_count'); //每页图片显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where($type . '_show', '1')->count();
                    $page_count = ceil($total / $page_number);
                }
            } else {
                $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where($type . '_show', '1')->count();
                $page_count = ceil($total / $per_page);
            }
            $paths = $template->categoryPush($id, $page_count, $publicdata, $this->last_html_precent, $this->html_precent);
            $this->last_html_precent +=($this->html_precent * count($paths));
            $result = array_merge((array) $result, (array) $paths);
        }
        return $result;
    }

    /**
     * 文章页静态文件生成并返回生成文件名的数组
     * 
     * @param array $ids
     * @return string
     */
    private function articlehtml($ids = [], $type = 'pc') {
        $template = new PrintController('online', $type);
        $result = array();
        if ($type == 'pc') {
            $publicdata = $this->pushpc;
        } else {
            $publicdata = $this->pushmobile;
        }
        foreach ((array) $ids as $id) {
            if (isset($articles)) {
                unset($articles);
            }
            $articles = Articles::where($type . '_show', '1')->where('c_id', $id)->where('use_url', '0')->lists('id');
            $paths = array();
            if (count($articles)) {
                $paths = @$template->articlepush($id, $publicdata, $this->last_html_precent, $this->html_precent);
                $this->last_html_precent +=($this->html_precent * count($paths));
                $result = array_merge((array) $result, (array) $paths);
            }
        }
        return $result;
    }

    private function addDir($path, $zip, $dst = "") {
        $handle = opendir($path);
        while (($filename = readdir($handle)) !== FALSE) {
            if ($filename == '.' || $filename == '..') {
                continue;
            } else {
                if (is_dir($path . $filename)) {
                    if ($filename != 'json') {
                        $this->addDir($path . $filename . '/', $zip, $dst . $filename . '/');
                    }
                } else {
                    $zip->addFile($path . $filename, $dst . $filename);
                }
            }
        }
        closedir($handle);
    }

    /**
     * 计算生成html文件的数量
     * 
     * @param array $pc_classify_ids  pc栏目id
     * @param array $mobile_classify_ids 手机栏目id
     * @param array $pc_article_ids  pc文章id
     * @param array $mobile_article_ids 手机文章id
     * @return int
     */
    private function htmlPagecount($pc_classify_ids = [], $mobile_classify_ids = [], $pc_article_ids = [], $mobile_article_ids = []) {
        $template = new PrintController();
        $page_count = 2;
        $pc_per_page = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_count');
        foreach ((array) $pc_classify_ids as $id) {
            $c_ids = explode(',', ltrim($template->getChirldenCid($id, 1)));
            $a_c_type = Classify::where('id', $id)->pluck('type'); //取得栏目的type
            $pc_page_count_switch = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_count_switch'); //页面图文列表图文显示个数是否分开控制开关
            if (isset($pc_page_count_switch) && $pc_page_count_switch == 1 && $a_c_type <= 3) {
                if ($a_c_type == 1) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_txt_count'); //每页文字显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where('pc_show', '1')->count();
                    if ($total) {
                        $page_count += ceil($total / $page_number) + 1;
                    } else {
                        $page_count+=2;
                    }
                }
                if ($a_c_type == 3) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_imgtxt_count'); //每页图文显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where('pc_show', '1')->count();
                    if ($total) {
                        $page_count += ceil($total / $page_number) + 1;
                    } else {
                        $page_count+=2;
                    }
                }
                if ($a_c_type == 2) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_img_count'); //每页图片显示个数
                    $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where('pc_show', '1')->count();
                    if ($total) {
                        $page_count += ceil($total / $page_number) + 1;
                    } else {
                        $page_count+=2;
                    }
                }
            } else {
                $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where('pc_show', '1')->count();
                if ($total) {
                    $page_count += ceil($total / $pc_per_page) + 1;
                } else {
                    $page_count+=2;
                }
            }
        }
        $mobileper_page = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_page_count');
        if (!empty($mobile_classify_ids)) {
            foreach ((array) $mobile_classify_ids as $id) {
                $c_ids = explode(',', $template->getChirldenCid($id, 1));
                $total = Articles::whereIn('c_id', $c_ids)->where('cus_id', $this->cus_id)->where('mobile_show', '1')->count();
                if ($total) {
                    $page_count += ceil($total / $mobileper_page);
                } else {
                    $page_count++;
                }
            }
        }
        $page_count +=count($pc_article_ids);
        $page_count +=count($mobile_article_ids);
        return $page_count;
    }

    /**
     * 推送进度算法
     */
    private function getPrecent() {
        $nowpercent = $this->last_html_precent + $this->html_precent;
        if (floor($nowpercent) !== floor($this->last_html_precent)) {
            echo '<div class="prompt">'.floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
            ob_flush();
            flush();
            $this->clearpushqueue();
        }
        $this->last_html_precent +=$this->html_precent;
    }

    /**
     * 图片删除
     * 
     * 
     */
    private function delimg($v) {
        $pc_Path = public_path('customers/' . $this->customer . '/images/');

        $mobile_Path = public_path('customers/' . $this->customer . '/mobile/images/');
        $filepath = $pc_Path . '/s/' . $v['target'] . '/' . $v['img'];
        if (file_exists($filepath)) {
            @unlink($filepath);
        }
        $filepath = $pc_Path . '/l/' . $v['target'] . '/' . $v['img'];
        if (file_exists($filepath)) {
            @unlink($filepath);
        }
        $filepath = $mobile_Path . '/s/' . $v['target'] . '/' . $v['img'];
        if (file_exists($filepath)) {
            @unlink($filepath);
        }
        $filepath = $mobile_Path . '/l/' . $v['target'] . '/' . $v['img'];
        if (file_exists($filepath)) {
            @unlink($filepath);
        }
    }

    /**
     * cache_images文件夹清空
     * 
     * 
     */
    private function folderClear() {
        //删除目录下的文件：
        $dir = public_path('customers/' . $this->customer . '/cache_images/');
        if (is_dir($dir)) {
            $opendir = opendir($dir);
            while ($file = readdir($opendir)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir . "/" . $file;
                    if (is_file($fullpath)) {
                        @unlink($fullpath);
                    }
                }
            }
        }
    }

    /**
     * 手机首页推送
     * 
     * 
     */
    private function mobilehomepage_push() {
        $mindexhtml = $this->homgepagehtml('mobile');
        $customerinfo = Customer::find($this->cus_id);
        $ftp_array = explode(':', $customerinfo->ftp_address);
        $port = $customerinfo->ftp_port;
        $ftpdir = $customerinfo->ftp_dir;
        $ftp = $customerinfo->ftp;
        $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
        $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
        if (trim($ftp) == '1') {
            if ($conn) {
                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);
                if (@ftp_chdir($conn, $this->customer) == FALSE) {
                    ftp_mkdir($conn, $this->customer);
                }
                ftp_put($conn, "/" . $this->customer . "/mobile/index.html", public_path('customers/' . $this->customer . '/mobile/index.html'), FTP_ASCII);
                ftp_close($conn);
            }
        } else {
            if ($conn) {
                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                ftp_put($conn, $ftpdir . "/mobile/index.html", public_path('customers/' . $this->customer . '/mobile/index.html'), FTP_ASCII);
                ftp_close($conn);
            }
        }
    }

    /**
     * 手机推送
     * 
     * 
     */
    private function mobile_push() {
        set_time_limit(0);
        if(Input::has("pushgrad")==1){
            $pushcid = $this->pushcid;
            $end = $this->end;
        }else{
            if (Input::has("push_c_id")) {
                $pushcid = Input::get("push_c_id");
            }
            if (Input::has("end")) {
                $end = Input::get("end");
            }
        }
        $pc_classify_ids = array();
        $mobile_classify_ids = array();
        $pc_article_ids = array();
        $mobile_article_ids = array();
        if (isset($pushcid)) {
            if (!isset($end) || $end == 1) {
                $mindexhtml = $this->homgepagehtml('mobile');
                $msearchhtml = $this->sendData('mobile');
            }
            $mobile_classify_ids = array();
            $mobile_article_ids = array();
            $mobile_show = Classify::where('cus_id', $this->cus_id)->where("id", $pushcid)->pluck("mobile_show");
            if ($mobile_show) {
                $mobile_classify_ids[] = $pushcid;
                $mobile_article_ids = Articles::where('cus_id', $this->cus_id)->where('mobile_show', 1)->where("c_id", $pushcid)->lists('id');
            }
        } else {
            $mindexhtml = $this->homgepagehtml('mobile');
            $msearchhtml = $this->sendData('mobile');
            $mobile_classify_ids = Classify::where('cus_id', $this->cus_id)->where('mobile_show', 1)->lists('id');
            $mobile_article_ids = Articles::where('cus_id', $this->cus_id)->where('mobile_show', 1)->lists('id');
        }
        $count = $this->htmlPagecount($pc_classify_ids, $mobile_classify_ids, $pc_article_ids, $mobile_article_ids);
        $this->html_precent = 70 / $count;
        $mcategoryhtml = $this->categoryhtml($mobile_classify_ids, 'mobile');
        $marticlehtml = $this->articlehtml($mobile_classify_ids, 'mobile');
        $this->percent = 20 / $count;
        $path = public_path('customers/' . $this->customer . '/' . $this->customer . '.zip');
        if (file_exists($path)) {
            @unlink($path);
        }
        $zip = new ZipArchive;
        if ((!isset($end) || $end == 1) && ($zip->open($path, ZipArchive::CREATE) === TRUE)) {
            $this->lastpercent += 70 + $this->percent;
            $zip->addFile($mindexhtml, 'mobile/index.html');
            $zip->addFile($msearchhtml, 'mobile/search.html');
            $zip->addFile(public_path('customers/' . $this->customer . '/mobile/article_data.json'), 'mobile/article_data.json');
            $nowpercent = $this->percent + $this->lastpercent;
            if (floor($nowpercent) != floor($this->lastpercent)) {
                echo '<div class="prompt">'.floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                ob_flush();
                flush();
            }
            $zip->close();
        } else {
            $this->lastpercent += 70 + $this->percent;
        }
        $this->lastpercent += $this->percent;
        $this->compareZip($mcategoryhtml, 'mobile/category', $path);
        $this->compareZip($marticlehtml, 'mobile/detail', $path);
        if (90 > floor($this->lastpercent)) {
            echo '<div class="prompt">'.'90%</div><script type="text/javascript">refresh(90);</script>';
            ob_flush();
            flush();
            $this->clearpushqueue();
        }
        PushQueue::where('cus_id', $this->cus_id)->delete();
        $nextpush = PushQueue::where('push', 0)->first();
        if ($nextpush) {
            PushQueue::where('id', $nextpush->id)->update(['push' => 1]);
        }
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            if (!isset($end) || $end == 1) {
                $mobile_dir = Template::where('website_info.cus_id', $this->cus_id)->Leftjoin('website_info', 'template.id', '=', 'website_info.mobile_tpl_id')->pluck('name');
                $maim_dir = public_path("templates/$mobile_dir/");
                $this->addDir($maim_dir, $zip, 'mobile/');
            }
            $zip->close();
            //$images_dir=public_path("customers/".$this->customer."/images/");
            //$this->addDir($images_dir,$zip,'images/'); 
            $customerinfo = Customer::find($this->cus_id);
            $ftp_array = explode(':', $customerinfo->ftp_address);
            $port = $customerinfo->ftp_port;
            $ftpdir = $customerinfo->ftp_dir;
            $ftp = $customerinfo->ftp;
            $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
            $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
            $del_imgs = ImgDel::where('cus_id', $this->cus_id)->get()->toArray();
            if (trim($ftp) == '1') {
                if ($conn) {
                    ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    if (@ftp_chdir($conn, $this->customer) == FALSE) {
                        ftp_mkdir($conn, $this->customer);
                    }
                    foreach ((array) $del_imgs as $v) {
                        $this->delimg($v);
                        @ftp_delete($conn, "/" . $this->customer . '/images/l/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, "/" . $this->customer . '/images/s/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, "/" . $this->customer . '/mobile/images/l/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, "/" . $this->customer . '/mobile/images/s/' . $v['target'] . '/' . $v['img']);
                    }
                    ImgDel::where('cus_id', $this->cus_id)->delete();
                    ftp_put($conn, "/" . $this->customer . "/mobile/m_unzip.php", public_path("packages/m_unzip.php"), FTP_ASCII);
                    if(file_exists($path)){
                        ftp_put($conn, "/" . $this->customer . "/mobile/site.zip", $path, FTP_BINARY);
                    }
                    ftp_put($conn, "/" . $this->customer . "/mobile/search.php", public_path("packages/search.php"), FTP_ASCII);
                    if (@ftp_chdir($conn, "/" . $this->customer . "/mobile") == FALSE) {
                        ftp_mkdir($conn, "/" . $this->customer . "/mobile");
                    }
                    //ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    ftp_close($conn);
                }
            } else {
                if ($conn) {
                    ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    foreach ((array) $del_imgs as $v) {
                        $this->delimg($v);
                        @ftp_delete($conn, $ftpdir . '/images/l/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, $ftpdir . '/images/s/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, $ftpdir . '/mobile/images/l/' . $v['target'] . '/' . $v['img']);
                        @ftp_delete($conn, $ftpdir . '/mobile/images/s/' . $v['target'] . '/' . $v['img']);
                    }
                    ImgDel::where('cus_id', $this->cus_id)->delete();
                    ftp_put($conn, $ftpdir . "/mobile/m_unzip.php", public_path("packages/m_unzip.php"), FTP_ASCII);
                    if(file_exists($path)){
                        ftp_put($conn, $ftpdir . "/mobile/site.zip", $path, FTP_BINARY);
                    }
                    ftp_put($conn, $ftpdir . "/mobile/search.php", public_path("packages/search.php"), FTP_ASCII);
                    //ftp_put($conn,$ftpdir."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    ftp_close($conn);
                }
            }

            $this->folderClear();
            echo '<div class="prompt">'.'100%</div><script type="text/javascript">refresh(100);</script>';
            if (!isset($end) || $end == 1) {
                Classify::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                Articles::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                WebsiteConfig::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                WebsiteInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                MobileHomepage::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                CustomerInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0, 'lastpushtime' => date('Y-m-d H:i:s', time())]); //date('Y-m-d H:i:s',time())
            }
            //$pc_domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
            /**
             * pc使用本服务器自带域名推送，后期需要改进！
             */
            $weburl = Customer::where('id', $this->cus_id)->pluck('weburl');
            $suf_url = str_replace('http://c', '', $weburl);
            $cus_name = strtolower(Customer::where('id', $this->cus_id)->pluck('name'));
            if (trim($ftp) == '1') {
                $ftp_mdomain = "http://m." . $cus_name . $suf_url;
            } else {
                $ftp_mdomain = $customerinfo->mobile_domain;
            }
            @file_get_contents("$ftp_mdomain/m_unzip.php");
        } else {
            echo '打包失败';
        }
        ob_end_flush();
    }

    /**
     * quickbar推送
     * 
     * 
     */
    public function pushQuickbar() {
        $customerinfo = Customer::find($this->cus_id);
        $ftp_array = explode(':', $customerinfo->ftp_address);
        $port = $customerinfo->ftp_port;
        $ftpdir = $customerinfo->ftp_dir;
        $ftp = $customerinfo->ftp;
        $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
        $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
        $m_template = new PrintController('push', 'mobile');
        $template = new PrintController('push', 'pc');
        $template->quickBarJson();
        $m_template->quickBarJson();
        if (trim($ftp) == '1') {
            if ($conn) {
                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);
                if (@ftp_chdir($conn, $this->customer) == FALSE) {
                    ftp_mkdir($conn, $this->customer);
                }
                ftp_put($conn, "/" . $this->customer . "/quickbar.json", public_path('customers/' . $this->customer . '/quickbar.json'), FTP_ASCII);
                ftp_put($conn, "/" . $this->customer . "/mobile/quickbar.json", public_path('customers/' . $this->customer . '/mobile/quickbar.json'), FTP_ASCII);
                ftp_close($conn);
            }
        } else {
            if ($conn) {
                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);
                ftp_put($conn, $ftpdir . "/quickbar.json", public_path('customers/' . $this->customer . '/quickbar.json'), FTP_ASCII);
                ftp_put($conn, $ftpdir . "/mobile/quickbar.json", public_path('customers/' . $this->customer . '/mobile/quickbar.json'), FTP_ASCII);
                ftp_close($conn);
            }
        }
    }

    /**
     * 修改内容-- $this->allpush:全站推送 $this->pcpush:pc推送 $this->mobilepush:手机推送 $this->quickbarpush:quickbar推送 $this->mobilehomepagepush:手机首页推送
     * 
     * 
     */
    private function needpush() {
        $this->allpush = 0;
        $this->pcpush = 0;
        $this->mobilepush = 0;
        $this->quickbarpush = 0;
        $this->mobilehomepagepush = 0;
        $pc_domain = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_domain');
        $mobile_domain = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_domain');
        $pc = str_replace('http://', '', $pc_domain);
        $mobile = str_replace('http://', '', $mobile_domain);
        $this->allpush = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pushed');
        if (!$this->allpush) {
            $this->quickbarpush = WebsiteConfig::where('cus_id', $this->cus_id)->where('key', 'quickbar')->pluck('pushed');
            $webinfo_pushed = WebsiteInfo::where('cus_id', $this->cus_id)->pluck('pushed');
            $article_pushed_count = Articles::where('cus_id', $this->cus_id)->where('pushed', 1)->count();
            $classify_pushed_count = Classify::where('cus_id', $this->cus_id)->where('pushed', 1)->count();
            if ($article_pushed_count) {
                $this->pcpush = 1;
                $this->mobilepush = 1;
            }
            if ($webinfo_pushed == 1) {
                $this->pcpush = 1;
                $this->mobilepush = 1;
                $this->quickbarpush = 1;
            } elseif ($webinfo_pushed == 2) {
                $this->pcpush = 1;
                $this->quickbarpush = 1;
            } elseif ($webinfo_pushed == 3) {
                $this->mobilepush = 1;
                $this->quickbarpush = 1;
            }
            if ($classify_pushed_count) {
                $this->pcpush = 1;
                $this->mobilepush = 1;
                $this->quickbarpush = 1;
            }
            if (!$this->mobilepush) {
                $mobile_homepage_pushed_count = MobileHomepage::where('cus_id', $this->cus_id)->where('pushed', 1)->count();
                if ($mobile_homepage_pushed_count) {
                    $this->mobilehomepagepush = 1;
                }
            }
        } else {
            $this->pcpush = 1;
            $this->mobilepush = 1;
            $this->quickbarpush = 1;
        }
        if ($pc == '') {
            if ($this->allpush) {
                $this->mobilepush = 1;
                $this->quickbarpush = 1;
            }
            $this->pcpush = 0;
        }
        if ($mobile == '') {
            if ($this->allpush) {
                $this->pcpush = 1;
                $this->quickbarpush = 1;
            }
            $this->mobilepush = 0;
            $this->mobilehomepagepush = 0;
        }
    }

    /**
     * 推送初始化
     * 
     * 
     */
    private function pushinit() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        PushQueue::where('cus_id', $this->cus_id)->delete();
        PushQueue::where('pushtime', '<', time() - 60)->delete();
        $maxpushid = PushQueue::max('id');
        $pushqueuecount = PushQueue::where('push', 1)->count();
        $pushqueue = new PushQueue();
        $pushqueue->id = $maxpushid ? $maxpushid + 1 : 1;
        $pushqueue->pushtime = time();
        $pushqueue->cus_id = $this->cus_id;
        if ($pushqueuecount < 2) {
            $pushqueue->push = 1;
            $pushqueue->save();
        } else {
            $pushqueue->push = 0;
            $pushqueue->save();
            while (1) {
                sleep(3);
                echo '<div class="prompt">繁忙等待.......</div><script type="text/javascript">refresh("繁忙等待.......");</script>';
                ob_flush();
                flush();
                $push_table = PushQueue::where('cus_id', $this->cus_id)->first();
                if ($push_table->push == 1) {
                    break;
                } else {
                    $pushqueuecount = PushQueue::where('push', 1)->count();
                    if ($pushqueuecount < 2) {
                        PushQueue::where('cus_id', $this->cus_id)->update(['push' => 1]);
                        break;
                    }
                }
                $this->clearpushqueue();
            }
        }

        if ($this->quickbarpush && !$this->mobilepush) {
            $m_template = new PrintController('online', 'mobile');
            $config_str = file_get_contents(public_path('/templates/' . $m_template->themename) . '/config.ini');
            $search = "/QuickBar=(.*)/i";
            $result = preg_match($search, $config_str, $config_arr);
            if ($result) {
                if (trim($config_arr[1]) == 'custom') {
                    $this->mobilepush = 1;
                    $this->mobilehomepagepush = 0;
                }
            }
        }
        if ($this->pcpush) {
            $pc_template = new PrintController('online', 'pc');
            $this->pushpc['result'] = $pc_template->publicdata();
            $this->pushpc['navs'] = $pc_template->publicnavs();
            $dir = app_path('views/templates/' . $pc_template->themename);
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) != FALSE) {
                        if (strpos($file, '.html')) {
                            $this->pushpc['repleace'][$file] = file_get_contents($dir . '/' . $file);
                            $this->pushpc['pattern'][$file] = "#{include((\s)+)?file=[\',\"].\/" . $file . "[\',\"]}#";
                            if ($file == '_footer.html') {
                                $this->pushpc['repleace'][$file] = preg_replace('/\$navs/', '\$footer_navs', $this->pushpc['repleace'][$file]);
                            }
                        }
                    }
                }
            }
            $dir = public_path('templates/' . $pc_template->themename . '/json/');
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) != FALSE) {
                        if (strpos($file, '.json')) {
                            $this->pushpc['pagedata'][$file] = file_get_contents($dir . '/' . $file);
                        }
                    }
                }
            }
        }
        if ($this->mobilepush || $this->mobilehomepagepush) {
            $m_template = new PrintController('online', 'mobile');
            $this->pushmobile['result'] = $m_template->publicdata();
            $this->pushmobile['navs'] = $m_template->publicnavs();
            $dir = app_path('views/templates/' . $m_template->themename);
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) != FALSE) {
                        if (strpos($file, '.html')) {
                            $this->pushmobile['repleace'][$file] = file_get_contents($dir . '/' . $file);
                            $this->pushmobile['pattern'][$file] = "#{include((\s)+)?file=[\',\"].\/" . $file . "[\',\"]}#";
                        }
                    }
                }
            }
            $dir = public_path('templates/' . $m_template->themename . '/json/');
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) != FALSE) {
                        if (strpos($file, '.json')) {
                            $this->pushmobile['pagedata'][$file] = file_get_contents($dir . '/' . $file);
                        }
                    }
                }
            }
        }
    }

    private function clearpushqueue() {
        PushQueue::where('pushtime', '<', time() - 60)->delete();
        PushQueue::where('cus_id', $this->cus_id)->update(['pushtime' => time()]);
    }
    /**
     * 文件夹复制
     * @param type $source_dir 资源文件夹
     * @param type $dest_dir 目标文件夹
     */
    private function copydir($source_dir,$dest_dir){
        $dir = opendir($source_dir);
        if(!is_dir($dest_dir)){
            @mkdir($dest_dir);
        }
        while(false !== ( $file = readdir($dir)) ) {
                 if (( $file != '.' ) && ( $file != '..' )) {
                        if ( is_dir($source_dir . '/' . $file) ) {
                                $this->copydir($source_dir . '/' . $file,$dest_dir . '/' . $file);
                        }  else  {
                                copy($source_dir . '/' . $file,$dest_dir.'/'.$file);
                        }
                }
        }
    }
    /**
     * 带登录推送
     */
    public function pushLogin() {//带登录推送
        if($_SERVER["SERVER_ADDR"]=="182.61.23.43"||$_SERVER["SERVER_ADDR"]=="172.16.0.17"||1){
            if (Input::has("name")) {
                $cus_id = Customer::where("name", Input::get("name"))->pluck("id");
                if ($cus_id > 0 && Input::has("remember_token")) {
                    $user = Customer::where("remember_token", Input::get("remember_token"))->find($cus_id);
                    Auth::login($user);
                    $this->cus_id = Auth::id();
                    $this->customer = Auth::user()->name;
                    $this->push_html();
                    $pc_path = public_path('customers/' . $this->customer . '/pc.zip');
                    $mobile_path = public_path('customers/' . $this->customer . '/mobile.zip');
                    $view_dir = app_path('views/templates/');
                    $json_dir = public_path('templates/');
                    $customer_pack_path=public_path('packages/customernull.zip');
                    $customer_dir=public_path('customers/' . $this->customer);;
                    $webinfo=WebsiteInfo::where("cus_id",$this->cus_id)->first();
                    $pc_themename = Template::where("id",$webinfo->pc_tpl_id)->pluck("name");
                    $mobile_themename = Template::where("id",$webinfo->mobile_tpl_id)->pluck("name");
                    $pc_tpl=Template::where("id",$webinfo->pc_tpl_id)->first();
                    $m_tpl=Template::where("id",$webinfo->mobile_tpl_id)->first();
                    if(file_exists($customer_pack_path)){
                        $zip = new ZipArchive;
                        if ($zip->open($customer_pack_path) === TRUE)
                        {
                            $zip->extractTo($customer_dir);
                        }
                        $zip->close();
                    }
                    if($pc_tpl->push_get_date==null||$pc_tpl->push_get_date==""||$pc_tpl->push_get_date<$pc_tpl->updated_at){
                        if(file_exists($pc_path)){
                            $zip = new ZipArchive;
                            if ($zip->open($pc_path) === TRUE)
                            {
                                $zip->extractTo(public_path('customers/' . $this->customer."/temp"));
                                $zip->close();
                                $pc_config=  json_decode(file_get_contents(public_path('customers/' . $this->customer."/temp/".$pc_themename."/config.json")));
                                if($pc_tpl->push_get_date==null||$pc_tpl->push_get_date==""||$pc_tpl->push_get_date<$pc_config->push_get_date){
                                    $this->copydir(public_path('customers/' . $this->customer."/temp/".$pc_themename."/html"), $view_dir."/".$pc_themename);
                                    $this->copydir(public_path('customers/' . $this->customer."/temp/".$pc_themename."/json"), $json_dir."/".$pc_themename);
                                    if($pc_config->push_get_date>$pc_tpl->updated_at){
                                        Template::where("id",$webinfo->pc_tpl_id)->update(array("push_get_date"=>date("Y-m-d H:i:s", time())));
                                    }
                                }
                            }
                        }
                    }
                    if($m_tpl->push_get_date==null||$m_tpl->push_get_date==""||$m_tpl->push_get_date<$m_tpl->updated_at){
                        if(file_exists($mobile_path)){
                            $zip = new ZipArchive;
                            if ($zip->open($mobile_path) === TRUE)
                            {
                                $zip->extractTo(public_path('customers/' . $this->customer."/temp"));
                                $zip->close();
                                $m_config=  json_decode(file_get_contents(public_path('customers/' . $this->customer."/temp/".$mobile_themename."/config.json")));
                                if($m_tpl->push_get_date==null||$m_tpl->push_get_date==""||$m_tpl->push_get_date<$m_config->push_get_date){
                                    $this->copydir(public_path('customers/' . $this->customer."/temp/".$mobile_themename."/html"), $view_dir."/".$mobile_themename);
                                    $this->copydir(public_path('customers/' . $this->customer."/temp/".$mobile_themename."/json"), $json_dir."/".$mobile_themename);
                                    if($m_config->push_get_date>$m_tpl->updated_at){
                                        Template::where("id",$webinfo->mobile_tpl_id)->update(array("push_get_date"=>date("Y-m-d H:i:s", time())));
                                    }
                                }
                            }
                        }
                    }
//                    if(file_exists($json_path)){
//                        $zip = new ZipArchive;
//                        if ($zip->open($json_path) === TRUE)
//                        {
//                            $zip->extractTo($json_dir);
//                        }
//                        $zip->close();
//                    }
                    if(Input::has("pushgrad")==1){
                        $classify=new ClassifyController();
                        $data=$classify->classifyids();
                        $count=  count($data);
                        $num=1;
                        foreach((array)$data as $val){
                            if($num<$count){
                                $this->end=0;
                            }else{
                                $this->end=1;
                            }
                            $this->pushcid=$val;
                            $this->pushPrecent();
                            $num++;
                        }
                    }else{
                        $this->pushPrecent();
                    }
                    $this->deldir(public_path('customers/' . $this->customer));
//                    Auth::logout();
                }
            }
        }
    }
    /**
     * 
     * 推送是获取所有栏目id
     */
    public function push_classify_ids() {//推送是获取所有栏目id
        if($_SERVER["SERVER_ADDR"]=="182.61.23.43"||$_SERVER["SERVER_ADDR"]=="172.16.0.17"||1){
            if (Input::has("name")) {
                $cus_id = Customer::where("name", Input::get("name"))->pluck("id");
                if ($cus_id > 0 && Input::has("remember_token")) {
                    $user = Customer::where("remember_token", Input::get("remember_token"))->find($cus_id);
                    Auth::login($user);
                    $this->cus_id = Auth::id();
                    $this->customer = Auth::user()->name;
                    $classify=new ClassifyController();
                    $data=$classify->classifyids();
                    return Response::json($data);
                }
            }
        }
    }
    /**
     * 推送界面显示
     */
    private function push_html(){//推送界面显示
        echo '<link type="text/css" rel="stylesheet" href="/admin/css/bootstrap.css">';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<style type="text/css">
                .prompt{
                    display:none;
                }
                </style>';
        echo '<div class="progress" style="margin-top: 40px;margin-left: 40px;">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                  0%
                </div>
              </div>
            <div style="width:100%;text-align:center;"><span class="worning ing">(请勿关闭)</span></div>';
        echo '<script type="text/javascript" src="/admin/js/jquery.min.js"></script>';
        echo '<script type="text/javascript" src="/admin/js/bootstrap.js"></script>';
        echo '<script type="text/javascript" src="/admin/js/push.js"></script>';
        ob_flush();
        flush();
    }
    /**
     * 将整个文件夹压缩
     * @param type $path文件夹路径
     * @param type $zip压缩变量
     * @param type $dir存储名称
     */
    private function addFileToZip($path,$zip,$dir){//将整个文件夹压缩
        $handler=opendir($path);
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){
                if(is_dir($path."/".$filename)){
                    $this->addFileToZip($path."/".$filename, $zip,$dir."/".$filename);
                }else{
                    $zip->addFile($path."/".$filename,$dir."/".$filename);
                }
            }
        }
    }
    /**
     * 发送数据包到推送服务器，并获取登录凭证
     * @return type
     */
    public function getRemeber_token() {//发送数据包到推送服务器，并获取登录凭证
        $webinfo=WebsiteInfo::where("cus_id",$this->cus_id)->first();
        $pc_themename = Template::where("id",$webinfo->pc_tpl_id)->pluck("name");
        $mobile_themename = Template::where("id",$webinfo->mobile_tpl_id)->pluck("name");
        $pc_tpl=Template::where("id",$webinfo->pc_tpl_id)->first();
        $m_tpl=Template::where("id",$webinfo->mobile_tpl_id)->first();
        $conn = ftp_connect("172.16.0.17", "21");
        $path="gbpen/public/customers";
        if ($conn) {
            ftp_login($conn, '12t', 'Db#907$LKF');
            ftp_pasv($conn, true);
            if (ftp_nlist($conn, $path. "/" . $this->customer) === FALSE) {
                ftp_mkdir($conn, $path."/" . $this->customer);
            }
            $view_dir = app_path('views/templates/');
            $json_dir = public_path('templates/');
            if($pc_tpl->push_get_date==null||$pc_tpl->push_get_date==""||$pc_tpl->push_get_date<$pc_tpl->updated_at){
                $pc_json=array();
                $pc_json["themename"]=$pc_themename;
                $pc_json["push_get_date"]=date("Y-m-d H:i:s", time());
                file_put_contents(public_path('customers/' . $this->customer . '/pc_config.json'), json_encode($pc_json));
                $pc_path = public_path('customers/' . $this->customer . '/pc.zip');
                if (file_exists($pc_path)) {
                    @unlink($pc_path);
                }
                $pc_zip = new ZipArchive;
                if($pc_zip->open($pc_path, ZipArchive::CREATE)=== TRUE){
                    $this->addFileToZip($view_dir.$pc_themename,$pc_zip,$pc_themename."/html");
                    $this->addFileToZip($json_dir.$pc_themename,$pc_zip,$pc_themename."/json");
                    $pc_zip->addFile(public_path('customers/' . $this->customer . '/pc_config.json'),$pc_themename."/config.json");
                    $pc_zip->close();
                    ftp_put($conn, $path."/" . $this->customer."/pc.zip", $pc_path, FTP_BINARY);
                }
            }
            if($m_tpl->push_get_date==null||$m_tpl->push_get_date==""||$m_tpl->push_get_date<$m_tpl->updated_at){
                $m_json=array();
                $m_json["themename"]=$pc_themename;
                $m_json["push_get_date"]=date("Y-m-d H:i:s", time());
                file_put_contents(public_path('customers/' . $this->customer . '/m_config.json'), json_encode($m_json));
                $m_path = public_path('customers/' . $this->customer . '/mobile.zip');
                if (file_exists($m_path)) {
                    @unlink($m_path);
                }
                $m_zip = new ZipArchive;
                if($m_zip->open($m_path, ZipArchive::CREATE)=== TRUE){
                    $this->addFileToZip($view_dir.$mobile_themename,$m_zip,$mobile_themename."/html");
                    $this->addFileToZip($json_dir.$mobile_themename,$m_zip,$mobile_themename."/json");
                    $m_zip->addFile(public_path('customers/' . $this->customer . '/m_config.json'),$mobile_themename."/config.json");
                    $m_zip->close();
                    ftp_put($conn, $path."/" . $this->customer."/mobile.zip", $m_path, FTP_BINARY);
                }
            }
            ftp_close($conn);
        }
        
//        $view_dir = app_path('views/templates/');
//        $zipview = new ZipArchive;
//        $view_path = public_path('customers/' . $this->customer . '/view.zip');
//        if (file_exists($view_path)) {
//            @unlink($view_path);
//        }
//        if($zipview->open($view_path, ZipArchive::CREATE)=== TRUE){
//            $this->addFileToZip($view_dir.$pc_themename,$zipview,$pc_themename);
//            $this->addFileToZip($view_dir.$mobile_themename,$zipview,$mobile_themename);
//            $zipview->close();
//        }
//        $json_dir = public_path('templates/');
//        $zipjson = new ZipArchive;
//        $json_path = public_path('customers/' . $this->customer . '/json.zip');
//        if (file_exists($json_path)) {
//            @unlink($json_path);
//        }
//        if($zipjson->open($json_path, ZipArchive::CREATE)=== TRUE){
//            $this->addFileToZip($json_dir.$pc_themename,$zipjson,$pc_themename);
//            $this->addFileToZip($json_dir.$mobile_themename,$zipjson,$mobile_themename);
//            $zipjson->close();
//        }
//        $conn = ftp_connect("182.61.23.43", "21");
//        $path="gbpen/public/customers";
//        if ($conn) {
//            ftp_login($conn, '12t', 'Db#907$LKF');
//            ftp_pasv($conn, true);
//            if (@ftp_chdir($conn,$path. "/" . $this->customer) == FALSE) {
//                ftp_mkdir($conn, $path."/" . $this->customer);
//            }
//            if (file_exists($view_path)) {
//                ftp_put($conn, "view.zip", $view_path, FTP_BINARY);
//            }
//            ftp_put($conn, "json.zip", $json_path, FTP_BINARY);
//            ftp_close($conn);
//        }
        return Response::json(array("name" => Auth::user()->name, "remember_token" => Auth::user()->remember_token));
    }

    /**
     * 推送
     * 
     * 
     */
    public function pushPrecent() {
        set_time_limit(0);
        if(Input::has("pushgrad")==1){
            $pushcid = $this->pushcid;
            $end = $this->end;
            $this->percent=0;
            $this->lastpercent=0;
            $this->html_precent=0;
            $this->last_html_precent=0;
            $this->pcpush=0;
            $this->mobilepush=0;
            $this->quickbarpush=0;
            $this->mobilehomepagepush=0;
        }else{
            if(!Input::has("name")){
                echo '<script type="text/javascript">function refresh(str){parent.refresh(str);};</script>';
            }
            if (Input::has("push_c_id")) {
                $pushcid = Input::get("push_c_id");
            }
            if (Input::has("end")) {
                $end = Input::get("end");
            }
        }
        $have_article = Articles::where('cus_id', $this->cus_id)->count();
        if (!$have_article) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<div class="prompt">没有文章不可推送</div><script type="text/javascript">alert("没有文章不可推送");refresh("没有文章不可推送");</script>';
            ob_flush();
            flush();
            exit();
        }
        if (!isset($_GET['gradpush'])) {
            $this->needpush();
        } else {
            $pc_domain = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_domain');
            $mobile_domain = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_domain');
            $pc = str_replace('http://', '', $pc_domain);
            $mobile = str_replace('http://', '', $mobile_domain);
            if ($pc != '') {
                $this->pcpush = 1;
            }
            if ($mobile != '') {
                $this->mobilepush = 1;
            }
            $this->quickbarpush = 1;
            $this->mobilehomepagepush = 0;
        }
        echo '<div class="prompt">';
        var_dump('pcpush:' . $this->pcpush);
        var_dump('mobilepush:' . $this->mobilepush);
        var_dump('quickbarpush:' . $this->quickbarpush);
        var_dump('mobilehomepagepush:' . $this->mobilehomepagepush);
        echo '</div>';
        ob_flush();
        flush();
        $this->pushinit();
        if (!isset($end) || $end == 1) {
            if ($this->quickbarpush) {
                $this->pushQuickbar();
            }
            if (!$this->mobilepush) {
                if ($this->mobilehomepagepush) {
                    $this->mobilehomepage_push();
                }
            }
        }
        if ($this->pcpush || $this->mobilepush) {
            if (!$this->pcpush && $this->mobilepush) {
                $this->mobile_push();
                return true;
            }
            if (ob_get_level() == 0) {
                ob_start();
            }
            $pc_classify_ids = array();
            $mobile_classify_ids = array();
            $pc_article_ids = array();
            $mobile_article_ids = array();
            if ($this->mobilepush) {
                if (isset($pushcid)) {
                    if (!isset($end) || $end == 1) {
                        $mindexhtml = $this->homgepagehtml('mobile');
                        $msearchhtml = $this->sendData('mobile');
                    }
                    $mobile_classify_ids = array();
                    $mobile_article_ids = array();
                    $mobile_show = Classify::where('cus_id', $this->cus_id)->where("id", $pushcid)->pluck("mobile_show");
                    if ($mobile_show) {
                        $mobile_classify_ids[] = $pushcid;
                        $mobile_article_ids = Articles::where('cus_id', $this->cus_id)->where('mobile_show', 1)->where("c_id", $pushcid)->lists('id');
                    }
                } else {
                    $mindexhtml = $this->homgepagehtml('mobile');
                    $msearchhtml = $this->sendData('mobile');
                    $mobile_classify_ids = Classify::where('cus_id', $this->cus_id)->where('mobile_show', 1)->lists('id');
                    $mobile_article_ids = Articles::where('cus_id', $this->cus_id)->where('mobile_show', 1)->lists('id');
                }
            }
            if ($this->pcpush) {
                if (isset($pushcid)) {
                    if (!isset($end) || $end == 1) {
                        $indexhtml = $this->homgepagehtml('pc');
                        $searchhtml = $this->sendData('pc');
                    }
                    $pc_classify_ids = array();
                    $pc_article_ids = array();
                    $pc_show = Classify::where('cus_id', $this->cus_id)->where("id", $pushcid)->pluck("pc_show");
                    if ($pc_show) {
                        $pc_classify_ids[] = $pushcid;
                        $pc_article_ids = Articles::where('cus_id', $this->cus_id)->where('pc_show', 1)->where("c_id", $pushcid)->lists('id');
                    }
                } else {
                    $indexhtml = $this->homgepagehtml('pc');
                    $searchhtml = $this->sendData('pc');
                    $pc_classify_ids = Classify::where('cus_id', $this->cus_id)->where('pc_show', 1)->lists('id');
                    $pc_article_ids = Articles::where('cus_id', $this->cus_id)->where('pc_show', 1)->lists('id');
                }
            }
            $count = $this->htmlPagecount($pc_classify_ids, $mobile_classify_ids, $pc_article_ids, $mobile_article_ids);
            $this->html_precent = 70 / $count;
            if ($this->pcpush) {
                $categoryhtml = $this->categoryhtml($pc_classify_ids, 'pc');
                $articlehtml = $this->articlehtml($pc_classify_ids, 'pc');
            }
            if ($this->mobilepush) {
                $mcategoryhtml = $this->categoryhtml($mobile_classify_ids, 'mobile');
                $marticlehtml = $this->articlehtml($mobile_classify_ids, 'mobile');
            }
            $this->percent = 20 / $count;
            $path = public_path('customers/' . $this->customer . '/' . $this->customer . '.zip');
            if (file_exists($path)) {
                @unlink($path);
            }
            $zip = new ZipArchive;
            if ((!isset($end) || $end == 1) && ($zip->open($path, ZipArchive::CREATE) === TRUE)) {
                if ($this->pcpush) {
                    $zip->addFile($indexhtml, 'index.html');
                    $zip->addFile($searchhtml, 'search.html');
                    $zip->addFile(public_path('customers/' . $this->customer . '/article_data.json'), 'article_data.json');
                    $nowpercent = $this->percent + $this->lastpercent;
                    if (floor($nowpercent) != $this->lastpercent) {
                        echo '<div class="prompt">'.floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                        ob_flush();
                        flush();
                        $this->clearpushqueue();
                    }
                }
                $this->lastpercent += 70 + $this->percent;
                if ($this->mobilepush) {
                    $zip->addFile($mindexhtml, 'mobile/index.html');
                    $zip->addFile($msearchhtml, 'mobile/search.html');
                    $zip->addFile(public_path('customers/' . $this->customer . '/mobile/article_data.json'), 'mobile/article_data.json');
                    $nowpercent = $this->percent + $this->lastpercent;
                    if (floor($nowpercent) != floor($this->lastpercent)) {
                        echo '<div class="prompt">'.floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                        ob_flush();
                        flush();
                        $this->clearpushqueue();
                    }
                }
                $this->lastpercent += $this->percent;
                $zip->close();
            } else {
                $this->lastpercent += 70 + $this->percent;
            }
            if ($this->pcpush) {
                $this->compareZip($categoryhtml, 'category', $path);
                $this->compareZip($articlehtml, 'detail', $path);
            }
            if ($this->mobilepush) {
                $this->compareZip($mcategoryhtml, 'mobile/category', $path);
                $this->compareZip($marticlehtml, 'mobile/detail', $path);
            }


            if (90 > floor($this->lastpercent)) {
                echo '<div class="prompt">'.'90%</div><script type="text/javascript">refresh(90);</script>';
                ob_flush();
                flush();
                $this->clearpushqueue();
            }
            PushQueue::where('cus_id', $this->cus_id)->delete();
            $nextpush = PushQueue::where('push', 0)->first();
            if ($nextpush) {
                PushQueue::where('id', $nextpush->id)->update(['push' => 1]);
            }
            if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
                if ((!isset($end) || $end == 1) && $this->pcpush) {
                    $pc_dir = Template::where('website_info.cus_id', $this->cus_id)->Leftjoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->pluck('name');
                    $aim_dir = public_path("templates/$pc_dir/");
                    $this->addDir($aim_dir, $zip);
                }
                if ((!isset($end) || $end == 1) && $this->mobilepush) {
                    $mobile_dir = Template::where('website_info.cus_id', $this->cus_id)->Leftjoin('website_info', 'template.id', '=', 'website_info.mobile_tpl_id')->pluck('name');
                    $maim_dir = public_path("templates/$mobile_dir/");
                    $this->addDir($maim_dir, $zip, 'mobile/');
                }
                $zip->close();
                $customerinfo = Customer::find($this->cus_id);
                $ftp_array = explode(':', $customerinfo->ftp_address);
                $port = $customerinfo->ftp_port;
                $ftpdir = $customerinfo->ftp_dir;
                $ftp = $customerinfo->ftp;
                $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
                $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
                $del_imgs = ImgDel::where('cus_id', $this->cus_id)->get()->toArray();
                if (trim($ftp) == '1') {
                    if ($conn) {
                        ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                        ftp_pasv($conn, 1);
                        if (@ftp_chdir($conn, $this->customer) == FALSE) {
                            ftp_mkdir($conn, $this->customer);
                        }
                        foreach ((array) $del_imgs as $v) {
                            $this->delimg($v);
                            @ftp_delete($conn, "/" . $this->customer . '/images/l/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, "/" . $this->customer . '/images/s/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, "/" . $this->customer . '/mobile/images/l/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, "/" . $this->customer . '/mobile/images/s/' . $v['target'] . '/' . $v['img']);
                        }
                        ImgDel::where('cus_id', $this->cus_id)->delete();
                        if ($this->pcpush) {
                            @ftp_put($conn, "/" . $this->customer . "/search.php", public_path("packages/search.php"), FTP_ASCII);
                            //@ftp_put($conn,"/".$this->customer."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                        }
                        if (file_exists($path)) {
                            ftp_put($conn, "/" . $this->customer . "/site.zip", $path, FTP_BINARY);
                        }
                        ftp_put($conn, "/" . $this->customer . "/unzip.php", public_path("packages/unzip.php"), FTP_ASCII);
                        if ($this->mobilepush) {
                            ftp_put($conn, "/" . $this->customer . "/mobile/search.php", public_path("packages/search.php"), FTP_ASCII);
                            if (@ftp_chdir($conn, "/" . $this->customer . "/mobile") == FALSE) {
                                ftp_mkdir($conn, "/" . $this->customer . "/mobile");
                            }
                            //ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                        }
                        ftp_close($conn);
                    }
                } else {
                    if ($conn) {
                        ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                        ftp_pasv($conn, 1);
                        foreach ((array) $del_imgs as $v) {
                            $this->delimg($v);
                            @ftp_delete($conn, $ftpdir . '/images/l/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, $ftpdir . '/images/s/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, $ftpdir . '/mobile/images/l/' . $v['target'] . '/' . $v['img']);
                            @ftp_delete($conn, $ftpdir . '/mobile/images/s/' . $v['target'] . '/' . $v['img']);
                        }
                        ImgDel::where('cus_id', $this->cus_id)->delete();
                        if ($this->pcpush) {
                            ftp_put($conn, $ftpdir . "/search.php", public_path("packages/search.php"), FTP_ASCII);
                            //ftp_put($conn,$ftpdir."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                        }
                        ftp_put($conn, $ftpdir . "/unzip.php", public_path("packages/unzip.php"), FTP_ASCII);
                        if (file_exists($path)) {
                            ftp_put($conn, $ftpdir . "/site.zip", $path, FTP_BINARY);
                        }
                        if ($this->mobilepush) {
                            ftp_put($conn, $ftpdir . "/mobile/search.php", public_path("packages/search.php"), FTP_ASCII);
                            //ftp_put($conn,$ftpdir."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                        }
                        ftp_close($conn);
                    }
                }

                $this->folderClear();
                echo '<div class="prompt">'.'100%</div><script type="text/javascript">refresh(100);</script>';
                if (!isset($end) || $end == 1) {
                    Classify::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                    Articles::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                    WebsiteConfig::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                    WebsiteInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                    MobileHomepage::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                    CustomerInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0, 'lastpushtime' => date('Y-m-d H:i:s', time())]); //date('Y-m-d H:i:s',time())
                }
                /**
                 * pc使用本服务器自带域名推送，后期需要改进！
                 */
                $weburl = Customer::where('id', $this->cus_id)->pluck('weburl');
                $suf_url = str_replace('http://c', '', $weburl);
                $cus_name = strtolower(Customer::where('id', $this->cus_id)->pluck('name'));
                if (trim($ftp) == '1') {
                    $ftp_pcdomain = "http://" . $cus_name . $suf_url;
                } else {
                    $ftp_pcdomain = $customerinfo->pc_domain;
                }
                @file_get_contents("$ftp_pcdomain/unzip.php");
            } else {
                echo '打包失败';
            }
            ob_end_flush();
        } else {
            echo '<div class="prompt">'.'100%</div><script type="text/javascript">refresh(100);</script>';
            if (!isset($end) || $end == 1) {
                Classify::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                Articles::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->update(['pushed' => 0]);
                WebsiteConfig::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                WebsiteInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                MobileHomepage::where('cus_id', $this->cus_id)->update(['pushed' => 0]);
                CustomerInfo::where('cus_id', $this->cus_id)->update(['pushed' => 0, 'lastpushtime' => date('Y-m-d H:i:s', time())]); //date('Y-m-d H:i:s',time())
                ob_end_flush();
            }
        }
    }

    /**
     * 遍历数组并和上次生成的文件md5比较，讲md5不同的或上次生成的文件不存在的加入压缩中
     * 
     * @param array $filearray 文件数组
     * @param array $data 上次生成的文件md5数组
     * @param array $prefix 加于filearray数组文件的前缀，以使它和data的key对应匹配
     * @param string $fpath 文件路径
     * @param string $path 加入压缩的路径
     * @return array
     */
    private function compareZip($filearray = [], $fpath = '', $path = '') {
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            foreach ((array) $filearray as $file) {
                $cat_arr = explode('/', $file);
                $filename = array_pop($cat_arr);
                $zip->addFile($file, $fpath . '/' . $filename);
                $nowpercent = $this->percent + $this->lastpercent;
                if (floor($nowpercent) !== floor($this->lastpercent)) {
                    echo '<div class="prompt">'.floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                    ob_flush();
                    flush();
                }
                $this->lastpercent += $this->percent;
            }
            $zip->close();
        }
        return $data;
    }

    /**
     * 判断一个用户是否需要推送并返回修改的次数
     */
    public function isNeedPush() {
        $count = Classify::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $count += Articles::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $count +=WebsiteConfig::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $count +=WebsiteInfo::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $count +=MobileHomepage::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $count +=CustomerInfo::where('cus_id', $this->cus_id)->where('pushed', '>', 0)->count();
        $data_final = ['err' => 0, 'msg' => '', 'data' => ['cache_num' => $count]];
        return Response::json($data_final);
    }

    /**
     * 生成搜索页面
     */
    public function sendData($type = 'pc') {
        $template = new PrintController('online', $type);
        if ($type == 'pc') {
            $publicdata = $this->pushpc;
        } else {
            $publicdata = $this->pushmobile;
        }
        ob_start();
        $path = $type == 'pc' ? public_path('customers/' . $this->customer . '/search.html') : public_path('customers/' . $this->customer . '/mobile/search.html');
        echo $template->searchPush($publicdata);
        file_put_contents($path, ob_get_contents());
        ob_end_clean();
        return $path;
    }
    /**
     * 删除整个文件夹
     * @param type $dir 文件夹路径
     * @return boolean
     */
    private function deldir($dir) {
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}
