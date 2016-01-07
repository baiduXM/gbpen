<?php
/**
 * @author 小余、財財
 * @package CustomerController
 * @copyright 厦门易尔通
 */

/**
 * 用户相关控制器
 */
class CustomerController extends BaseController{
    
    /**
     * 获取用户详情
     */
	public function customerInfo(){
		$cus_id = Auth::id();
        $customer = Auth::user()->name;
		$customer_info = CustomerInfo::where('cus_id',$cus_id)->first();
		$data['company_name'] = $customer_info->company;
		$domain_pc = $customer_info->pc_domain;
		$data['domain_pc'] = str_replace('http://', '', $domain_pc);
		$domain_m = $customer_info->mobile_domain;
		$data['domain_m'] = str_replace('http://', '', $domain_m);
        if($customer_info->favicon!=''){
            $data['favicon'] = asset('customers/'.$customer.'/images/l/common/'.$customer_info->favicon);
        }
        if($customer_info->logo!=''){
            $data['logo_large'] = asset('customers/'.$customer.'/images/l/common/'.$customer_info->logo);
        }
        if($customer_info->logo_small!=''){
            $data['logo_small'] = asset('customers/'.$customer.'/images/l/common/'.$customer_info->logo_small);
        }
        $data['pc_header_script'] = $customer_info->pc_header_script;
        $data['mobile_header_script'] = $customer_info->mobile_header_script;
		$data['title'] = $customer_info->title;
		$data['keywords'] = $customer_info->keywords;
		$data['description'] = $customer_info->description;
		$data['footer'] = $customer_info->footer;
		$data['mobile_footer'] = $customer_info->mobile_footer;
        $data['pc_footer_script'] = $customer_info->pc_footer_script;
		$data['mobile_footer_script'] = $customer_info->mobile_footer_script;
		$data['pc_num_per_page'] = $customer_info->pc_page_count;
		$data['pc_num_pagenav'] = $customer_info->pc_page_links;
		$data['m_num_per_page'] = $customer_info->mobile_page_count;
		$data['m_num_pagenav'] = $customer_info->mobile_page_links;
		$data['contactor'] = $customer_info->contact_name;
		$data['telephone'] = $customer_info->telephone;
		$data['mobile'] = $customer_info->mobile;
		$data['fax'] = $customer_info->fax;
		$data['mail'] = $customer_info->email;
		$data['qq'] = $customer_info->qq;
		$data['address'] = $customer_info->address;
        $websiteinfo=WebsiteInfo::where('cus_id',$cus_id)->select('pc_tpl_id','mobile_tpl_id')->first();
        $pc_tpl_name=Template::where('id',$websiteinfo->pc_tpl_id)->pluck('name');
        if($pc_tpl_name!=null){
              $pc_ini=file_get_contents(public_path('/templates/'.$pc_tpl_name.'/config.ini'));
        	  $pc_search="/LogoSize=(.*)/i";
        	  $pc_search_result=preg_match($pc_search,$pc_ini,$pc_config_arr);
        }
        else{
            $pc_ini=false;
        }
        $data['pc_logo_size']=$pc_search_result?strtr($pc_config_arr[1],'*', '/'):0;
        $mobile_tpl_name=Template::where('id',$websiteinfo->mobile_tpl_id)->pluck('name');
         if($mobile_tpl_name!=null){
             $mobile_ini=file_get_contents(public_path('/templates/'.$mobile_tpl_name.'/config.ini'));
        	 $mobile_search="/LogoSize=(.*)/i";
        	 $mobile_search_result=preg_match($mobile_search,$mobile_ini,$mobile_config_arr);
        }
        else{
            $mobile_ini=false;
        }
        $data['m_logo_size']=$mobile_search_result?strtr($mobile_config_arr[1],'*', '/'):0;
		$result['err'] = 0;
        $result['msg'] = '';
        $result['data'] = $data;
		return Response::json($result);
	}
	
	/**
	 * 用户修改设置
	 */
	public function customerSetting(){
		$cus_id = Auth::id();
		$data['company'] = strtolower(Input::get('company_name'));
		$pc_domain = Input::get('domain_pc');
		$data['pc_domain'] = strstr($pc_domain,'http') ? $pc_domain : 'http://'.$pc_domain;
		$mobile_domain = Input::get('domain_m');
		$data['mobile_domain'] = strstr($mobile_domain,'http') ? $mobile_domain : 'http://'.$mobile_domain;
		$data['favicon'] = basename(Input::get('favicon'));
		$data['logo'] = basename(Input::get('logo_large'));
		$data['logo_small'] = basename(Input::get('logo_small'));
		$data['pc_header_script'] = Input::get('pc_header_script');
        $data['mobile_header_script'] = Input::get('mobile_header_script');
		$data['title'] = Input::get('title');
		$data['keywords'] = Input::get('keywords');
		$data['description'] = Input::get('description');
		$data['footer'] = Input::get('footer');
		$data['mobile_footer'] = Input::get('mobile_footer');
		$data['pc_footer_script'] = Input::get('pc_footer_script');
        $data['mobile_footer_script'] = Input::get('mobile_footer_script');
		$data['pc_page_count'] = Input::get('pc_num_per_page')?Input::get('pc_num_per_page'):12;
		$data['pc_page_links'] = Input::get('pc_num_pagenav')?Input::get('pc_num_pagenav'):8;
		$data['mobile_page_count'] = Input::get('m_num_per_page')?Input::get('m_num_per_page'):12;
		$data['mobile_page_links'] = Input::get('m_num_pagenav')?Input::get('m_num_pagenav'):3;
		$data['contact_name'] = Input::get('contactor');
		$data['telephone'] = Input::get('telephone');
		$data['mobile'] = Input::get('mobile');
		$data['fax'] = Input::get('fax');
		$data['email'] = Input::get('mail');
		$data['qq'] = Input::get('qq');
		$data['address'] = Input::get('address');
		$update = CustomerInfo::where('cus_id',$cus_id)->update($data);
		if($update){
            Articles::where('cus_id',$cus_id)->where('pushed',0)->update(array('pushed'=>1));
            Classify::where('cus_id',$cus_id)->where('pushed',0)->update(array('pushed'=>1));
			$result = ['err' => 0, 'msg' => '','data'=>''];
		}
		else{
			$result = ['err' => 1002, 'msg' => '无法保存数据','data'=>''];
		}
		return Response::json($result);

	}
}