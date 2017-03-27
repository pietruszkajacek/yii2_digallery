<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username_email;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username_email and password are both required
            [['username_email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
			
			['username_email', 'activatedUser'],
            
			// password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username_email' => 'Username or Email',
        ];
    }
		
	public function activatedUser($attribute, $params)
	{
		if (!$this->hasErrors())
		{
			$user = $this->getUser();
			if ($user && $user->status === User::STATUS_NOT_ACTIVATED)
			{
				$this->addError($attribute, 'Your account must be activated first. Please check your email.');
			} 
		}
	}

	/**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username_email]]
     *
     * @return User|null
     */
    public function getUser()
	{
		if ($this->_user === false)
		{
			if (($this->_user = User::findByUsername($this->username_email)) === null)
			{
				$this->_user = User::findByEmail($this->username_email);
			}
		}

		return $this->_user;
	}
}
