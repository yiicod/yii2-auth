<?php

namespace yiicod\auth\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status 
 * @property integer $updated_at
 * @property string $password write-only password
 */
class UserModel extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(ArrayHelper::merge(['id' => $id], Yii::$app->get('auth')->condition));
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(ArrayHelper::merge([
                            Yii::$app->get('auth')->modelMap['User']['fieldLogin'] => $username
                                ], Yii::$app->get('auth')->condition));
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = isset(Yii::$app->params['user.passwordResetTokenExpire']) ?
                Yii::$app->params['user.passwordResetTokenExpire'] :
                3600;
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne(ArrayHelper::merge([
                            Yii::$app->get('auth')->modelMap['User']['fieldPasswordResetToken'] => $token,
                                ], Yii::$app->get('auth')->condition));
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttr('authKey');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
                        $password, $this->password
        );
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function generatePassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->passwordResetToken = null;
    }

    public function behaviors()
    {
        return [
            'userBehavior' => [
                'class' => 'yiicod\auth\models\behaviors\UserBehavior'
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => Yii::$app->get('auth')->modelMap['User']['fieldCreatedDate'],
                'updatedAtAttribute' => Yii::$app->get('auth')->modelMap['User']['fieldUpdatedDate'],
            ]
        ];
    }

}
