<?php

namespace apps\demo\services;

use services\Base;

class TestService extends Base
{
    public function __invoke()
    {
        return 'loaded';
    }
}