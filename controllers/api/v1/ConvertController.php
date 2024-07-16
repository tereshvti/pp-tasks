<?php

namespace micro\controllers\api\v1;

use Yii;

class ConvertController extends IndexController
{
    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function actionIndex()
    {
        /** @var yii\web\Request $request */
        $request = Yii::$app->request;
        $method = $request->getQueryParam('method');
        if ($method !== 'convert') {
            return $this->prepareErrorResult('Invalid method');
        }

        foreach (['currency_from', 'currency_to', 'value'] as $paramName) {
            if (!$request->getQueryParam($paramName)) {
                return $this->prepareErrorResult("Missing parameter `$paramName`");
            }
        }

        try {
            $result = Yii::$app->coinCap->convertAmount(
                $request->getQueryParam('currency_from'),
                $request->getQueryParam('currency_to'),
                (float) $request->getQueryParam('value')
            );
        } catch (\Exception $e) {
            return $this->prepareErrorResult($e->getMessage());
        }


        return $this->prepareSuccessResult($result);
    }
}
