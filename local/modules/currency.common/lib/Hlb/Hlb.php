<?php

namespace Currency\Common\Hlb;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Exception;

class Hlb
{
    protected $entity;

    public function __construct($hlblockCode)
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new Exception('highloadblock не подключен');
        }

        $hlblock = HighloadBlockTable::getList(['filter' => ['=NAME' => $hlblockCode]])->fetch();
        if (!$hlblock) {
            throw new Exception('highloadblock: ' . $hlblockCode . " не найден");
        }

        $entity = HighloadBlockTable::compileEntity($hlblock);
        $this->entity = $entity->getDataClass();
    }

    public function getList($parameters = [])
    {
        return $this->entity::getList($parameters);
    }

    public function add($data)
    {
        return $this->entity::add($data);
    }

    public function update($id, $data)
    {
        return $this->entity::update($id, $data);
    }

    public function delete($id)
    {
        return $this->entity::delete($id);
    }

}
