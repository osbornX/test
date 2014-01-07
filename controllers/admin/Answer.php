<?php

namespace controllers\admin;

class Answer extends Base
{
    public function index(){
    	$answer = wei()->db('answer')->where('id=?', 1)->fetch();
    	return get_defined_vars();
    }
    
    public function userlist(){
    	$page = (int) $this->request('page');
    	$rows = (int) $this->request('rows');
    	$period = (int) $this->request('period');
    	$totalResult = wei()->answer->getUserList($period);
    	$total = count($totalResult);
    	$result = wei()->answer->getUserList($period, $page, $rows);
    	return json_encode(array('iTotalRecords'=>$total, 'iTotalDisplayRecords'=>$total, 'data'=>$result));
    }
    
    public function manage(){
    	$id = $this->request('id');
    	$answer = wei()->db('answer')->where('id=?', $id)->fetch();
    	$json = json_encode($answer);
    	return get_defined_vars();
    }
    
    public function edit(){
    	$id = $this->request('id');
    	$thumb = $this->request('thumb');
    	$answer = $this->request('answer');
    	$period = (int) $this->request('period');
    	
    	if(empty($thumb)){
    		return $this->json('图片不能为空', -1);
    	}
    	if(empty($answer)){
    		return $this->json('答案不能为空', -2);
    	}
    	if($period <= 0){
    		return $this->json('请正确填写期数', -3);
    	}
    	
    	widget()->db->update('answer', array('thumb'=>$thumb, 'answer'=>$answer, 'period'=>$period), array('id'=>$id));
    	return $this->json('保存答题成功', 1);
    }
}