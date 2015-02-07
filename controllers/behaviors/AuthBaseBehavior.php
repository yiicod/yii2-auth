<?php

namespace yiicod\auth\controllers\behaviors;

use yii\base\Behavior;

/**
 * Base auth behavior, with all declarate events
 * @author Orlov Alexey <aaorlov@gmail.com>
 */


class AuthBaseBehavior extends Behavior
{

    /**
     * Declares events and the corresponding event handler methods.
     * If you override this method, make sure you merge the parent result to the return value.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     * @see CBehavior::events
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'beforeLogin' => 'beforeLogin',
            'beforeSignup' => 'beforeSignup',
            'beforeRequestPasswordReset' => 'beforeRequestPasswordReset',
            'beforeResetPassword' => 'beforeResetPassword',
            'beforeLogout' => 'beforeLogout',
            'afterRequestPasswordReset' => 'afterRequestPasswordReset',
            'afterResetPassword' => 'afterResetPassword',
            'afterLogin' => 'afterLogin',
            'afterSignup' => 'afterSignup',
            'afterLogout' => 'afterLogout',
            'errorResetPassword' => 'errorResetPassword',
            'errorRequestPasswordReset' => 'errorRequestPasswordReset',
            'errorLogin' => 'errorLogin',
            'errorSignup' => 'errorSignup',
        ));
    }

    /**
     * Action before login
     * @param 
     */
    public function beforeLogin($event)
    {
        
    }

    /**
     * Action before signup
     * @param CEvent
     */
    public function beforeSignUp($event)
    {
        
    }

    /**
     * Action before RequestPasswordReset
     * @param CEvent
     */
    public function beforeRequestPasswordReset($event)
    {
        
    }

    /**
     * Action before check recovery key
     * @param CEvent
     */
    public function beforeResetPassword($event)
    {
        
    }

    public function beforeLogout($event)
    {
        
    }

    /**
     * Action after RequestPasswordReset
     * @param CEvent
     */
    public function afterRequestPasswordReset($event)
    {
        
    }

    /**
     * Action after check recovery key
     * @param CEvent
     */
    public function afterResetPassword($event)
    {
        
    }

    /**
     * Action after login
     * @param CEvent
     */
    public function afterLogin($event)
    {
        
    }

    /**
     * Action after signup
     * @param CEvent
     */
    public function afterSignUp($event)
    {
        
    }

    public function afterLogout($event)
    {
        
    }

    /**
     * Action error check recovery key
     * @param CEvent
     */
    public function errorResetPassword($event)
    {
        
    }

    /**
     * Action error RequestPasswordReset
     * @param CEvent
     */
    public function errorRequestPasswordReset($event)
    {
        
    }

    /**
     * Action error check recovery key
     * @param CEvent
     */
    public function errorLogin($event)
    {
        
    }

    /**
     * Action error RequestPasswordReset
     * @param CEvent
     */
    public function errorSignup($event)
    {
        
    }

}
