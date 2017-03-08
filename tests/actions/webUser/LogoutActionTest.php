<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\LoginAction;
use yiicod\auth\actions\webUser\LogoutAction;
use yiicod\auth\models\UserModel;
use yiicod\auth\tests\TestCase;

class LogoutActionTest extends TestCase
{
    public function testRun()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'username' => 'test@mail.com',
                'password' => 'password',
                'rememberMe' => true,
            ],
        ];

        $login = new LoginAction('login', $this->createController());
        $login->run();

        $this->assertTrue(is_a(Yii::$app->user->identity, UserModel::class));

        $logout = new LogoutAction('logout', $this->createController());
        $logout->run();

        $this->assertTrue(Yii::$app->user->identity === null);
    }
}
