<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\RequestPasswordResetAction;
use yiicod\auth\models\UserModel;
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

        $response = $this->runAction();

        $this->assertTrue($response['params']['model']->hasErrors());
    }

    public function testRun()
    {
        Yii::$app->request->bodyParams = [
            'PasswordResetRequestForm' => [
                'email' => 'test@mail.com',
            ],
        ];

        $model = UserModel::findOne(['email' => 'test@mail.com']);

        $this->assertTrue(is_null($model->password_reset_token) === true);

        $response = $this->runAction();

        $this->assertTrue(empty($response['params']['model']->findUser()->password_reset_token) === false);
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
        $action = new RequestPasswordResetAction('requestPasswordReset', $this->createController(), $config);

        return $action->run();
    }
}
