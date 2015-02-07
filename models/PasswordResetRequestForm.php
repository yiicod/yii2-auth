<?php

namespace yiicod\auth\models;

use Yii;
use yiicod\auth\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;
    /**
     *
     * @var type User model
     */
    private $_user = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => Yii::$app->get('auth')->modelMap['User']['class'],
                'targetAttribute' => Yii::$app->get('auth')->modelMap['User']['fieldEmail'],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    public function findUser()
    {
        if (null === $this->_user) {            
            $userClass = Yii::$app->get('auth')->modelMap['User']['class'];

            $this->_user = $userClass::findOne(ArrayHelper::merge(['email' => $this->email], Yii::$app->get('auth')->condition));
        }
        return $this->_user;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function resetPassword()
    {
        /* @var $user User */
        $user = $this->findUser();

        if ($user) {
            $user->generatePasswordResetToken();
            return $user->save();
        }

        return false;
    }

}
