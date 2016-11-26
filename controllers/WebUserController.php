<?php

namespace yiicod\auth\controllers;

use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * WebUser controller
 *
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
class WebUserController extends Controller
{
    public $defaultAction = 'login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'logout', 'requestPasswordReset', 'signup', 'resetPassword'],
                    ],
                ],
            ],
            'authUserBehavior' => [
                'class' => \yiicod\auth\controllers\behaviors\AuthUserBehavior::className(),
            ],
        ];
    }

    /**
     * Declares class-based actions.
     * For change functional use AuthUserBehavior, This is component
     * declaret in to config.php, you can change him.
     * Auth event:
     *
     * - beforeLogin(ActionEvent)
     * - afterLogin(ActionEvent)
     * - errorLogin(ActionEvent)
     *
     * - beforeSignup(ActionEvent)
     * - afterSignup(ActionEvent)
     * - errorSignup(ActionEvent)
     *
     * - beforeCheckRecoveryKey(ActionEvent)
     * - afterCheckRecoveryKey(ActionEvent)
     * - errorCheckRecoveryKey(ActionEvent)
     *
     * - beforeForgot(ActionEvent)
     * - afterForgot(ActionEvent)
     * - errorForgot(ActionEvent)
     *
     * - beforeLogout(ActionEvent)
     * - afterLogout(ActionEvent)
     *
     * Also if you use extensions evenement, use this events,
     * Insert into listeners.php:
     *
     * ...
     * yiicod.auth.controllers.WebUserBase.[All event name before]
     * ...
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
                'login' => [
                    'class' => \yiicod\auth\actions\webUser\LoginAction::className(),
                ],
                'requestPasswordReset' => [
                    'class' => \yiicod\auth\actions\webUser\RequestPasswordResetAction::className(),
                ],
                'logout' => [
                    'class' => \yiicod\auth\actions\webUser\LogoutAction::className(),
                ],
                'signup' => [
                    'class' => \yiicod\auth\actions\webUser\SignupAction::className(),
                ],
                'resetPassword' => [
                    'class' => \yiicod\auth\actions\webUser\ResetPasswordAction::className(),
                ],
            ]
        );
    }
}
