<?php

namespace controllers\admin;

class Coupon extends Base
{
	
    public function index(){
    	$userId = $this->request('userId');
    	return get_defined_vars();
    }
    
    //优惠券列表
    public function couponList(){
    	$page = $this->request('page');
    	$rows = $this->request('rows');
    	$total = wei()->db->count('coupon', array('enable'=>1));
    	$couponList = wei()->db('coupon')
    						->where('enable=?', 1)
    						->limit($rows)
    						->page($page)
    						->orderBy('id', 'desc')
    						->fetchAll();
    	return json_encode(array('iTotalRecords'=>$total, 'iTotalDisplayRecords'=>$total, 'data'=>$couponList));
    }
    
    //编辑页面
    public function manage(){
    	$id = $this->request('id');
    	$couponInfo = wei()->db('coupon')->where('id=?', $id)->fetch();
    	if(!$couponInfo){
    		$couponInfo = array();
    	}
    	
    	return get_defined_vars();
    }
    
    //保存优惠券
    public function edit(){
    	$id = $this->request('id');
    	$name = $this->request('name');
    	$money = $this->request('money');
    	$validDay = $this->request('validDay');
    	$useScene = $this->request('useScene');
    	$rule = $this->request('rule');
    	$remark = $this->request('remark');
    	$pic = $this->request('pic');
    	$coupon = array();
    	$coupon['name'] = $name;
    	$coupon['money'] = $money;
    	$coupon['validDay'] = $validDay;
    	$coupon['useScene'] = $useScene;
    	$coupon['rule'] = $rule;
    	$coupon['remark'] = $remark;
    	$coupon['pic'] = $pic;
    	
    	if($id){
    		wei()->db->update('coupon', $coupon, array('id'=>$id));
    	}else{
    		$coupon['enable'] = 1;
    		$coupon['createTime'] = date('Y-m-d H:i:s');
    		wei()->db->insert('coupon', $coupon);
    	}
    	
    	return $this->json('保存优惠券成功', 1);
    }
    
    //删除优惠券
    public function del(){
    	$id = $this->request('id');
    	
    	wei()->db->update('coupon', array('enable'=>0), array('id'=>$id));
    	 
    	return $this->json('删除优惠券成功', 1);
    }
    
    //发送优惠券页面
    public function send(){
    	$id = $this->request('id');
    	$categoryList = wei()->getConfig('productCategories');
    	return get_defined_vars();
    }
    
    public function sendUser(){
    	$couponList = $this->request('couponList');
    	$userId = $this->request('userId');
    	$couponList = explode(',', $couponList);
    	foreach($couponList as $key => $value){
    		$couponInfo = wei()->db('coupon')->where('id=?', $value)->fetch();
    		$endTime = date('Y-m-d H:i:s', time() + $couponInfo['validDay'] * 24 * 60 * 60);
    		wei()->coupon->sendCoupon($value, $userId, date('Y-m-d H:i:s'), $endTime);
    	}
    	return $this->json('发送优惠券成功', 1);
    }
    
    //发送优惠券
    public function doSend(){
    	$couponId = $this->request('couponId');
    	
    	$subscribeList = $this->request->getPost('subscribeList');
    	$businessList = $this->request->getPost('businessList');
    	if(empty($subscribeList) && empty($businessList)){
    		return $this->json('请选择需要发送优惠券的分组', -1);
    	}
    	
    	$couponInfo = wei()->db('coupon')->where('id=?', $couponId)->fetch();
    	$endTime = date('Y-m-d H:i:s', time() + $couponInfo['validDay'] * 24 * 60 * 60);
    	
    	if($subscribeList){
    		foreach($subscribeList as $key => $value){
    			$userList = wei()->subscribe->getSUserList($value);
    			foreach($userList as $key1 => $value1){
    				wei()->coupon->sendCoupon($couponId, $value1['userId'], date('Y-m-d H:i:s'), $endTime);
    			}
    		}
    	}
    	
    	if($businessList){
    		foreach($businessList as $key => $value){
    			$userList = $this->getBusinessCategory($value);
    			foreach($userList as $key1 => $value1){
    				wei()->coupon->sendCoupon($couponId, $value1['userId'], date('Y-m-d H:i:s'), $endTime);
    			}
    		}
    	}
    	return $this->json('发送优惠券成功', 1);
    }
}