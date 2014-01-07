<?php

namespace controllers;

use Wei\Request;
use Wei\Response;

class Admin extends Base
{
    public function index()
    {
        return wei()->response->redirect(wei()->url('admin/article/index'));
    }

    public function login(Request $req, Response $res)
    {
        $message = '';

        if ($req->isPost()) {
            $validator = wei()->validate(array(
                'data' => $req,
                'rules' => array(
                    'username' => array(
                        'required' => true,
                    ),
                    'password' => array(
                        'required' => true,
                    )
                ),
                'names' => array(
                    'username' => '用户名',
                    'password' => '密码'
                )
            ));

            if (!$validator->isValid()) {
                $message = $validator->getFirstMessage();
                return get_defined_vars();
            }

            /** @var $account \models\Account */
            $account = $this->db->find('account', array('username' => $req['username']));
            if (!$account) {
                $message = '用户名不存在或密码错误';
                return get_defined_vars();
            }

            if (!$account->verifyPassword($req['password'])) {
                $message = '用户不存在或密码错误';
                return get_defined_vars();
            }

            // 设置登录状态
            wei()->account->login($account->toArray());

            // 跳转到后台首页
            $jumpUrl = $req('jumpurl', wei()->url->full('admin'));
            return $res->redirect($jumpUrl);
        }

        return get_defined_vars();
    }

    public function logout(Request $req, Response $res)
    {
        wei()->account->logout();

        // TODO 安全检查
        $jumpUrl = $req('jumpurl', wei()->url->full('admin'));

        return $res->redirect($jumpUrl);
    }
}