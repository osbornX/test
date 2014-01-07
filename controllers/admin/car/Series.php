<?php

namespace controllers\admin\car;

class Series extends \controllers\admin\Base
{
    public function index()
    {
        return get_defined_vars();
    }

    public function lists($req)
    {
        $carSeries = wei()->carSeries();

        // 分页
        $carSeries->limit($req['rows'])->page($req['page']);

        // 排序
        $carSeries->desc('sort');

        // 搜索
        if ($req['search']) {
            $carSeries->andWhere('name LIKE ?', '%' . $req['search'] . '%');
        }

        $data = array();
        foreach ($carSeries as $series) {
            $data[] = $series->toArray() + array(
                'count' => $series->getCarsCount()
            );
        }

        return $this->json('读取列表成功', 1, array(
            'data' => $data,
            'page' => $req['page'],
            'rows' => $req['rows'],
            'records' => $carSeries->count(),
        ));
    }

    public function add()
    {
        $carSeries = wei()->carSeries();
        return get_defined_vars();
    }

    public function create($req)
    {
        wei()->carSeries()->save($req);
        return $this->suc();
    }

    public function edit($req)
    {
        $carSeries = wei()->carSeries()->findOne($req['id']);
        return get_defined_vars();
    }

    public function update($req)
    {
        wei()->carSeries()->findOne($req['id'])->save($req);
        return $this->suc();
    }

    public function destroy($req)
    {
        wei()->carSeries()->findOne($req['id'])->destroy();
        wei()->car()->findAll(array('seriesId' => $req['id']))->destroy();
        return $this->suc();
    }
}