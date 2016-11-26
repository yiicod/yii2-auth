<?php

namespace yiicod\auth\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    /**
     * @var type User model
     */
    private $_user = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $targetClass = Yii::$app->get('auth')->modelMap['user']['class'];

        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $targetClass,
                'targetAttribute' => $targetClass::attributesMap()['fieldEmail'],
                'message' => 'There is no user with such email.',
            ],
        ];
    }

    public function findUser()
    {
        if (null === $this->_user) {
            $userClass = Yii::$app->get('auth')->modelMap['user']['class'];

            $this->_user = $userClass::find()
                ->where(['email' => $this->email])
                ->byPasswordResetRequest()
                ->one();
        }

        return $this->_user;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
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
