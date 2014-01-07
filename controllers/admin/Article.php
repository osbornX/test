<?php

namespace controllers\admin;

class Article  extends Base
{
    public function index()
    {
        return get_defined_vars();
    }

    public function lists($req)
    {
        $articles = wei()->article();

        // 分页
        $articles->limit($req['rows'])->page($req['page']);

        // 排序
        $articles->orderBy('createTime', 'DESC');

        // 搜索
        if ($req['search']) {
            $articles->andWhere('title LIKE ?', '%' . $req['search'] . '%');
        }

        // 分类筛选
        if ($req['categoryId']) {
            $articles->andWhere('categoryId = ?', $req['categoryId']);
        }

        $data = array();
        foreach ($articles as $article) {
            $data[] = $article->toArray() + array(
                'categoryName' => wei()->category()->find($article['categoryId'])->get('name')
            );
        }

        return $this->json('读取列表成功', 1, array(
            'data' => $data,
            'page' => $req['page'],
            'rows' => $req['rows'],
            'records' => $articles->count(),
        ));
    }

    public function add()
    {
        $article = wei()->article();
        return get_defined_vars();
    }

    public function create($req)
    {
        wei()->article()->save($req);
        return $this->suc('保存成功');
    }

    public function edit($req)
    {
        $article = wei()->article()->findOne($req['id']);
        return get_defined_vars();
    }

    public function update($req)
    {
        wei()->article()->findOne($req['id'])->fromArray($req)->save();
        return $this->suc();
    }

    public function destroy($req)
    {
        wei()->article()->findOne($req['id'])->destroy();
        return $this->suc();
    }
}