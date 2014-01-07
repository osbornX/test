<?php

namespace Controller\admin;

class Task extends Base
{
    public function dropdown()
    {
        $data = array(
            'sendMassMessage' => array(
                'name' => '群发消息',
                'icon' => 'btn-success icon-comment',
            ),
            'syncUserByMessages' => array(
                'name' => '通过信息内容同步用户',
                'icon' => 'btn-primary icon-user'
            ),
            'syncFromRecentlyMessages' => array(
                'name' => '从新用户列表同步用户',
                'icon' => 'btn-primary icon-user'
            ),
            'syncNewUsers' => array(
                'name' => '从消息列表同步用户',
                'icon' => 'btn-primary icon-user'
            ),
        );

        foreach ($data as $name => $row) {
            $task = wei()
                ->db('task')
                ->select('name, completeTime')
                ->where('name = ?', $name)
                ->orderBy('id', 'DESC')
                ->fetch();
            if ($task) {
                $data[$name] += $task;
            } else {
                unset($data[$name]);
            }
        }

        return $this->json('', 1, array(
            'tasks' => $data,
        ));
    }
}