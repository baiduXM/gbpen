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
        $path = $type =='pc' ? public_path('customers/'.$this->customer.'/index.html') : public_path('customers/'.$this->customer.'/mobile/index.html');
        $template = new PrintController('online',$type);
        if($type =='pc'){
            echo $template->homepagePreview();
        }
        else{
            echo $template->mhomepagePreview();
        }
        file_put_contents($path, ob_get_contents());
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
        $result = [];
        if(isset($_GET['catflush'])){
        ob_flush();
        flush();
        for(;time()-$this->start<800;sleep(10)){
        echo "cat:".(time()-$this->start)."<br />";
        ob_flush();
        flush();
        }
        sleep(400);
        echo "cat<br />";
        ob_flush();
        flush();
        sleep(400);}
        $template = new PrintController('online',$type);
        $per_page = CustomerInfo::where('cus_id',$this->cus_id)->pluck($type."_page_count");
        $c_id_lists=Articles::where('cus_id',$this->cus_id)->where($type.'_show','1')->lists('c_id');
        if($type=='pc'){
            $pc_page_count_switch = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count_switch');//页面图文列表图文显示个数是否分开控制开关
            if(isset($pc_page_count_switch)&&$pc_page_count_switch==1){
                $page_txt_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_txt_count');//每页文字显示个数
                $page_imgtxt_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_imgtxt_count');//每页图文显示个数
                $page_img_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count');//每页图片显示个数
            }
        }
        foreach($ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $a_c_type = Classify::where('id',$id)->pluck('type');//取得栏目的type
            if($type=='pc'&&isset($pc_page_count_switch)&&$pc_page_count_switch==1&&$a_c_type<=3){
                if($a_c_type==1){
                    $total=0;
                    foreach($c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    $page_count = ceil($total/$page_txt_number);
                    echo "type:1<br />";
                }
                if($a_c_type==3){
                    $total=0;
                    foreach($c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    $page_count = ceil($total/$page_imgtxt_number);
                    echo "type:2<br />";
                }
                if($a_c_type==2){
                    $total=0;
                    foreach($c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    $page_count = ceil($total/$page_img_number);
                    echo "type:1<br />";
                }
            }else{
                $total=0;
                foreach($c_id_lists as $c_id){
                    if(in_array($c_id, $c_ids)){
                        $total++;
                    }
                }
                $page_count = ceil($total/$per_page);
            }
            $this->getPrecent();   
            $path = $type =='pc' ? public_path('customers/'.$this->customer.'/category/'.$id.'.html') : public_path('customers/'.$this->customer.'/mobile/category/'.$id.'.html');
            ob_start();
            echo $template->categoryPreview($id,1);
            file_put_contents($path, ob_get_contents());
            ob_end_clean();        
            $result[] = $path;
            for($i=1;$i<=$page_count;$i++){
                $this->getPrecent();
                ob_start();
                $path = $type =='pc' ? public_path('customers/'.$this->customer.'/category/'.$id.'_'.$i.'.html') : public_path('customers/'.$this->customer.'/mobile/category/'.$id.'_'.$i.'.html');
                echo $template->categoryPreview($id,$i);
                file_put_contents($path, ob_get_contents());
                ob_end_clean();
                $result[] = $path;
            }
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
        $result = [];
        var_dump($ids);
        $num=1;
        echo "wwww<br />";
        foreach($ids as $id){
            if(isset($_GET['con']))
            if($id<8273){
                continue;
            }
            echo $id."_test<br />";
            if(!isset($_GET['test']))
            $this->getPrecent();
            $this->end=time();
            echo "{$num}_articlehtml_".$id."time(".(($this->end)-($this->start)).")--------flush(".ob_get_lenght().")<br />";
            ob_flush();
            flush();
            $num++;
            ob_start();
            $path = $type =='pc' ? public_path('customers/'.$this->customer.'/detail/'.$id.'.html') : public_path('customers/'.$this->customer.'/mobile/detail/'.$id.'.html');
            if(isset($_GET['art_sleep'])){
            sleep(10);}else{
            echo $template->articlePreview($id);
            file_put_contents($path, ob_get_contents());}
            if(isset($_GET['flush'])){
                echo "flush:".ob_get_lenght()."<br />";
            }
            ob_end_clean();
            $result[] = $path;
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
        $pc_page_count_switch = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count_switch');//页面图文列表图文显示个数是否分开控制开关
        $pc_c_id_lists=Articles::where('cus_id',$this->cus_id)->where('pc_show','1')->lists('c_id');
        $mobile_c_id_lists=Articles::where('cus_id',$this->cus_id)->where('mobile_show','1')->lists('c_id');
        if(isset($pc_page_count_switch)&&$pc_page_count_switch==1){
            $page_txt_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_txt_count');//每页文字显示个数
            $page_imgtxt_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_imgtxt_count');//每页图文显示个数
            $page_img_number=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count');//每页图片显示个数
        }
        foreach($pc_classify_ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $a_c_type = Classify::where('id',$id)->pluck('type');//取得栏目的type
            if(isset($pc_page_count_switch)&&$pc_page_count_switch==1&&$a_c_type<=3){
                if($a_c_type==1){
                    $total=0;
                    foreach($pc_c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    if($total){
                        $page_count += ceil($total/$page_txt_number);
                    }
                    else{
                        $page_count++;
                    }
                }
                if($a_c_type==3){
                    $total=0;
                    foreach($pc_c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    if($total){
                        $page_count += ceil($total/$page_imgtxt_number);
                    }
                    else{
                        $page_count++;
                    }
                }
                if($a_c_type==2){
                    $total=0;
                    foreach($pc_c_id_lists as $c_id){
                        if(in_array($c_id, $c_ids)){
                            $total++;
                        }
                    }
                    if($total){
                        $page_count += ceil($total/$page_img_number);
                    }
                    else{
                        $page_count++;
                    }
                }
            }else{
                $total=0;
                foreach($pc_c_id_lists as $c_id){
                    if(in_array($c_id, $c_ids)){
                        $total++;
                    }
                }
                if($total){
                    $page_count += ceil($total/$pc_per_page);
                }
                else{
                    $page_count++;
                }
            }

        }
        $mobileper_page = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_page_count');
        foreach($mobile_classify_ids as $id){
            $c_ids=explode(',',$template->getChirldenCid($id));
            $total=0;
            foreach($mobile_c_id_lists as $c_id){
                if(in_array($c_id, $c_ids)){
                    $total++;
                }
            }
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
     * 推送
     * 
     * 
     */
    public function pushPrecent(){
        set_time_limit(0);
        $this->start=time();
        if(isset($_GET['test'])){
        $pc_article_ids = Articles::where('cus_id',$this->cus_id)->where('pc_show',1)->lists('id');
        $articlehtml = $this->articlehtml($pc_article_ids,'pc');
        if(!isset($_GET['noexit'])){
        exit();}
        }
        echo 'start:'.date("H:i:s",time());
        if (ob_get_level() == 0){
            ob_start();
        }
        $indexhtml = $this->homgepagehtml('pc');
        $customer_data_get = CustomerPushfile::where('cus_id',$this->cus_id)->pluck('files');
        if($customer_data_get){
            $new_data = 0;
            $customer_data = unserialize($customer_data_get);
        }
        else{
            $new_data = 1;
            $customer_data = [];
        }
        $mindexhtml = $this->homgepagehtml('mobile');
        $searchhtml = $this->sendData('pc');
        $msearchhtml = $this->sendData('mobile');
        echo "t1";
        $pc_classify_ids = Classify::where('cus_id',$this->cus_id)->where('pc_show',1)->lists('id');
        echo "t2";
        $mobile_classify_ids = Classify::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
        echo "t3";
        $pc_article_ids = Articles::where('cus_id',$this->cus_id)->where('pc_show',1)->lists('id');
        echo "t4";
        $mobile_article_ids = Articles::where('cus_id',$this->cus_id)->where('mobile_show',1)->lists('id');
         echo "t5";
        $count = $this->htmlPagecount($pc_classify_ids,$mobile_classify_ids,$pc_article_ids,$mobile_article_ids);
        $this->html_precent= 70/$count;
        $this->end=time();
         echo "test0(".(($this->end)-($this->start)).")";
        var_dump($pc_classify_ids);
        $categoryhtml = $this->categoryhtml($pc_classify_ids,'pc');
        $this->end=time();
         echo "test1(".(($this->end)-($this->start)).")";
         var_dump($mobile_classify_ids);
        $mcategoryhtml = $this->categoryhtml($mobile_classify_ids,'mobile');
        $this->end=time();
         echo "test2(".(($this->end)-($this->start)).")";
       if(!isset($_GET['test'])){
        $articlehtml = $this->articlehtml($pc_article_ids,'pc');
       }  
        $this->end=time();
        echo "test3(".(($this->end)-($this->start)).")";
        $marticlehtml = $this->articlehtml($mobile_article_ids,'mobile');
        $this->percent = 20/$count;
        $this->end=time();
        echo "test4(".(($this->end)-($this->start)).")";
        $path = public_path('customers/'.$this->customer.'/'.$this->customer.'.zip');
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($indexhtml,'index.html');
            $zip->addFile($searchhtml,'search.html');
            $zip->addFile(public_path('customers/'.$this->customer.'/article_data.json'),'article_data.json');
            $zip->addFile(public_path('customers/'.$this->customer.'/mobile/article_data.json'),'mobile/article_data.json');
            $nowpercent = $this->percent + $this->lastpercent;
            if(floor($nowpercent)!=$this->lastpercent){
                echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
                ob_flush();
                flush();
            }
            $this->lastpercent += 70+$this->percent;
            $zip->addFile($mindexhtml,'mobile/index.html');
            $zip->addFile($msearchhtml,'mobile/search.html');
            $nowpercent = $this->percent + $this->lastpercent;
            if(floor($nowpercent)!=floor($this->lastpercent)){
                echo floor($nowpercent) . '%<script type="text/javascript">parent.refresh(' . floor($nowpercent) . ');</script><br />';
                ob_flush();
                flush();
            }
            $this->lastpercent += $this->percent;
            $zip->close();
        }
            //$customer_data = $this->compareZip($categoryhtml,$customer_data,'p','category',$path);
            //$customer_data = $this->compareZip($mcategoryhtml,$customer_data,'m','mobile/category',$path);
            //$customer_data = $this->compareZip($articlehtml,$customer_data,'pf','detail',$path);
            //$customer_data = $this->compareZip($marticlehtml,$customer_data,'mf','mobile/detail',$path);
             $this->compareZip($categoryhtml,$customer_data,'p','category',$path);
             $this->compareZip($mcategoryhtml,$customer_data,'m','mobile/category',$path);
             $this->compareZip($articlehtml,$customer_data,'pf','detail',$path);
             $this->compareZip($marticlehtml,$customer_data,'mf','mobile/detail',$path);
  
            if(90 > floor($this->lastpercent)) {
                echo '90%<script type="text/javascript">parent.refresh(90);</script><br />';
                ob_flush();
                flush();
            }
            
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $pc_dir=  Template::where('website_info.cus_id',$this->cus_id)->Leftjoin('website_info','website_info.pc_tpl_id','=','template.id')->pluck('name');
            $mobile_dir=  Template::where('website_info.cus_id',$this->cus_id)->Leftjoin('website_info','template.id','=','website_info.mobile_tpl_id')->pluck('name');
            $aim_dir=public_path("templates/$pc_dir/");
            $maim_dir=public_path("templates/$mobile_dir/");
            //$images_dir=public_path("customers/".$this->customer."/images/");
            $this->addDir($aim_dir,$zip); 
            //$this->addDir($images_dir,$zip,'images/'); 
            $this->addDir($maim_dir,$zip,'mobile/');
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
            echo "ftp:start<br />";
            $customerinfo = Customer::find($this->cus_id);
            $ftp_array = explode(':',$customerinfo->ftp_address);
            $port= $customerinfo->ftp_port;
            $ftpdir=$customerinfo->ftp_dir;
            $ftp=$customerinfo->ftp;
            $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:$port;
            $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
            if(trim($ftp)=='1'){
                if($conn){
                ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);
               // dd($conn,$this->customer,$path);
                if(@ftp_chdir($conn,$this->customer) == FALSE){
                ftp_mkdir($conn,$this->customer);  
                //ftp_cdup($conn);
                }
                ftp_put($conn,"/".$this->customer."/site.zip",$path,FTP_BINARY);
                ftp_put($conn,"/".$this->customer."/unzip.php",public_path("packages/unzip.php"),FTP_ASCII);
                ftp_put($conn,"/".$this->customer."/search.php",public_path("packages/search.php"),FTP_ASCII);
                ftp_put($conn,"/".$this->customer."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                ftp_put($conn,"/".$this->customer."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                //ftp_chdir($conn,$this->customer);
                if(@ftp_chdir($conn,"/".$this->customer."/mobile") == FALSE){
                ftp_mkdir($conn,"/".$this->customer."/mobile"); 
                //ftp_cdup($conn);
                }
                
                //ftp_chdir($conn,"mobile");
                ftp_put($conn,"/".$this->customer."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                ftp_close($conn);
            }
            }else{
                 if($conn){
                ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);
                ftp_put($conn,$ftpdir."/site.zip",$path,FTP_BINARY);
                ftp_put($conn,$ftpdir."/unzip.php",public_path("packages/unzip.php"),FTP_ASCII);
                ftp_put($conn,$ftpdir."/search.php",public_path("packages/search.php"),FTP_ASCII);
                ftp_put($conn,$ftpdir."/mobile/search.php",public_path("packages/search.php"),FTP_ASCII);
                ftp_put($conn,$ftpdir."/quickbar.json",public_path('customers/'.$this->customer.'/quickbar.json'),FTP_ASCII);
                ftp_put($conn,$ftpdir."/mobile/quickbar.json",public_path('customers/'.$this->customer.'/mobile/quickbar.json'),FTP_ASCII);
                ftp_close($conn);
            }
            }
           
            
            echo '100%<script type="text/javascript">parent.refresh(100);</script><br />';
            Classify::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            Articles::where('cus_id',$this->cus_id)->where('pushed',1)->update(['pushed'=>0]);
            //$pc_domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
             /**
            * pc使用本服务器自带域名推送，后期需要改进！
            */
            $cus_name =strtolower( Customer::where('id',$this->cus_id)->pluck('name'));
            if(trim($ftp)=='1'){
                $ftp_pcdomain="http://".$cus_name.".n01.5067.org";
            }
            else{
                $ftp_pcdomain=$customerinfo->pc_domain;
                dd($ftp_pcdomain);
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
            foreach($filearray as $file)
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
        ob_start();
        $path = $type =='pc' ? public_path('customers/'.$this->customer.'/search.html') : public_path('customers/'.$this->customer.'/mobile/search.html');
        echo $template->searchPreview();
        file_put_contents($path, ob_get_contents());
        ob_end_clean();
        return $path;
    }
}