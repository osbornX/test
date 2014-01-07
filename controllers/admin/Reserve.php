<?php

namespace controllers\admin;

class Reserve extends Base
{
    //中奖用户
    public function index(){
    	return get_defined_vars();
    }
    
    public function userlist(){
    	$page = (int) $this->request('page');
    	$rows = (int) $this->request('rows');
    	$total = wei()->db->count('reserve');
    	$result = wei()->db('reserve')->limit($rows)->page($page)->orderBy('createTime', 'desc')->fetchAll();
    	
    	if($result){
    		foreach($result as $key => &$value){
    			//$value['userInfo'] = wei()->weChatUser->getUserInfo($value['userId']);
    			$value['reserveCar'] = $this->getCarName($value['reserveCar']);
    		}
    	}
    	return json_encode(array('iTotalRecords'=>$total, 'iTotalDisplayRecords'=>$total, 'data'=>$result));
    }
    
    public function exportUserList(){
    	$rows = (int) $this->request('rows');
    	$result = wei()->db('reserve')->limit($rows)->page(1)->orderBy('createTime', 'desc')->fetchAll();
    	$data = array();
    	$data[] = array("联系人","联系电话","预约时间","中意车型","备注","提交时间");
    	if(!empty($result)){
    		foreach($result as $key => $value){
    			$data[] = array(
    					$value['name'],
    					$value['mobile'],
    					$value['reserveTime'],
    					$this->getCarName($value['reserveCar']),
    					//$value['reserveCar'],
    					$value['remark'],
    					$value['createTime']
    			);
    		}
    	}
    	$name = "预约试驾用户列表";
    	$this->responseExcel($name, $data);
    }
    
    public function getCarName($id){
    	$car = wei()->db('car')->where('id=?', $id)->fetch();
    	return $car['name'];
    }
}