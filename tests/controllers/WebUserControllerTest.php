<?php
/**
 * Created by PhpStorm.
 * User: lexx
 * Date: 3/19/17
 * Time: 9:42 PM
 */

namespace tests\controllers;

use Yii;
use yii\base\ExitException;
use yiicod\auth\controllers\WebUserController;
use yiicod\auth\models\UserModel;
use yiicod\auth\tests\data\Module;
use yiicod\auth\tests\TestCase;

class WebUserControllerTest extends TestCase
{
    public function testLogin()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'username' => 'test@mail.com',
                'password' => 'password',
                'rememberMe' => true,
            ],
        ];

        $controller = $this->mockController();
        try {
            $controller->runAction('login');
        } catch (ExitException $e) {
            $this->assertTrue(Yii::$app->response->getIsRedirection());
        }
    }

    public function testSignup()
    {
        Yii::$app->request->bodyParams = [
            'SignupForm' => [
                'username' => 'new-user',
                'password' => 'new-password',
                'confirm' => 'new-password',
                'email' => 'new-user@mail.com',
            ],
        ];

        $controller = $this->mockController();
        try {
            $controller->runAction('signup');
        } catch (ExitException $e) {
            $this->assertTrue(Yii::$app->response->getIsRedirection());
        }
    }

    /**
     * @depends testSignup
     */
    public function testRequestPasswordReset()
    {
        Yii::$app->request->bodyParams = [
            'PasswordResetRequestForm' => [
                'email' => 'test@mail.com',
            ],
        ];

        $controller = $this->mockController();
        try {
            $controller->runAction('request-password-reset');
        } catch (ExitException $e) {
            //            $this->assertTrue(Yii::$app->getSession()->getFlash('success') === 'Check your email for further instructions.');
            $this->assertTrue(Yii::$app->response->getIsRedirection());
        }
    }

    /**
     * @depends testRequestPasswordReset
     */
    public function testResetPassword()
    {
        Yii::$app->request->bodyParams = [
            'ResetPasswordForm' => [
                'password' => '123123',
            ],
        ];

        $controller = $this->mockController();
        try {
            $user = UserModel::findOne(['email' => 'reset-password@mail.com']);

            $controller->runAction('reset-password', ['token' => $user->password_reset_token]);
        } catch (ExitException $e) {
            //            $this->assertTrue(Yii::$app->getSession()->getFlash('success') === 'New password was saved.');
            $this->assertTrue(Yii::$app->response->getIsRedirection());
        }
    }

    /**
     * @depends testLogin
     */
    public function testLogout()
    {
        $controller = $this->mockController();
        $controller->runAction('logout');
        $this->assertTrue(Yii::$app->response->getIsRedirection());
    }

    private function mockController()
    {
        $module = new Module('test');
        $controller = new WebUserController('web-user', $module);

        Yii::$app->controller = $controller;

        return $controller;
    }
}
