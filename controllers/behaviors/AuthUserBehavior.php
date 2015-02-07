<?php

namespace yiicod\auth\controllers\behaviors;

/**
 * Auth behavior with event for controller action
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
use Yii;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

class AuthUserBehavior extends AuthBaseBehavior
{

    /**
     * After login action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function afterLogin($event)
    {
        parent::afterLogin($event);
        
        $event->sender->goHome();
        
        Yii::$app->getResponse()->send();
    }

    /**
     * After signup action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function afterSignUp($event)
    {
        parent::afterSignUp($event);

        if (Yii::$app->getUser()->login($event->params['user'])) {
            
            $event->sender->goHome();
            
            Yii::$app->getResponse()->send();
        }
    }

    /**
     * After RequestPasswordReset action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function afterRequestPasswordReset($event)
    {
        parent::afterRequestPasswordReset($event);

        $mailerViewPath = Yii::$app->mailer->viewPath;

        Yii::$app->mailer->viewPath = '@yiicod/auth/mail';
        Yii::$app->mailer->compose('passwordResetToken', ['action' => $event->action, 'user' => $event->params['model']->findUser()])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($event->params['model']->email)
                ->setSubject('Password reset for ' . Yii::$app->name);       
        
//                ->send();
        Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

        Yii::$app->mailer->viewPath = $mailerViewPath;
        
        $event->sender->goHome();
        
        Yii::$app->getResponse()->send();
    }

    /**
     * After ResetPassword action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel, 'password' => 'Not encrypt password')
     */
    public function afterResetPassword($event)
    {
        Yii::$app->getSession()->setFlash('success', 'New password was saved.');
        
        $event->sender->goHome();
        
        Yii::$app->getResponse()->send();
    }

    /**
     * Before login action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function beforeLogin($event)
    {
        parent::beforeLogin($event);

        if (!Yii::$app->user->isGuest) {
            return $event->sender->goHome();
        }
    }

    /**
     * Before signup action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function beforeSignup($event)
    {
        parent::beforeSignUp($event);
    }

    /**
     * Before RequestPasswordReset action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function beforeRequestPasswordReset($event)
    {
        parent::beforeRequestPasswordReset($event);
    }

    /**
     * Before ResetPassword action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel, 'password' => 'Not encrypt password')
     */
    public function beforeResetPassword($event)
    {
        parent::beforeResetPassword($event);
    }

    /**
     * error ResetPassword action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function errorResetPassword($event)
    {
        parent::errorResetPassword($event);

        throw new BadRequestHttpException($event->params['e']->getMessage());
    }

    /**
     * Error RequestPasswordReset action event
     * @param CEvent $event Object has next params sender -> LoginAction, 
     * params -> array('model' => UserModel)
     */
    public function errorRequestPasswordReset($event)
    {
        parent::errorRequestPasswordReset($event);

        Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
    }

}
