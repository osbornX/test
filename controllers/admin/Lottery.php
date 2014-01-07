<?php

namespace controllers\admin;

class Lottery extends Base
{
    //中奖用户
    public function index(){
    	return get_defined_vars();
    }
    
    public function winners(){
    	$page = (int) $this->request('page');
    	$rows = (int) $this->request('rows');
    	$awardType = (int) $this->request('awardType');
    	$totalResult = wei()->lottery->winnerList($awardType);
    	$total = count($totalResult);
    	$result = wei()->lottery->winnerList($awardType, $page, $rows);
    	return json_encode(array('iTotalRecords'=>$total, 'iTotalDisplayRecords'=>$total, 'data'=>$result));
    }
}