<?php

class CustomerInfo extends Eloquent{
    protected $table = 'customer_info';
    public  $timestamps = true;

    public function getPCDomain($cus_id=0){
    	return $this->where('cus_id',$cus_id)->pluck('pc_domain');
    }
    
    public function getMobileDomain($cus_id=0){
    	return $this->where('cus_id',$cus_id)->pluck('mobile_domain');
    }
}
