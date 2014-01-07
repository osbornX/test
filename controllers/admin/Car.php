<?php

namespace controllers\admin;

class Car extends Base
{
    public function index()
    {
        return get_defined_vars();
    }

    public function lists($req)
    {
        $cars = wei()->car();

        // 分页
        $cars->limit($req['rows'])->page($req['page']);

        // 排序
        $cars->desc('createTime');

        // 搜索
        if ($req['search']) {
            $cars->andWhere('name LIKE ?', '%' . $req['search'] . '%');
        }

        // 分类筛选
        if ($req['seriesId']) {
            $cars->andWhere('seriesId = ?', $req['seriesId']);
        }

        $data = array();
        foreach ($cars as $car) {
            $data[] = $car->toArray() + array(
                'seriesName' => $car->getSeries()->get('name')
            );
        }

        return $this->json('读取列表成功', 1, array(
            'data' => $data,
            'page' => $req['page'],
            'rows' => $req['rows'],
            'records' => $cars->count(),
        ));
    }

    public function add($req)
    {
        return $this->edit($req);
    }

    public function edit($req)
    {
        $car = wei()->car()->findOrInit(array('id' => $req['id']));

        // TODO 默认值
        if ($car->isNew()) {
            $car['sort'] = 100;
        }

        $attrs = $car->getAttrs();

        if ($attrs->length() == 0) {
            $attrs = wei()->db('attrTmpl')->findAll(array('categoryId' => 63));
        }

        return get_defined_vars();
    }

    public function create($req)
    {
        return $this->update($req);
    }

    public function update($req)
    {
        $car = wei()->car()->findOrInit(array('id' => $req['id']));
        $car->fromArray($req);
        $car->save();

        $attrs = $car->getAttrs();
        $attrs->saveColl($req['attrs'], array('carId' => $car['id']));

        return $this->suc();
    }

    public function destroy($req)
    {
        wei()->car()->findOne($req['id'])->destroy();
        return $this->suc();
    }
}