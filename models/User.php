<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $fullname
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property int $role
 *
 * @property Request[] $requests
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'username', 'email', 'phone', 'password'], 'required',
                'message' => 'Поле обязательное для заполнения!'],
            [['role'], 'integer'],
            ['role', 'default', 'value' => 0],
            [['fullname'], 'string', 'max' => 50],
            ['fullname', 'match', 'pattern' => '/^[а-яА-Я -]*$/u',
                'message' => 'Только кириллица!'],
            [['username', 'email', 'phone'], 'string', 'max' => 30],
            ['username', 'match', 'pattern' => '/^[A-z]\w*$/i',
                'message' => 'Только латиница!'],
            [['password'], 'string', 'max' => 255],
            [['email'], 'unique'],
            ['email', 'email'],
            [['username'], 'unique'],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'ФИО',
            'username' => 'Логин',
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'password' => 'Пароль',
            'role' => 'Роль',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['id_user' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    public static function findByUsername($username)
    {
        return $identity = User::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function beforeSave($insert)
    {
        $this->password = md5($this->password);
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
