<?php

namespace controllers\admin;

class Index extends Base
{
    public function index()
    {
        return $this->app->forward('wechat/user');
    }
}