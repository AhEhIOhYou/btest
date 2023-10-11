<?php

namespace Currency\Common\Hlb\HlbCurrency;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use CUserTypeEntity;
use Currency\Common\Hlb\Hlb;
use Exception;

class HlbCurrency extends Hlb
{
    private const HLB_NAME = "Currency";
    // code => type
    private const FIELDS = ["UF_COURSE_CODE" => "string", "UF_COURSE_DATE" => "date", "UF_COURSE_COURSE" => "double"];

    public function __construct()
    {
        try {
            parent::__construct(static::HLB_NAME);
        } catch (Exception $exception) {
            echo '<script>console.log(' . \CUtil::PhpToJsObject($exception) . ');</script>';
        }
    }

    public static function createHlb()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new Exception('highloadblock не подключен');
        }

        $result = HighloadBlockTable::add([
            'NAME' => static::HLB_NAME,
            'TABLE_NAME' => strtolower(static::HLB_NAME),
        ]);

        if (!$result->isSuccess()) {
            throw new Exception('Не удалось создать highload блок');
        }

        $hlId = $result->getId();

        $userTypeEntity = new CUserTypeEntity();

        foreach (static::FIELDS as $name => $type) {
            $arFields = [
                'ENTITY_ID' => 'HLBLOCK_' . $hlId,
                'FIELD_NAME' => $name,
                'USER_TYPE_ID' => $type,
                'XML_ID' => $name,
                'SORT' => 100,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'I',
                'SHOW_IN_LIST' => '',
                'EDIT_IN_LIST' => '',
                'IS_SEARCHABLE' => 'N',
            ];
            if ($type == "double") {
                $arFields['SETTINGS'] = [
                    'PRECISION' => 4,
                ];
            }
            $userTypeEntity->Add($arFields);
        }

        return $hlId;
    }

    public static function deleteHlb()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new Exception('highloadblock не подключен');
        }

        $hlblock = HighloadBlockTable::getList(['filter' => ['=NAME' => static::HLB_NAME]])->fetch();
        if ($hlblock) {
            return HighloadBlockTable::delete($hlblock['ID']);
        }

        return false;
    }

    public function getCurrencyList($parameters = [])
    {
        $result = parent::getList($parameters);
        return $result->fetchAll();
    }
}

