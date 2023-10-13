<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Engine\Contract\Controllerable;
use Currency\Common\Hlb\HlbCurrency\HlbCurrency;

class CurrencyList extends CBitrixComponent implements Controllerable
{
    private const DEFAULT_SORT_FIELD = "UF_COURSE_DATE";
    private const DEFAULT_SORT_DIRECTION = "asc";
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_LIMIT = 3;

    public function configureActions(): array
    {
        return [
            'ajaxUpdateList' => [
                'prefilters' => [],
            ]
        ];
    }

    public function ajaxUpdateListAction(int $page, string $sort, string $direction)
    {
        if (!check_bitrix_sessid()) {
            throw new Exception("error ajaxUpdateListAction");
        }

        Loader::includeModule('currency.common');

        return $this->getCurrencyDataHTML($page, $sort, $direction);
    }

    public function getCurrencyData(int $page, string $sort, string $direction): array
    {
        $limit = self::DEFAULT_LIMIT;

        if (!$page || $page < 1) $page = self::DEFAULT_PAGE;
        if ($sort == "") $sort = self::DEFAULT_SORT_FIELD;
        if ($direction == "") $direction = self::DEFAULT_SORT_DIRECTION;

        $offset = ($page - 1) * $limit;

        $arParams = [
            "offset" => $offset,
            "limit" => $limit,
            "order" => [
                $sort => $direction,
            ],
        ];

        // Currency
        $currencyHlb = new HlbCurrency();
        $queryResult = $currencyHlb->getCurrencyList($arParams);

        if (!$queryResult) {
            return [];
        }

        // Navigation
        $queryResult["pages"] = [];

        $totalPages = ceil($queryResult["count_total"] / $limit);
        $startPage = max(1, $page - 1);
        $endPage = min($totalPages, $page + 1);
        $pages = range($startPage, $endPage);

        $queryResult["pages"]["nav"] = $pages;
        $queryResult["pages"]["active"] = $page;

        return $queryResult;
    }

    public function getCurrencyDataHTML(int $page, string $sort, string $direction): string
    {
        $data = $this->getCurrencyData($page, $sort, $direction);
        $table = static::createTable($data["data"]);
        $pagination = static::createPagination($data["pages"]);

        return $table . $pagination;
    }

    protected static function createTable(array $data): string
    {
        if (!$data) {
            return '<h2>' . Loc::getMessage('CURRENCY_EMPTY_DATE') . '</h2>';
        }

        $html = '<table id="currency" class="table">
        <thead>
        <tr>
            <th scope="col">' . Loc::getMessage('CURRENCY_CODE') . '</th>
            <th scope="col">' . Loc::getMessage('CURRENCY_COURSE') . '</th>
            <th scope="col">' . Loc::getMessage('CURRENCY_DATE') . '</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($data as $course) {
            $html .= '<tr>';
            $html .= '<td>' . $course["UF_COURSE_CODE"] . '</td>';
            $html .= '<td>' . $course["UF_COURSE_COURSE"] . '</td>';
            $html .= '<td>' . $course["UF_COURSE_DATE"]->format("d.m.Y") . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    protected static function createPagination(array $data): string
    {
        $html = '';

        if (!$data) {
            return $html;
        }

        $html .= '<nav id="currency-pagination">
                <ul class="pagination justify-content-center">';

        foreach ($data["nav"] as $pageNum) {
            $html .= '<li class="page-item' . $data["active"] == $pageNum ? 'active>' : '';
            $html .= '<button class="page-link" data-page="' . $pageNum . '">' . $pageNum . '</button></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            try {
                $this->arResult = $this->getCurrencyData(1, "", "");
                $this->setResultCacheKeys([]);
                $this->includeComponentTemplate();
            } catch (\Throwable $exception) {
                $this->abortResultCache();
            }
        }
    }
}