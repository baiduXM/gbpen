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
class HtmlController1 extends BaseController{
    
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
    private $lastpercent=0;
    
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
    private $start;
    private $end;
    private $pushpc;
    private $pushmobile;
            
    
    function __construct(){
        $this->cus_id = Auth::id();
        $this->customer = Auth::user()->name;
    }
    
    /**
     * 首页生成静态文件并返回生成文件名
     * 
     * @return string
     */
    private function homgepagehtml($type ='pc'){
        $this->getPrecent();
        ob_start();
        if($type =='pc'){
            $publicdata=$this->pushpc;
        }else{
            $publicdata=$this->pushmobile;
        }
        $path = $type =='pc' ? public_path('customers/'.$this->customer.'/index.html') : public_path('customers/'.$this->customer.'/mobile/index.html');
        $template = new PrintController('online',$type);
        if($type =='pc'){
            echo $template->homepagePush($publicdata);
        }
        else{
            echo $template->mhomepagePush($publicdata);
        }
        file_put_contents($path, $ouput=ob_get_contents());
        ob_end_clean();
        $quickbar_json=$template->quickBarJson();
        return $path;
    }
    
    /**
     * 栏目页静态文件生成并返回生成文件名的数组
     * 
     * @param array $ids
     * @return multitype:string
     */

    private function categoryhtml($ids=[],$type ='pc'){
        $result = array();
        $template = new PrintController('online',$type);
        $per_page = CustomerInfo::where('cus_id',$this->cus_id)->pluck($type."_page_count");
        if($type =='pc'){
            $publicdata=$this->pushpc;
        }else{
            $publicdata=$this->pushmobile;
        }
        foreach((array)$ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $a_c_type = Classify::where('id',$id)->pluck('type');//取得栏目的type
            $pc_page_count_switch = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count_switch');//页面图文列表图文显示个数是否分开控制开关
            if(isset($pc_page_count_switch)&&$pc_page_count_switch==1&&$type=='pc'){
                if($a_c_type==1){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_txt_count');//每页文字显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where($type.'_show','1')->count();
                    $page_count = ceil($total/$page_number);
                }
                if($a_c_type==3){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_imgtxt_count');//每页图文显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where($type.'_show','1')->count();
                    $page_count = ceil($total/$page_number);
                }
                if($a_c_type==2){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_img_count');//每页图片显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where($type.'_show','1')->count();
                    $page_count = ceil($total/$page_number);
                }
            }else{
                $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where($type.'_show','1')->count();
                $page_count = ceil($total/$per_page);
            }
            $paths=$template->categoryPush($id,$page_count,$publicdata,$this->last_html_precent,$this->html_precent);
            $this->last_html_precent +=($this->html_precent*count($paths));
            $result=array_merge((array)$result,(array)$paths);
        }
        return $result;
    }
    
    /**
     * 文章页静态文件生成并返回生成文件名的数组
     * 
     * @param array $ids
     * @return string
     */
    private function articlehtml($ids=[],$type ='pc'){
        $template = new PrintController('online',$type);
        $result =array();
        if($type =='pc'){
            $publicdata=$this->pushpc;
        }else{
            $publicdata=$this->pushmobile;
        }
        foreach((array)$ids as $id){
            if(isset($articles)){
                unset($articles);
            }
            $articles = Articles::where($type . '_show', '1')->where('c_id', $id)->where('use_url', '0')->lists('id');
            $paths=array();
            if(count($articles)){
                $paths=@$template->articlepush($id,$publicdata,$this->last_html_precent,$this->html_precent);
                $this->last_html_precent +=($this->html_precent*count($paths));
                $result=array_merge((array)$result,(array)$paths);
            }
        }
        return $result;
    }

    private function addDir($path,$zip,$dst=""){
		$handle=opendir($path);
		while(($filename=readdir($handle))!==FALSE){
			if($filename=='.' || $filename=='..'){
				continue;
			}else{
				if(is_dir($path.$filename)){
                    if($filename!='json'){
                        $this->addDir($path.$filename.'/',$zip,$dst.$filename.'/');
                    }
                }else{
                    $zip->addFile($path.$filename,$dst.$filename);   
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
    private function htmlPagecount($pc_classify_ids=[],$mobile_classify_ids=[],$pc_article_ids=[],$mobile_article_ids=[]){
         $template = new PrintController();
        $page_count = 2;
        $pc_per_page = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count');
        foreach((array)$pc_classify_ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $a_c_type = Classify::where('id',$id)->pluck('type');//取得栏目的type
            $pc_page_count_switch = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count_switch');//页面图文列表图文显示个数是否分开控制开关
            if(isset($pc_page_count_switch)&&$pc_page_count_switch==1&&$a_c_type<=3){
                if($a_c_type==1){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_txt_count');//每页文字显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where('pc_show','1')->count();
                    if($total){
                        $page_count += ceil($total/$page_number)+1;
                    }
                    else{
                        $page_count+=2;
                    }
                }
                if($a_c_type==3){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_imgtxt_count');//每页图文显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where('pc_show','1')->count();
                    if($total){
                        $page_count += ceil($total/$page_number)+1;
                    }
                    else{
                        $page_count+=2;
                    }
                }
                if($a_c_type==2){
                    $page_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_img_count');//每页图片显示个数
                    $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where('pc_show','1')->count();
                    if($total){
                        $page_count += ceil($total/$page_number)+1;
                    }
                    else{
                        $page_count+=2;
                    }
                }
            }else{
                $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where('pc_show','1')->count();
                if($total){
                    $page_count += ceil($total/$pc_per_page)+1;
                }
                else{
                    $page_count+=2;
                }
            }

        }
        $mobileper_page = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_page_count');
        foreach((array)$mobile_classify_ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $total = Articles::whereIn('c_id',$c_ids)->where('cus_id',$this->cus_id)->where('mobile_show','1')->count();
            if($total){
                $page_count += ceil($total/$mobileper_page);
            }
            else{
                $page_count++;
            }
            
        }
        $page_count +=count($pc_article_ids);
        $page_count +=count($mobile_article_ids);
        return $page_count;
    }
    
    
    /**
     * 推送进度算法
     */
    private function getPrecent(){
        $nowpercent = $this->last_html_precent+$this->html_precent;
        if(floor($nowpercent)!==floor($this->last_html_precent)){
            echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
            ob_flush();
            flush();
        }
        $this->last_html_precent +=$this->html_precent;
    }
    /**
     * 图片删除
     * 
     * 
     */
    private function delimg($v){
        $pc_Path = public_path('customers/'.$this->customer.'/images/');
        $mobile_Path=public_path('customers/'.$this->customer.'/mobile/images/');
        $filepath=$pc_Path.'/s/'.$v['target'].'/'.$v['img'];
        if(file_exists($filepath)){
            @unlink($filepath);
        }
        $filepath=$pc_Path.'/l/'.$v['target'].'/'.$v['img'];
        if(file_exists($filepath)){
            @unlink($filepath);
        }
        $filepath=$mobile_Path.'/s/'.$v['target'].'/'.$v['img'];
        if(file_exists($filepath)){
            @unlink($filepath);
        }
        $filepath=$mobile_Path.'/l/'.$v['target'].'/'.$v['img'];
        if(file_exists($filepath)){
            @unlink($filepath);
        }
    }
    /**
     * cache_images文件夹清空
     * 
     * 
     */
    private function folderClear(){
        //删除目录下的文件：
            $dir=public_path('customers/'.$this->customer.'/cache_images/');
            if(is_dir($dir)){
                $opendir=opendir($dir);
                while ($file=readdir($opendir)) {
                  if($file!="." && $file!="..") {
                    $fullpath=$dir."/".$file;
                    if(is_file($fullpath)) {
                        @unlink($fullpath);
                    }
                  }
                }
            }
    }
    /**
     * 手机推送
     * 
     * 
     */
    private function mobile_push(){
        set_time_limit(0);
        if (ob_get_level() == 0){
            ob_start();
        }
        $customer_data_get = CustomerPushfile::where('cus_id',$this->cus_id)->pluck('files');
        if($customer_data_get){
            $new_data = 0;
            $customer_data = unserialize($customer_data_get);
        }
        else{
            $new_data = 1;
            $customer_data = [];
        }
        $pc_classify_ids=array();
        $mobile_classify_ids=array();
        $pc_article_ids=array();
        $mobile_article_ids=array();
        $mindexhtml = $this->homgepagehtml('mobile');
        $msearchhtml = $this->sendData('mobile');
        $mobile_classify_ids = Classify::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
        $mobile_article_ids = Articles::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
        $count = $this->htmlPagecount($pc_classify_ids,$mobile_classify_ids,$pc_article_ids,$mobile_article_ids);
        $this->html_precent= 70/$count;
        $mcategoryhtml = $this->categoryhtml($mobile_classify_ids,'mobile');
        $marticlehtml = $this->articlehtml($mobile_classify_ids,'mobile');
        $this->percent = 20/$count;
        $path = public_path('customers/'.$this->customer.'/'.$this->customer.'.zip');
        if(file_exists($path)){
            @unlink($path);
        }
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $this->lastpercent += 70+$this->percent;
            $zip->addFile($mindexhtml,'mobile/index.html');
            $zip->addFile($msearchhtml,'mobile/search.html');
            $zip->addFile(public_path('customers/'.$this->customer.'/mobile/article_data.json'),'mobile/article_data.json');
            $nowpercent = $this->percent + $this->lastpercent;
            if(floor($nowpercent)!=floor($this->lastpercent)){
                echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
                ob_flush();
                flush();
            }
        }
        $this->lastpercent += $this->percent;
        $zip->close();
        $this->compareZip($mcategoryhtml,$customer_data,'m','mobile/category',$path);
        $this->compareZip($marticlehtml,$customer_data,'mf','mobile/detail',$path);
        if(90 > floor($this->lastpercent)) {
            echo '90%<script type="text/javascript">parent.refresh(90);</script><br />';
            ob_flush();
            flush();
        }
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $mobile_dir=  Template::where('website_info.cus_id',$this->cus_id)->Leftjoin('website_info','template.id','=','website_info.mobile_tpl_id')->pluck('name');
            $maim_dir=public_path("templates/$mobile_dir/");
            $this->addDir($maim_dir,$zip,'mobile/');
            //$images_dir=public_path("customers/".$this->customer."/images/");
            //$this->addDir($images_dir,$zip,'images/'); 
            $zip->close();
            $data = serialize($customer_data);
            if($new_data){
                $customerpushfile = new CustomerPushfile;
                $customerpushfile->cus_id = $this->cus_id;
                $customerpushfile->files = $data;
                $customerpushfile->save();
            }
            else{
                CustomerPushfile::where('cus_id',$this->cus_id)->update(['files'=>$data]);
            }
            $customerinfo = Customer::find($this->cus_id);
            $ftp_array = explode(':',$customerinfo->ftp_address);
            $port= $customerinfo->ftp_port;
            $ftpdir=$customerinfo->ftp_dir;
            $ftp=$customerinfo->ftp;
            $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:$port;
            $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
            $del_imgs=ImgDel::where('cus_id',$this->cus_id)->get()->toArray();
            if(trim($ftp)=='1'){
                if($conn){
                    ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    if(@ftp_chdir($conn,$this->customer) == FALSE){
                    ftp_mkdir($conn,$this->customer);  
                    }
                    foreach((array)$del_imgs as $v){
                        $this->delimg($v);
                        @ftp_delete($conn,"/".$this->customer.'/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/images/s/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/mobile/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/mobile/images/s/'.$v['target'].'/'.$v['img']);
                    }
                    ImgDel::where('cus_id',$this->cus_id)->delete();
                    ftp_put($conn,$ftpdir."/mobile/m_unzip.php",public_path("packages/m_unzip.php"),FTP_ASCII);
                    ftp_put($conn,$ftpdir."/mobile/site.zip",$path,FTP_BINARY);
                    ftp_put($conn,"/".$this->customer."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                    if(@ftp_chdir($conn,"/".$this->customer."/mobile") == FALSE){
                        ftp_mkdir($conn,"/".$this->customer."/mobile"); 
                    }
                    ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    ftp_close($conn);
                }
            }else{
                if($conn){
                    ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    foreach((array)$del_imgs as $v){
                        $this->delimg($v);
                        @ftp_delete($conn,$ftpdir.'/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/images/s/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/mobile/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/mobile/images/s/'.$v['target'].'/'.$v['img']);
                    }
                    ImgDel::where('cus_id',$this->cus_id)->delete();
                    ftp_put($conn,$ftpdir."/mobile/m_unzip.php",public_path("packages/m_unzip.php"),FTP_ASCII);
                    ftp_put($conn,$ftpdir."/mobile/site.zip",$path,FTP_BINARY);
                    ftp_put($conn,$ftpdir."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                    ftp_put($conn,$ftpdir."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    ftp_close($conn);
                }
            }
           
            $this->folderClear();
            echo '100%<script type="text/javascript">parent.refresh(100);</script><br />';
            Classify::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            Articles::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            CustomerInfo::where('cus_id', $this->cus_id)->where('pushed', 1)->update(['pushed' => 0]);
            //$pc_domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
             /**
            * pc使用本服务器自带域名推送，后期需要改进！
            */
            $weburl=Customer::where('id',$this->cus_id)->pluck('weburl');
            $suf_url=str_replace('http://c', '', $weburl);
            $cus_name =strtolower( Customer::where('id',$this->cus_id)->pluck('name'));
            if(trim($ftp)=='1'){
                $ftp_mdomain="http://m.".$cus_name.$suf_url;
            }
            else{
                $ftp_mdomain=$customerinfo->mobile_domain;
            }
            @file_get_contents("$ftp_mdomain/m_unzip.php");
        } 
        else {
            echo '打包失败';
        }
        ob_end_flush();
    }
     /**
     * quickbar推送
     * 
     * 
     */
    public function pushQuickbar(){
        $customerinfo = Customer::find($this->cus_id);
        $ftp_array = explode(':',$customerinfo->ftp_address);
        $port= $customerinfo->ftp_port;
        $ftpdir=$customerinfo->ftp_dir;
        $ftp=$customerinfo->ftp;
        $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:$port;
        $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
        $template = new PrintController('push', 'mobile');
        $m_template = new PrintController('push', 'pc');
        $template->quickBarJson();
        $m_template->quickBarJson();
        if(trim($ftp)=='1'){
            if($conn){
                ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    if(@ftp_chdir($conn,$this->customer) == FALSE){
                        ftp_mkdir($conn,$this->customer);  
                    }
                ftp_put($conn,"/".$this->customer."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
            }
        }
    }
    /**
     * 推送初始化
     * 
     * 
     */
    private function pushinit(){
        $pc_template = new PrintController('online','pc');
        $this->pushpc['result']=$pc_template->publicdata();
        $this->pushpc['navs']=$pc_template->publicnavs();
        $m_template = new PrintController('online','mobile');
        $this->pushmobile['result']=$m_template->publicdata();
        $this->pushmobile['navs']=$m_template->publicnavs();
        $dir=app_path('views/templates/' . $pc_template->themename);
        if(is_dir($dir)){
            if ($dh = opendir($dir)){
                while(($file=readdir($dh))!=FALSE){
                    if(strpos($file,'.html')){
                        $this->pushpc['repleace'][$file]=file_get_contents($dir.'/'.$file);
                        $this->pushpc['pattern'][$file]="#{include((\s)+)?file=[\',\"].\/".$file."[\',\"]}#";
                        if($file=='_footer.html'){
                            $this->pushpc['repleace'][$file]=preg_replace('/\$navs/', '\$footer_navs', $this->pushpc['repleace'][$file]).'{$enlargeprint}';
                        }
                    }
                }
            }
        }
        $dir=app_path('views/templates/' . $m_template->themename);
        if(is_dir($dir)){
            if ($dh = opendir($dir)){
                while(($file=readdir($dh))!=FALSE){
                    if(strpos($file,'.html')){
                        $this->pushmobile['repleace'][$file]=file_get_contents($dir.'/'.$file);
                        $this->pushmobile['pattern'][$file]="#{include((\s)+)?file=[\',\"].\/".$file."[\',\"]}#";
                    }
                }
            }
        }
    }
    /**
     * 推送
     * 
     * 
     */
    public function pushPrecent(){
        set_time_limit(0);
        $this->pushinit();
        $pc_domain=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
        $mobile_domain=CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_domain');
        $pc=str_replace('http://', '', $pc_domain);
        $mobile=str_replace('http://', '', $mobile_domain);
        if($pc==''&&$mobile!=''){
            $this->mobile_push();
            exit();
        }
        if (ob_get_level() == 0){
            ob_start();
        }
        $customer_data_get = CustomerPushfile::where('cus_id',$this->cus_id)->pluck('files');
        if($customer_data_get){
            $new_data = 0;
            $customer_data = unserialize($customer_data_get);
        }
        else{
            $new_data = 1;
            $customer_data = [];
        }
        $pc_classify_ids=array();
        $mobile_classify_ids=array();
        $pc_article_ids=array();
        $mobile_article_ids=array();
        if(isset($_GET['sleep'])){
            sleep($_GET['sleep']);
        }
        if($mobile!=''){
            $mindexhtml = $this->homgepagehtml('mobile');
            $msearchhtml = $this->sendData('mobile');
            $mobile_classify_ids = Classify::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
            $mobile_article_ids = Articles::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
        }
        if(isset($_GET['sleep'])){
            sleep($_GET['sleep']);
        }
        if($pc!=''){
            $indexhtml = $this->homgepagehtml('pc');
            $searchhtml = $this->sendData('pc');
            $pc_classify_ids = Classify::where('cus_id',$this->cus_id)->where('pc_show',1)->lists('id');
            $pc_article_ids = Articles::where('cus_id',$this->cus_id)->where('pc_show',1)->lists('id');
        }
        if(isset($_GET['sleep'])){
            sleep($_GET['sleep']);
        }
        $count = $this->htmlPagecount($pc_classify_ids,$mobile_classify_ids,$pc_article_ids,$mobile_article_ids);
        $this->html_precent= 70/$count;
        if($pc!=''){
            $categoryhtml = $this->categoryhtml($pc_classify_ids,'pc');
            $articlehtml = $this->articlehtml($pc_classify_ids,'pc');
        }
        if($mobile!=''){
            $mcategoryhtml = $this->categoryhtml($mobile_classify_ids,'mobile');
            $marticlehtml = $this->articlehtml($mobile_classify_ids,'mobile');
        }
        $this->percent = 20/$count;
        $path = public_path('customers/'.$this->customer.'/'.$this->customer.'.zip');
        if(file_exists($path)){
            @unlink($path);
        }
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            if($pc!=''){
                $zip->addFile($indexhtml,'index.html');
                $zip->addFile($searchhtml,'search.html');
                $zip->addFile(public_path('customers/'.$this->customer.'/article_data.json'),'article_data.json');
                $nowpercent = $this->percent + $this->lastpercent;
                if(floor($nowpercent)!=$this->lastpercent){
                    echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
                    ob_flush();
                    flush();
                }
            }
            $this->lastpercent += 70+$this->percent;
            if($mobile!=''){
                $zip->addFile($mindexhtml,'mobile/index.html');
                $zip->addFile($msearchhtml,'mobile/search.html');
                $zip->addFile(public_path('customers/'.$this->customer.'/mobile/article_data.json'),'mobile/article_data.json');
                $nowpercent = $this->percent + $this->lastpercent;
                if(floor($nowpercent)!=floor($this->lastpercent)){
                    echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
                    ob_flush();
                    flush();
                }
            }
            $this->lastpercent += $this->percent;
            $zip->close();
        }
            if($pc!=''){
                $this->compareZip($categoryhtml,$customer_data,'p','category',$path);
                $this->compareZip($articlehtml,$customer_data,'pf','detail',$path);
            }
            if($mobile!=''){
                $this->compareZip($mcategoryhtml,$customer_data,'m','mobile/category',$path);
                $this->compareZip($marticlehtml,$customer_data,'mf','mobile/detail',$path);
            }
             
  
            if(90 > floor($this->lastpercent)) {
                echo '90%<script type="text/javascript">parent.refresh(90);</script><br />';
                ob_flush();
                flush();
            }
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            if($pc!=''){
                $pc_dir=  Template::where('website_info.cus_id',$this->cus_id)->Leftjoin('website_info','website_info.pc_tpl_id','=','template.id')->pluck('name');
                $aim_dir=public_path("templates/$pc_dir/");
                $this->addDir($aim_dir,$zip);
            }
            if($mobile!=''){
                $mobile_dir=  Template::where('website_info.cus_id',$this->cus_id)->Leftjoin('website_info','template.id','=','website_info.mobile_tpl_id')->pluck('name');
                $maim_dir=public_path("templates/$mobile_dir/");
                $this->addDir($maim_dir,$zip,'mobile/');
            }
            $zip->close();
            $data = serialize($customer_data);
            if($new_data){
                $customerpushfile = new CustomerPushfile;
                $customerpushfile->cus_id = $this->cus_id;
                $customerpushfile->files = $data;
                $customerpushfile->save();
            }
            else{
                CustomerPushfile::where('cus_id',$this->cus_id)->update(['files'=>$data]);
            }
            $customerinfo = Customer::find($this->cus_id);
            $ftp_array = explode(':',$customerinfo->ftp_address);
            $port= $customerinfo->ftp_port;
            $ftpdir=$customerinfo->ftp_dir;
            $ftp=$customerinfo->ftp;
            $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:$port;
            $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
            $del_imgs=ImgDel::where('cus_id',$this->cus_id)->get()->toArray();
            if(trim($ftp)=='1'){
                if($conn){
                    ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    if(@ftp_chdir($conn,$this->customer) == FALSE){
                        ftp_mkdir($conn,$this->customer);  
                    }
                    foreach((array)$del_imgs as $v){
                        $this->delimg($v);
                        @ftp_delete($conn,"/".$this->customer.'/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/images/s/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/mobile/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,"/".$this->customer.'/mobile/images/s/'.$v['target'].'/'.$v['img']);
                    }
                    ImgDel::where('cus_id',$this->cus_id)->delete();
                    if($pc!=''){
                        @ftp_put($conn,"/".$this->customer."/search.php",public_path("packages/search.php"),FTP_ASCII);
                        if(isset($_GET['test'])){
                            var_dump(public_path('customers/'.$this->customer.'/quickbar.json'));
                            if(file_exists(public_path('customers/'.$this->customer.'/quickbar.json'))){
                                var_dump(1);
                            }
                            ob_flush();
                            flush();
                        }
                        @ftp_put($conn,"/".$this->customer."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                    }
                    ftp_put($conn,"/".$this->customer."/site.zip",$path,FTP_BINARY);
                    ftp_put($conn,"/".$this->customer."/unzip.php",public_path("packages/unzip.php"),FTP_ASCII);
                    if($mobile!=''){
                        ftp_put($conn,"/".$this->customer."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                        if(@ftp_chdir($conn,"/".$this->customer."/mobile") == FALSE){
                            ftp_mkdir($conn,"/".$this->customer."/mobile"); 
                        }
                        ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    }
                    ftp_close($conn);
                }
            }else{
                if($conn){
                    ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    foreach((array)$del_imgs as $v){
                        $this->delimg($v);
                        @ftp_delete($conn,$ftpdir.'/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/images/s/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/mobile/images/l/'.$v['target'].'/'.$v['img']);
                        @ftp_delete($conn,$ftpdir.'/mobile/images/s/'.$v['target'].'/'.$v['img']);
                    }
                    ImgDel::where('cus_id',$this->cus_id)->delete();
                    if($pc!=''){
                        ftp_put($conn,$ftpdir."/search.php",public_path("packages/search.php"),FTP_ASCII);
                        ftp_put($conn,$ftpdir."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                    }
                    ftp_put($conn,$ftpdir."/unzip.php",public_path("packages/unzip.php"),FTP_ASCII);
                    ftp_put($conn,$ftpdir."/site.zip",$path,FTP_BINARY);
                    if($mobile!=''){
                        ftp_put($conn,$ftpdir."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                        ftp_put($conn,$ftpdir."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                    }
                    ftp_close($conn);
                }
            }
           
            $this->folderClear();
            echo '100%<script type="text/javascript">parent.refresh(100);</script><br />';
            Classify::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            Articles::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            CustomerInfo::where('cus_id', $this->cus_id)->where('pushed', 1)->update(['pushed' => 0]);
             /**
            * pc使用本服务器自带域名推送，后期需要改进！
            */
            $weburl=Customer::where('id',$this->cus_id)->pluck('weburl');
            $suf_url=str_replace('http://c', '', $weburl);
            $cus_name =strtolower( Customer::where('id',$this->cus_id)->pluck('name'));
            if(trim($ftp)=='1'){
                $ftp_pcdomain="http://".$cus_name.$suf_url;
            }
            else{
                $ftp_pcdomain=$customerinfo->pc_domain;
            }
            @file_get_contents("$ftp_pcdomain/unzip.php");
        } 
        else {
            echo '打包失败';
        }
        ob_end_flush();
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
    private function compareZip($filearray=[],$data=[],$prefix='',$fpath='',$path=''){
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            foreach((array)$filearray as $file)
            {
                $cat_arr = explode('/',$file);
                $filename = array_pop($cat_arr);
                $zip->addFile($file,$fpath.'/'.$filename);
                /*
                $md5 = md5_file($file);
                if(array_key_exists($prefix.$filename,$data))
                {
                   // //if($data[$prefix.$filename] != $md5)
                    {
                        $data[$prefix.$filename] = $md5;
                        $zip->addFile($file,$fpath.'/'.$filename);
                    }                   
                }
                else{
                    $data[$prefix.$filename] = $md5;
                    $zip->addFile($file,$fpath.'/'.$filename);
                }
                 */
                $nowpercent = $this->percent + $this->lastpercent;
                if (floor($nowpercent) !==floor($this->lastpercent)) {
                     echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
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
    public function isNeedPush(){
        $count = Classify::where('cus_id',$this->cus_id)->where('pushed',1)->count();
        $count += Articles::where('cus_id',$this->cus_id)->where('pushed',1)->count();
        $data_final = ['err'=>0,'msg'=>'','data'=>['cache_num'=>$count]];
        return Response::json($data_final);
    }
    
    /**
    * 生成搜索页面
     */
    public function sendData($type='pc'){
        $template = new PrintController('online',$type);
        if($type =='pc'){
            $publicdata=$this->pushpc;
        }else{
            $publicdata=$this->pushmobile;
        }
        ob_start();
        $path = $type =='pc' ? public_path('customers/'.$this->customer.'/search.html') : public_path('customers/'.$this->customer.'/mobile/search.html');
        echo $template->searchPush($publicdata);
        file_put_contents($path, ob_get_contents());
        ob_end_clean();
        return $path;
    }
}