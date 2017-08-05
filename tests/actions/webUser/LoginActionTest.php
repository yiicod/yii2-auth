<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\LoginAction;
use yiicod\auth\models\UserModel;
use yiicod\auth\tests\TestCase;

class LoginActionTest extends TestCase
{
    //    /**
    //     * @expectedException \yii\base\InvalidConfigException
    //     */
    //    public function testCheckIncorrectAction()
    //    {
    //
    //    }

    public function testIncorrectCredential()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'username' => 'user@gmail.com',
                'password' => '123123',
                'rememberMe' => true,
            ],
        ];

        $response = $this->mockAction();

        $this->assertTrue(true);
        $this->assertTrue('Incorrect username or password.' === $response['params']['model']->getErrors()['password'][0]);
    }

    public function testRun()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'username' => 'test@mail.com',
                'password' => 'password',
                'rememberMe' => true,
            ],
        ];

        $response = $this->mockAction([]);

        $this->assertTrue(is_a(Yii::$app->user->identity, UserModel::class));
    }

    /**
     * Runs the action.
     *
     * @param array $config
     *
     * @return string
     */
    protected function mockAction(array $config = [])
    {
        $action = new LoginAction('login', $this->createController(), $config);

        return $action->run();
    }
}
