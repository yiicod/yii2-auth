<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\RequestPasswordResetAction;
use yiicod\auth\tests\TestCase;

class RequestPasswordResetTest extends TestCase
{
    public function testIncorrectCredential()
    {
        Yii::$app->request->bodyParams = [
            'PasswordResetRequestForm' => [
                'email' => 'incorrect@mail.com',
            ],
        ];

        $response = $this->mockAction();

        $this->assertTrue($response['params']['model']->hasErrors());
    }

    public function testRun()
    {
        Yii::$app->request->bodyParams = [
            'PasswordResetRequestForm' => [
                'email' => 'test@mail.com',
            ],
        ];

        $response = $this->mockAction();

        $this->assertTrue(empty($response['params']['model']->getErrors()));
        $this->assertTrue(false === empty($response['params']['model']->findUser()->password_reset_token));
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
        $action = new RequestPasswordResetAction('requestPasswordReset', $this->createController(), $config);

        return $action->run();
    }
}
