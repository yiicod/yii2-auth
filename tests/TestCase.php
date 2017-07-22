<?php

namespace yiicod\auth\tests;

use Yii;
use yii\db\sqlite\Schema;
use yii\helpers\ArrayHelper;
use yiicod\auth\tests\data\Controller;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();

        $this->setupTestDbData();
    }

    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        @mkdir($this->getVendorPath() . '/tmp', 0777, true);
        session_save_path($this->getVendorPath() . '/tmp');
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'bootstrap' => ['auth'],
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
                'user' => [
                    'identityClass' => 'yiicod\auth\models\UserModel',
                ],
                'session' => [
                    'class' => 'yii\web\CacheSession',
                ],
                'auth' => [
                    'class' => 'yiicod\auth\Auth',
                ],
                'cache' => [
                    'class' => 'yii\caching\ArrayCache',
                ],
                'i18n' => [
                    'translations' => [
                        'yiicod.auth' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                        ],
                    ],
                ],
            ],
            'params' => [
                'user.passwordResetTokenExpire' => 1000,
                'supportEmail' => 'support@example.com',
            ],
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * @param array $config controller config
     *
     * @return Controller controller instance
     */
    protected function createController($config = [])
    {
        return new Controller('test', Yii::$app, $config);
    }

    /**
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :
        $db->createCommand()->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'created_date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL',
        ])->execute();

        $db->createCommand()->insert('{{%user}}', [
            'username' => 'test',
            'email' => 'test@mail.com',
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('password'),
            'status' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
        ])->execute();

        $db->createCommand()->insert('{{%user}}', [
            'username' => 'reset-password',
            'email' => 'reset-password@mail.com',
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('password'),
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
        ])->execute();
    }
}
