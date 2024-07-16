<?php

namespace micro\models;

use \yii\web\IdentityInterface;
class User implements IdentityInterface
{
    /**
     * Predefined token for testing purpose
     * @var string
     */
    private static $authToken = 'QOIR_i3IhAt51eAZV6TCH9mLUlP7Jdolux54u4K7pMRIfO1s-LrWEdQ1NNLZKZyj';

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token === self::$authToken) {
            return new User();
        }
    }

}