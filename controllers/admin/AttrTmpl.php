<?php

namespace controllers\admin;

class AttrTmpl extends Base
{
    public function index()
    {

    }

    public function lists()
    {

    }

    public function add($req)
    {
        return $this->edit($req);
    }

    public function edit($req)
    {
        $category = wei()->attrTmplCategory()->findOrInit(array('id' => $req['id']));
        return get_defined_vars();
    }

    public function create($req)
    {
        return $this->update($req);
    }

    public function update($req)
    {
        $category = wei()->attrTmplCategory()->findOrInit(array('id' => $req['id']));
        $category->fromArray($req);
        $category->save();

        $attrs = $category->getAttrs();
        $attrs->saveColl($req['attrs'], array('categoryId' => $category['id']));

        return $this->suc();
    }

    public function destroy()
    {

    }
}