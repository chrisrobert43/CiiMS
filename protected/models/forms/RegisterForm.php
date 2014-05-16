<?php

class RegisterForm extends CFormModel
{
	/**
	 * The submitted email address
	 * @var string|email
	 */
	public $email;

	/**
	 * The submitted password
	 * @var string
	 */
	public $password;

	/**
	 * The password verification
	 * @var string
	 */
	public $password_repeat;

	/**
	 * The submitted first name if it was supplied
	 * @var string
	 */
	public $firstName;

	/**
	 * The submitted last name if it was supplied
	 * @var string
	 */
	public $lastName;

	/**
	 * The display name as we will show it on the site
	 * @var string
	 */
	public $displayName;

    /**
     * The user model
     * @param Users $_user
     */
    protected $_user;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, password, password_repeat, displayName', 'required'),
			array('password', 'compare'),
			array('password', 'length', 'min'=>8),
			array('email', 'email'),
            array('email', 'isEmailUnique')
		);
	}

    /**
     * Determines if an email is already taken or not
     * @param array $attributes
     * @param array $params
     * @return boolean
     */
    public function isEmailUnique($attributes, $params)
    {
        $this->_user = Users::model()->findByAttributes(array('email' => $this->email));

        if ($this->_user != NULL)
        {
            $this->addError('email', Yii::t('ciims.models.RegisterForm', 'That email address is already in use'));
            return false;
        }

        return true;
    }

    /**
     * Model attribute labels
     * @return array
     */
	public function attributeLabels()
	{
		return array(
			'email'           => Yii::t('ciims.models.RegisterForm', 'Your Email Address'),
			'password'        => Yii::t('ciims.models.RegisterForm', 'Your Password'),
			'password_repeat' => Yii::t('ciims.models.RegisterForm', 'Your Password (again)'),
			'displayName'     => Yii::t('ciims.models.RegisterForm', 'Your Display Name on the Site')
		);
	}

    /**
     * Creates a new user, and sends the appropriate messaging out
     * @return boolean
     */
    public function save($sendEmail = true)
    {
        if (!$this->validate())
            return false;

        $this->_user = new Users;

        // Set the model attributes
        $this->_user->attributes = array(
            'email'       => $this->email,
            'password'    => $this->password,
            'firstName'   => NULL,
            'lastName'    => NULL,
            'displayName' => $this->displayName,
            'user_role'   => 1,
            'status'      => $sendEmail ? Users::PENDING_INVITATION : Users::ACTIVE
        );

        // If we saved the user model, return true
        if($this->_user->save())
        {
            // This class my be extended by other modules, in which case we don't need to send an activation form if we don't want need it to.
            if ($sendEmail)
            {
                $factory = new CryptLib\Random\Factory;
                $meta = new UserMetadata;
                $meta->attributes = array(
                    'user_id' => $this->_user->id,
                    'key'     => 'activationKey',
                    'value'   => str_replace('/', '', $factory->getHighStrengthGenerator()->generateString(16))
                );
                $meta->save();

                // Send the registration email
                Yii::app()->controller->sendEmail($this->_user, Yii::t('ciims.email','Activate Your Account'), 'webroot.themes.' . Cii::getConfig('theme', 'default') .'.views.email.register', array('user' => $this->_user, 'hash' => $meta->value), true, true);
            }

            return true;
        }

        return false;
    }
}
