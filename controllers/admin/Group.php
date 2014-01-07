<?php

namespace controllers\admin;

class Group extends Base
{
    public function create($req)
    {
        wei()->group()->save($req);
        return $this->suc();
    }

    public function update($req)
    {
        $group = wei()->group()->findOne($req['id']);
        $group->save($req);
        return $this->suc();
    }

    public function destroy($req)
    {
        wei()->group()->destroy($req['id']);
        wei()->user()->where(array('groupId' => $req['id']))->update("groupId = 0");
        return $this->suc();
    }
}