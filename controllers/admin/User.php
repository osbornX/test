<?php

namespace controllers\admin;

class User extends Base
{
    /**
     * 展示用户列表
     */
    public function index()
    {
        return get_defined_vars();
    }

    /**
     * 拉取用户列表数据
     */
    public function lists($req)
    {
        $users = wei()->user();

        // 分页
        $users->limit($req['rows'])->page($req['page']);

        // 排序
        $users->desc('createTime');

        // 只显示有绑定且有效的用户
        $users
            ->andWhere("fakeId != ''")
            ->andWhere("isValid = 1");

        // 分组筛选
        if ($req['groupId']) {
            $users->andWhere('groupId = ?', (string)$req['groupId']);
        }

        // 用户名搜索
        if ($req['search']) {
            $users->andWhere('nickName LIKE ?', "%{$req['search']}%");
        }


        $data = array();
        foreach ($users as $user) {
            $data[] = $user->toArray() + array(

                );
        }

        switch ($req['format']) {
            case 'csv' :
                return $this->renderCsv($data);

            default:
                return $this->json('读取列表成功', 1, array(
                    'data' => $data,
                    'page' => (int)$req['page'],
                    'rows' => (int)$req['rows'],
                    'records' => $users->count()
                ));
        }
    }

    public function update($req)
    {
        $user = wei()->user()->findOne($req['id']);
        $user->save($req);
        return $this->suc();
    }

    /**
     * 将用户移动到新分组
     */
    public function moveGroup($req)
    {
        wei()->user()->where(array('id' => $req['ids']))->update("groupId = " . $req['groupId']);
        return $this->suc();
    }

    public function info($req)
    {
        $user = wei()->user()->findOne($req['id']);
        return $this->json('操作成功', 1, array(
            'data' => $user->toArray()
        ));
    }

    protected function renderCsv($users)
    {
        $data = array();
        $data[0] = array('用户', '性别', '国家', '省份', '城市', '注册时间');

        foreach ($users as $user) {
            $data[] = array(
                $user['nickName'],
                wei()->getConfig('sex:' . $user['gender']),
                $user['country'],
                $user['province'],
                $user['city'],
                $user['createTime'],
            );
        }

        return wei()->csvExporter->export('users', $data);
    }
}