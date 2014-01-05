<?php

namespace apps\demo\models;

use Wei\Base;

class Test extends Base
{
    public function __invoke()
    {
        return 'loaded';
    }
}