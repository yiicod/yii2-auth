<?php

namespace yiicod\auth\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * WebUser controller
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
class WebUserController extends WebUserBase
{

    public $defaultAction = 'login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $module = Yii::$app->controller->module === null ? 'default' : Yii::$app->controller->module->id;
        $module = Yii::$app->controller->module->id === Yii::$app->id ? 'default' : $module;
        return Yii::$app->get('auth')->controllers[$module][Yii::$app->controller->id]['behaviors'];
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
     * 
     */
    public function actions()
    {

        return ArrayHelper::merge(parent::actions(), array(
                    'login' => array(
                        'class' => 'yiicod\auth\actions\webUser\LoginAction',
                    ),
                    'requestPasswordReset' => array(
                        'class' => 'yiicod\auth\actions\webUser\RequestPasswordResetAction',
                    ),
                    'logout' => array(
                        'class' => 'yiicod\auth\actions\webUser\LogoutAction',
                    ),
                    'signup' => array(
                        'class' => 'yiicod\auth\actions\webUser\SignupAction',
                    ),
                    'resetPassword' => array(
                        'class' => 'yiicod\auth\actions\webUser\ResetPasswordAction',
                    ),
                        )
        );
    }

}
