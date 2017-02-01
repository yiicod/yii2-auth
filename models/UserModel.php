<?php

namespace yiicod\auth\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $role
 * @property int $status
 * @property int $updated_at
 * @property string $password write-only password
 */
class UserModel extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
        return static::find()
            ->where(['id' => $id])
            ->identity()
            ->one();
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
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where([static::attributesMap()['fieldLogin'] => $username])
            ->byUsername()
            ->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (false === static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()
            ->where([static::attributesMap()['fieldPasswordResetToken'] => $token])
            ->byPasswordResetToken()
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @return mixed
     */
    public static function find()
    {
        /** @var UserQueryInterface $userQueryclass */
        $userQueryclass = Yii::$app->get('auth')->modelMap['userQuery']['class'];

        return new $userQueryclass(get_called_class());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     *
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        try {
            return Yii::$app->security->validatePassword(
                $password, $this->password
            );
        } catch (InvalidParamException $e) {
            return false;
        }
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
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function attributesMap()
    {
        return [
            'fieldLogin' => 'email', //requred
            'fieldEmail' => 'email', //requred
            'fieldPassword' => 'password_hash', //requred
            'fieldAuthKey' => 'auth_key',
            'fieldUsername' => 'username',
            'fieldStatus' => 'status',
            'fieldPasswordResetToken' => 'password_reset_token', //requred
            'fieldCreatedDate' => 'created_date', //or null
            'fieldUpdatedDate' => 'updated_date', //or null
        ];
    }

    public function behaviors()
    {
        return [
            'attributesMapBehavior' => [
                'class' => '\yiicod\base\models\behaviors\AttributesMapBehavior',
                'attributesMap' => static::attributesMap(),
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => static::attributesMap()['fieldCreatedDate'],
                'updatedAtAttribute' => static::attributesMap()['fieldUpdatedDate'],
            ],
        ];
    }
}
