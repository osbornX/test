<?php

namespace controllers\admin;

abstract class Base extends \controllers\Base
{
    /**
     * 无需用户登录的页面
     *
     * @var array
     */
    protected $guestPages = array(
        'admin/auth/login'
    );

    /**
     * 构造器
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->initUser();
        $this->initViewVariables();
        $this->requireLogin();
    }

    protected function initUser()
    {
        if (isset(wei()->session['user']['id'])) {
            wei()->account = $this->account = wei()->db->find('account', wei()->session['user']['id']);
        }
    }

    protected function initViewVariables()
    {
        wei()->view->assign('baseUrl', $this->request->getBaseUrl() . '/');
    }

    protected function requireLogin()
    {
        $page = $this->controller . '/' . $this->action;
        if (!wei()->account || (!wei()->account->isLogin() && !in_array($page, $this->guestPages))) {
            wei()->response->redirect(wei()->account->getLoginUrl());
            $this->app->preventPreviousDispatch();
        }
    }

    /**
     * 输出Excel表格
     *
     * @param string $name
     * @param array $data
     */
    protected function responseExcel($name, array $data)
    {

    	header('Content-Encoding: UTF-8');
    	header('Content-type: applicationnd.ms-excel');
    	header('Content-Disposition: attachment;'."filename={$name}-" . date('Ymdh') . ".csv");

    	// UTF-8 BOM
    	echo "\xEF\xBB\xBF";

    	$handle = fopen('php://output', 'w');
    	foreach ($data as $row) {
    		fputcsv($handle, $row);
    	}
    	fclose($handle);
    }
}