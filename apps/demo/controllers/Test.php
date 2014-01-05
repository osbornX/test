<?php

namespace apps\demo\controllers;

class Test extends \controllers\Base
{
    public function index()
    {
        echo '配置:' . wei()->getConfig('htft') . '<p>';
        echo '模型类:' . wei()->test() . '<p>';
        echo '服务类:' . wei()->testService() . '<p>';
        return array();
    }
}