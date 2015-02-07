<?php

namespace yiicod\auth\controllers;

use Yii;
use yii\web\Controller;

class WebUserBase extends Controller
{

    protected function attachEvent($name, $event)
    {
        if (false === Yii::$app->has('emitter')) {
            return false;
        }
        Yii::$app->emitter->emit($name, array(
            $event
        ));
    }

    /**
     * Action before login
     * @param 
     */
    public function onBeforeLogin($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.beforeLogin', $event);        
        $this->trigger('beforeLogin', $event);
        return $event->isValid;                
    }

    /**
     * Action before signup
     * @param CModel
     */
    public function onBeforeSignup($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.beforeSignup', $event);
        $this->trigger('beforeSignup', $event);
        return $event->isValid;        
    }

    /**
     * Action before forgot
     * @param CModel
     */
    public function onBeforeRequestPasswordReset($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.beforeRequestPasswordReset', $event);
        $this->trigger('beforeRequestPasswordReset', $event);
        return $event->isValid;         
    }

    /**
     * Action before check recovery key
     * @param CModel
     */
    public function onBeforeResetPassword($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.beforeResetPassword', $event);
        $this->trigger('beforeResetPassword', $event);
        return $event->isValid;
    }

    public function onBeforeLogout($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.beforeLogout', $event);
        $this->trigger('beforeLogout', $event);
        return $event->isValid;
    }

    /**
     * Action after forgot
     * @param CModel
     */
    public function onAfterRequestPasswordReset($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.afterRequestPasswordReset', $event);
        $this->trigger('afterRequestPasswordReset', $event);
        return $event->isValid;
    }

    /**
     * Action after check recovery key
     * @param CModel
     */
    public function onAfterResetPassword($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.afterResetPassword', $event);
        $this->trigger('afterResetPassword', $event);
        return $event->isValid;
    }

    /**
     * Action after login
     * @param CModel
     */
    public function onAfterLogin($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.afterLogin', $event);
        $this->trigger('afterLogin', $event);  
    }

    /**
     * Action after signup
     * @param CModel
     */
    public function onAfterSignup($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.afterSignup', $event);
        $this->trigger('afterSignup', $event);
    }

    public function onAfterLogout($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.afterLogout', $event);
        $this->trigger('afterLogout', $event);
    }

    /**
     * Action error check recovery key
     * @param CModel
     */
    public function onErrorRequestPasswordReset($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.errorRequestPasswordReset', $event);
        $this->trigger('errorRequestPasswordReset', $event);
    }

    /**
     * Action error forgot
     * @param CModel
     */
    public function onErrorResetPassword($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.errorResetPassword', $event);
        $this->trigger('errorResetPassword', $event);   
    }

    /**
     * Action error check recovery key
     * @param CModel
     */
    public function onErrorLogin($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.errorLogin', $event);
        $this->trigger('errorLogin', $event);  
    }

    /**
     * Action error forgot
     * @param CModel
     */
    public function onErrorSignup($event)
    {
        $this->attachEvent('yiicod.auth.controllers.WebUserBase.errorSignup', $event);
        $this->trigger('errorSignup', $event);  
    }

}
