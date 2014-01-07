<?php

namespace controllers\admin;

use Wei\Validate;

class Account extends Base
{
    /**
     * 展示用户列表页面
     */
    public function index()
    {
        return array();
    }

    /**
     * 拉取用户列表数据
     */
    public function lists()
    {
        $request = $this->request;
        $start = $request->getInt('iDisplayStart', 0);
        $rows = $request->getInt('iDisplayLength');
        $search = $request->get('sSearch');

        $qb = wei()->db('account');

        // 分页
        $qb->limit($rows)->offset($start);

        // 排序
        $qb->orderBy('createdAt', 'DESC');

        // 搜索
        if ($search) {
            $qb->andWhere('accountname LIKE ?', '%' . $search . '%');
        }

        $records = $qb->findAll();
        $total = $qb->count();

        $data = array();
        /** @var $record \Model\Account */
        foreach ($records as $record) {
            $data[] = $record->toArray() + array(
                'isSuperAdmin' => $record->isSuperAdmin()
            );
        }

        return json_encode(array(
            'iTotalRecords' => $total,
            'iTotalDisplayRecords' => $total,
            'data' => $data
        ));
    }

    /**
     * 展示创建用户页面
     */
    public function add()
    {
        $this->app->forward('edit');
    }

    /**
     * 展示编辑用户页面
     */
    public function edit($req)
    {
        if ($this->action == 'add') {
            $actionName = '添加';
            $actionLink = 'create';
        } else {
            $actionName = '编辑';
            $actionLink = 'update';
        }

        $account = $this->db->findOrCreate('account', $req['id']);

        return get_defined_vars();
    }

    /**
     * 创建用户逻辑
     */
    public function create()
    {
        $this->app->forward('update');
    }

    /**
     * 更新用户逻辑
     */
    public function update($req)
    {
        $id = $this->request('id');
        $password = $this->request('password');
        $action = $this->action;
        $data = $this->request->toArray();

        $validator = wei()->validate(array(
            'data' => $this->request,
            'rules' => array(
                'accountname' => array(
                    'length' => array(1, 32),
                    'alnum' => true,
                    'notRecordExists' => array('account', 'accountname')
                ),
                'nickname' => array(
                    'length' => array(1, 32)
                ),
                'password' => array(
                    'minLength' => 6
                ),
                'passwordAgain' => array(
                    'equalTo' => $req['password']
                )
            ),
            'names' => array(
                'accountname' => '用户名',
                'nickname' => '昵称',
                'password' => '密码',
                'passwordAgain' => '重复密码'
            ),
            'messages' => array(
                'passwordAgain' => array(
                    'equalTo' => '两次输入的密码不相等'
                )
            ),
            'beforeValidate' => function(Validate $validator) use($action) {
                // 更新操作不校验用户名
                if ('update' == $action) {
                    $validator->removeField('accountname');
                }
            }
        ));

        if (!$validator->isValid()) {
            return $this->error($validator->getJoinedMessage());
        }

        unset($data['salt']);
        // 编辑操作不允许更改用户名
        if ('update' == $action) {
            unset($data['accountname']);
        }

        /** @var $account \Model\Account */
        $account = $this->db->findOrCreate('account', $id);

        // 设置用户数据并保存
        $account->fromArray($data);
        $account->setPlainPassword($password);
        $account->save();

        return $this->success('操作成功');
    }

    /**
     * 展示用户信息页面
     */
    public function show()
    {

    }

    /**
     * 删除用户逻辑
     */
    public function destroy($req)
    {
        if (!$this->request->isPost()) {
            throw new \RuntimeException('Method not allowed', 405);
        }

        /** @var $account \Model\Account */
        $account = $this->db->find('account', $req['id']);
        if (!$account) {
            return $this->error('用户不存在');
        }

        if ($account['id'] == wei()->account->getId()) {
            return $this->error('不能删除自己');
        }

        if ($account->isSuperAdmin()) {
            return $this->error('不能删除超级管理员');
        }

        $result = $account->delete();
        if ($result) {
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    /**
     * 当前用户个人资料页
     */
    public function me()
    {
        $this->request->set('id', $this->account->getId());
        $this->app->forward('edit');
    }
}