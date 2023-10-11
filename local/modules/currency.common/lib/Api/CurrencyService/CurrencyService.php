<?php

namespace Currency\Common\Api\CurrencyService;

use SimpleXMLElement;
use SoapClient;

class CurrencyService
{
    private $client;
    private const CBR_WSDL = "http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?wsdl";
    private const CBR_CURS_METHOD = "GetCursOnDateXML";

    public function __construct()
    {
        $this->client = new SoapClient(static::CBR_WSDL);
    }

    public function getCursOnDate($date)
    {
        $params = [
            'On_date' => $date
        ];
        $response = $this->client->__soapCall(static::CBR_CURS_METHOD, [$params]);
        return $response->GetCursOnDateXMLResult->any;
    }

    public function parseCurrencies($xml)
    {
        $currencies = new SimpleXMLElement($xml);
        $result = [];
        $date = \DateTime::createFromFormat('Ymd', $currencies->attributes()['OnDate'])->format('d.m.Y');
        foreach ($currencies->ValuteCursOnDate as $currency) {
            $result[] = [
                "UF_COURSE_CODE" => (string)$currency->VchCode,
                "UF_COURSE_COURSE" => (double)$currency->Vcurs,
                "UF_COURSE_DATE" => $date,
            ];
        }
        return $result;
    }
}