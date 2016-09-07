<?php

/**
 * @author 小余、財財
 * @package CustomerController
 * @copyright 厦门易尔通
 */

/**
 * 用户相关控制器
 */
class CustomerController extends BaseController {

    /**
     * 获取用户详情
     */
    public function customerInfo() {
        $cus_id = Auth::id();
        $customer = Auth::user()->name;
        $weburl = Customer::where('id', $cus_id)->pluck('weburl');
        $suf_url = str_replace('http://c', '', $weburl);
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $data['company_name'] = $customer_info->company;
        $domain_pc = $customer_info->pc_domain;
        $data['domain_pc'] = str_replace('http://', '', $domain_pc);
        $domain_m = $customer_info->mobile_domain;
        $data['domain_m'] = str_replace('http://', '', $domain_m);
        $data['def_domain_pc'] = $customer . $suf_url;
        $data['def_domain_m'] = "m." . $customer . $suf_url;
        if ($customer_info->favicon != '') {
            $data['favicon'] = asset('customers/' . $customer . '/images/l/common/' . $customer_info->favicon);
        }
        if ($customer_info->logo != '') {
            $data['logo_large'] = asset('customers/' . $customer . '/images/l/common/' . $customer_info->logo);
        }
        if ($customer_info->logo_small != '') {
            $data['logo_small'] = asset('customers/' . $customer . '/images/l/common/' . $customer_info->logo_small);
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
        $data['pc_imgtxt_per_page'] = $customer_info->pc_page_imgtxt_count;
        $data['pc_txt_per_page'] = $customer_info->pc_page_txt_count;
        $data['pc_img_per_page'] = $customer_info->pc_page_img_count;
        $data['pc_page_count_switch'] = $customer_info->pc_page_count_switch;
        $data['copyright'] = $customer_info->copyright;
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
        $data['enlarge'] = $customer_info->enlarge;
        $data['lang'] = $customer_info->lang;
        $data['lastpushtime'] = strtotime($customer_info->lastpushtime);
        $data['floatadv'] = json_decode($customer_info->floatadv);
        foreach ((array) $data['floatadv'] as $key => $val) {
            if (!isset($val->type) || $val->type == 'adv') {
                $data['floatadv'][$key]->url = asset('customers/' . $customer . '/images/l/common/' . $val->adv);
            }
        }
        $websiteinfo = WebsiteInfo::where('cus_id', $cus_id)->select('pc_tpl_id', 'mobile_tpl_id')->first();
        $pc_tpl_name = Template::where('id', $websiteinfo->pc_tpl_id)->pluck('name');
        if ($pc_tpl_name != null) {
            $pc_ini = parse_ini_file(public_path('/templates/' . $pc_tpl_name . '/config.ini'), true);
        } else {
            $pc_ini = false;
        }
        $data['pc_logo_size'] = isset($pc_ini['Config']['LogoSize']) ? strtr($pc_ini['Config']['LogoSize'], '*', '/') : 0;
        $mobile_tpl_name = Template::where('id', $websiteinfo->mobile_tpl_id)->pluck('name');
        if ($mobile_tpl_name != null) {
            $mobile_ini = parse_ini_file(public_path('/templates/' . $mobile_tpl_name . '/config.ini'), true);
        } else {
            $mobile_ini = false;
        }
        $data['m_logo_size'] = isset($mobile_ini['Config']['LogoSize']) ? strtr($mobile_ini['Config']['LogoSize'], '*', '/') : 0;
        $result['err'] = 0;
        $result['msg'] = '';
        $result['data'] = $data;
        return Response::json($result);
    }

    /**
     * 用户修改设置
     */
    public function customerSetting() {
        $cus_id = Auth::id();
        $logo = CustomerInfo::where('cus_id', $cus_id)->pluck('logo');
        $logo_small = CustomerInfo::where('cus_id', $cus_id)->pluck('logo_small');
        $org_floatadv = CustomerInfo::where('cus_id', $cus_id)->pluck('floatadv');
        $org_floatadv = json_decode($org_floatadv);
        $org_imgs = array();
        foreach ((array) $org_floatadv as $v) {
            if (!isset($v->type) || $v->type == 'adv') {
                $org_imgs[] = $v->adv;
            }
        }
        $data['company'] = strtolower(Input::get('company_name'));
        $pc_domain = Input::get('domain_pc');
        $data['pc_domain'] = strstr($pc_domain, 'http') ? $pc_domain : 'http://' . $pc_domain;
        $mobile_domain = Input::get('domain_m');
        $data['mobile_domain'] = strstr($mobile_domain, 'http') ? $mobile_domain : 'http://' . $mobile_domain;
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
        $data['pc_page_count'] = Input::get('pc_num_per_page') ? Input::get('pc_num_per_page') : 12;
        $data['pc_page_links'] = Input::get('pc_num_pagenav') ? Input::get('pc_num_pagenav') : 8;
        $data['mobile_page_count'] = Input::get('m_num_per_page') ? Input::get('m_num_per_page') : 12;
        $data['mobile_page_links'] = Input::get('m_num_pagenav') ? Input::get('m_num_pagenav') : 3;
        $data['contact_name'] = Input::get('contactor');
        $data['telephone'] = Input::get('telephone');
        $data['mobile'] = Input::get('mobile');
        $data['fax'] = Input::get('fax');
        $data['email'] = Input::get('mail');
        $data['qq'] = Input::get('qq');
        $data['address'] = Input::get('address');
        $data['pc_page_imgtxt_count'] = (Input::get('pc_imgtxt_per_page') > 0) ? Input::get('pc_imgtxt_per_page') : 3;
        $data['pc_page_txt_count'] = (Input::get('pc_txt_per_page') > 0) ? Input::get('pc_txt_per_page') : 3;
        $data['pc_page_img_count'] = (Input::get('pc_img_per_page') > 0) ? Input::get('pc_img_per_page') : 3;
        $data['pc_page_count_switch'] = Input::get('pc_page_count_switch');
        $data['enlarge'] = Input::get('enlargev');
        $data['lang'] = Input::get('lang');
        $data['copyright'] = Input::get('copyright');
        $data['pushed'] = 1;

        $float_adv = Input::get('float_adv') ? Input::get('float_adv') : array();
        $float_type = Input::get('float_type') ? Input::get('float_type') : array();
        $posx = Input::get('posx') ? Input::get('posx') : array();
        $posy = Input::get('posy') ? Input::get('posy') : array();
        $posw = Input::get('posw') ? Input::get('posw') : array();
        $href = Input::get('href') ? Input::get('href') : array();
        $position = Input::get('position') ? Input::get('position') : array();
        $floatadv = array();
        $num = 0;
        foreach ((array) $float_adv as $key => $val) {
            $floatadv[$num]['adv'] = $val;
            $floatadv[$num]['type'] = $float_type[$key];
            $floatadv[$num]['posx'] = $posx[$key];
            $floatadv[$num]['posy'] = $posy[$key];
            $floatadv[$num]['posw'] = $posw[$key];
            $floatadv[$num]['href'] = !empty($href[$key]) ? $href[$key] : '';
            $floatadv[$num]['position'] = $position[$key];
            $num++;
        }
        $data['floatadv'] = json_encode($floatadv);
        $update = CustomerInfo::where('cus_id', $cus_id)->update($data);
        if ($update) {
            if ($logo != $data['logo']) {
                $imgdel = new ImgDel();
                $imgdel->mysave($logo, 'common');
            }
            if ($logo_small != $data['logo_small']) {
                $imgdel = new ImgDel();
                $imgdel->mysave($logo_small, 'common');
            }
            foreach ((array) $org_imgs as $v) {
                if (!in_array($v, $float_adv)) {
                    $imgdel = new ImgDel();
                    $imgdel->mysave($v, 'common');
                }
            }
            $result = ['err' => 0, 'msg' => '', 'data' => ''];
        } else {
            $result = ['err' => 1002, 'msg' => '无法保存数据', 'data' => ''];
        }
        return Response::json($result);
    }

    /**
     * 初始化获取切换信息
     */
    public function isSwitchcus() {
        $cus_id = Auth::id();
        $switch_cus_id = Customer::where('id', $cus_id)->pluck('switch_cus_id');
        return $switch_cus_id;
    }

    /**
     * 获取用户信息
     */
    public function getSwitchCustomer() {
        $cus_id = Auth::id();
        $switch_cus_id = Customer::where('id', $cus_id)->pluck('switch_cus_id');
        if (empty($switch_cus_id)) {
            return null;
        }
        $current_cus_info = Customer::where('id', $cus_id)->first();
        $switch_cus_info = Customer::where('id', $switch_cus_id)->first();
        $data['switch_pc_domain'] = $switch_cus_info->pc_domain;
        $data['switch_mobile_domain'] = $switch_cus_info->mobile_domain;
        $data['current_pc_domain'] = $current_cus_info->pc_domain;
        $data['current_mobile_domain'] = $current_cus_info->mobile_domain;
        return $data;
    }

}
