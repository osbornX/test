<?php

namespace controllers\admin;

class Setting extends Base
{
    public function index()
    {
        $settings = wei()->setting()->findAll();
        $fieldSets = array();

        foreach ($settings as $setting) {
            $name = $setting->getTypeLabel();

            if (!isset($fieldSets[$name])) {
                $fieldSets[$name] = array();
            }

            $fieldSets[$name][] = $setting;
        }
        return get_defined_vars();
    }

    public function update($req)
    {
        foreach ((array)$req['settings'] as $setting) {
            $record = wei()->setting()->findOne(array(
                'id' => $setting['id'],
                'type' => $setting['type']
            ));
            $record['value'] = $setting['value'];
            $record->save();
        }

        return $this->suc();
    }
}