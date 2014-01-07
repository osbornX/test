<?php

namespace controllers\admin;

class Reply extends Base
{
    public function manage(){
    	$id = $this->request('id');
    	$reply = wei()->db('replyMessage')->where('enable=?', 1)->fetchAll();
    	$replyInfo = wei()->db('replyMessage')->where('id=?', $id)->fetch();
    	if(empty($replyInfo)){
    		$replyInfo = array(
    				'id' => 0,
    				'type' => 'replyEntry',
    				'keyWord' => '',
    				'title' => '',
    				'entry' => '',
    				'content' => '',
    				'url' => '',
    				'image' => ''
    		);
    	}
    	$json = json_encode($replyInfo);
    	return get_defined_vars();
    }
    
    public function edit(){
    	$id = (int) $this->request('id');
    	$type = $this->request('type');
    	$keyWord = $this->request('keyWord');
    	$title = $this->request('title');
    	$entry = $this->request('entry');
    	$content = $this->request('content');
    	$url = $this->request('url');
    	$image = $this->request('image');
    	
    	$data = array(
    		'type' => $type,
    		'keyWord' => $keyWord,
    		'title' => $title,
    		'entry' => $entry,
    		'content' => $content,
    		'url' => $url,
    		'image' => $image
    	);
    	//echo $id;print_r($data);
    	if($id){
    		wei()->db->update('replyMessage', $data, array('id'=>$id));
    	}else{
    		$data['createTime'] = date('Y-m-d H:i:s');
    		$data['enable'] = 1;
    		wei()->db->insert('replyMessage', $data);
    		$id = wei()->db->lastInsertId();
    	}
    	return $this->json('保存成功', 1, array('id'=> $id));
    }
    
    public function delete(){
    	$id = (int) $this->request('id');
    	wei()->db->update('replyMessage', array('enable'=>0), array('id'=>$id));
    	return $this->json('删除成功', 1);
    }
}