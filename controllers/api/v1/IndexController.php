<?php

namespace micro\controllers\api\v1;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

class IndexController extends Controller
{

    /**
     * @return array[]
     */
    public function behaviors(){
        return [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    HttpBearerAuth::class,
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        /** @var yii\web\Request $request */
        $request = Yii::$app->request;
        $method = $request->getQueryParam('method');
        if ($method !== 'rates') {
            return $this->prepareErrorResult('Invalid method');
        }
        $currency = $request->getQueryParam('currency');
        $currency = $currency ? explode(',', $currency) : null;
        $result = Yii::$app->coinCap->getSortedRatesWithCommission($currency);

        return $this->prepareSuccessResult($result);
    }

    /**
     * @param $result
     * @return array
     */
    protected function prepareSuccessResult($result) {
        return [
            'status' => 'success',
            'code' => 200,
            'data' => $result
        ];
    }

    /**
     * @param string $message
     * @return array
     */
    protected function prepareErrorResult(string $message) {
        return [
            'status' => 'error',
            'code' => 400,
            'message' => $message
        ];
    }
}
