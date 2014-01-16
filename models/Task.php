<?php

namespace models;

class Task extends Base
{
    public function isPrevCompleted()
    {
        return true;
    }

    public function __invoke($name = null, \Closure $fn = null)
    {
        // 创建一个任务对象
        $task = wei()->db->init('task', array(
            'name' => $name,
        ));

        // 如果任务还未完成,直接返回
        if (!$this->isTaskComplete($name)) {
            $task['message'] = '上一个任务还未完成';
            $task['result'] = false;
            return $task;
        }

        // 预先保存
        $task->save();

        $startTime = microtime(true);

        $result = $fn($task);

        $task['constTime']    = microtime(true) - $startTime;
        $task['completeTime'] = date('Y-m-d H:i:s');
        $task['result']       = $result;
        $task->save();

        return $task;
    }

    /**
     * 检查上次任务是否完成
     * 1. 执行中
     * 2. 出错停止了
     *
     * @param $name
     * @return bool
     */
    public function isTaskComplete($name)
    {
        $task = wei()->db('task')
            ->where('name = ?', $name)
            ->orderBy('id', 'DESC')
            ->find();

        if ($task && !$task['completeTime']) {
            return false;
        } else {
            return true;
        }
    }
}