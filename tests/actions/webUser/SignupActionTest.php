<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\SignupAction;
use yiicod\auth\tests\TestCase;

class SignupActionTest extends TestCase
{
    public function testIncorrectCredential()
    {
        Yii::$app->request->bodyParams = [
            'SignupForm' => [
            ],
        ];
        $response = $this->runAction();

        $this->assertTrue($response['params']['model']->hasErrors());
    }

    public function testRun()
    {
        Yii::$app->request->bodyParams = [
            'SignupForm' => [
                'username' => 'new-user',
                'password' => 'new-password',
                'confirm' => 'new-password',
                'email' => 'new-user@mail.com',
            ],
        ];

        $responce = $this->runAction();

        $this->assertTrue(count($responce['params']['model']->getErrors()) <= 0);
        $this->assertTrue(is_null($responce['params']['user']) === false);
    }

    /**
     * Runs the action.
     *
     * @param array $config
     *
     * @return string
     */
    protected function runAction(array $config = [])
    {
        $action = new SignupAction('signupAction', $this->createController());

        return $action->run();
    }
}
