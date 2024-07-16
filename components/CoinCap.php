<?php

namespace micro\components;

use yii\httpclient\Client;
use yii\web\BadRequestHttpException;
use \Yiisoft\Json\Json;

class CoinCap
{
    const RATES_URL = 'https://api.coincap.io/v2/rates';

    private $request;

    public $commission = 0;

    public $minValue = 0;

    /**
     * @param $client
     */
    public function __construct()
    {
        $client = new Client();
        $this->request = $client->createRequest()->setMethod('GET')
            ->setUrl(self::RATES_URL)
            ->addHeaders(['content-type' => 'application/json']);
    }

    /**
     * @param $currency
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \JsonException
     * @throws \yii\httpclient\Exception
     */
    private function getRates()
    {
        $response = $this->request->send();
        if ($response->getStatusCode() == 200) {
            $json = (string) ($response->getContent());
            $jsonData = Json::decode($json);
            if (!isset($jsonData['data'])) {
                throw new BadRequestHttpException('CoinCap failed to answer');
            }
            return $jsonData['data'];
        } else {
            throw new BadRequestHttpException('Bad request from CoinCap');
        }
    }

    /**
     * @param $currencyCodes
     * @return array
     * @throws BadRequestHttpException
     * @throws \JsonException
     * @throws \yii\httpclient\Exception
     */
    public function getSortedRatesWithCommission($currencyCodes) {
        $rates = $this->getRates();
        $result = [];
        foreach ($rates as $rate) {
            if (isset($rate['symbol']) && isset($rate['rateUsd']) &&
                (is_null($currencyCodes) || in_array($rate['symbol'], $currencyCodes))
            ) {
                $result[$rate['symbol']] = round($rate['rateUsd'] * (1 + $this->commission), 10);
            }
        }
        asort($result);

        return $result;
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $value
     * @return array
     * @throws BadRequestHttpException
     * @throws \JsonException
     * @throws \yii\httpclient\Exception
     */
    public function convertAmount($currencyFrom, $currencyTo, $value) {
        if ($currencyFrom == 'USD') {
            $targetCurrency = $currencyTo;
        } elseif ($currencyTo == 'USD') {
            $targetCurrency = $currencyFrom;
        } else {
            throw new \Exception('Invalid currency parameters');
        }

        if ($value < $this->minValue) {
            throw new \Exception('Minimal value is ' . $this->minValue);
        }
        $rateData = $this->getSortedRatesWithCommission([$targetCurrency]);
        if (!isset($rateData[$targetCurrency])) {
            throw new \Exception('Invalid response from Coincap');
        }
        $rateToUsd = $rateData[$targetCurrency];
        if ($currencyFrom === 'USD') {
            $rate = 1 / $rateToUsd;
            $convertedValue = round($value * $rate, 10);
        } else {
            $rate = $rateToUsd;
            $convertedValue = round($value * $rate, 2);
        }
        $result = [
            'currency_from' => $currencyFrom,
            'currency_to' => $currencyTo,
            'value' => $value,
            'converted_value' => $convertedValue,
            'rate' => $rate,
        ];

        return $result;
    }
}
