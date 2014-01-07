<?php

namespace controllers\admin;

class Category extends Base
{
    public function index()
    {
        return get_defined_vars();
    }

    public function lists($req)
    {
        $categories = wei()->category();

        // 分页
        $categories->limit($req['rows'])->page($req['page']);

        // 排序
        $categories->desc('sort');

        // 搜索
        if ($req['search']) {
            $categories->andWhere('name LIKE ?', '%' . $req['search'] . '%');
        }

        $data = array();
        foreach ($categories as $category) {
            $data[] = $category->toArray() + array(
                'articleCount' => wei()->article()->where(array('categoryId' => $category['id']))->count()
            );
        }

        return $this->json('读取列表成功', 1, array(
            'data' => $data,
            'page' => $req['page'],
            'rows' => $req['rows'],
            'records' => $categories->count(),
        ));
    }

    public function add()
    {
        $category = wei()->category();
        return get_defined_vars();
    }

    public function create($req)
    {
        $category = wei()->category()->fromArray($req);
        $category->save();
        return $this->suc();
    }

    public function edit($req)
    {
        $category = wei()->category()->findOne($req['id']);
        return get_defined_vars();
    }

    public function update($req)
    {
        $category = wei()->category()->findOne($req['id']);
        $category->fromArray($req);
        $category->save();
        return $this->suc();
    }

    public function destroy($req)
    {
        wei()->category()->findOne($req['id'])->destroy();
        return $this->suc();
    }
}