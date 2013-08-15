<?php

class GeneralSettings extends CiiSettingsModel
{
	protected $name = NULL;

	protected $dateFormat = 'F jS, Y';

	protected $timeFormat = 'H:i';

	protected $timezone = "UTC";

	protected $defaultLanguage = 'en_US';

	protected $url = NULL;

	protected $menu = 'admin|blog';

	protected $offline = 0;

	protected $preferMarkdown = 1;

	protected $bcrypt_cost = 13;

	protected $searchPaginationSize = 10;

	protected $categoryPaginationSize = 10;

	protected $contentPaginationSize = 10;

	protected $autoApproveComments = 1;

	protected $notifyAuthorOnComment = 1;

	protected $sphinx_enabled = 0;

	protected $sphinxHost = 'localhost';

	protected $sphinxPort = 9312;

	protected $sphinxSource = NULL;

	public function getSubdomain()
	{
		return Yii::app()->params['user'];
	}

	public function groups()
	{
		return array(
			Yii::t('Dashboard.models-general', 'Site Settings') => array('name', 'url', 'subdomain', 'menu', 'offline', 'preferMarkdown', 'bcrypt_cost', 'categoryPaginationSize','contentPaginationSize','searchPaginationSize'),
			Yii::t('Dashboard.models-general', 'Display Settings') => array('dateFormat', 'timeFormat', 'timezone', 'defaultLanguage'),
			Yii::t('Dashboard.models-general', 'Sphinx') => array('sphinx_enabled', 'sphinxHost', 'sphinxPort', 'sphinxSource'),
			Yii::t('Dashboard.models-general', 'Comments') => array('notifyAuthorOnComment', 'autoApproveComments'),
		);
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, dateFormat, timeFormat, timezone, defaultLanguage', 'required'),
			array('name, menu', 'length', 'max' => 255),
			array('dateFormat, timeFormat, timezone, defaultLanguage', 'length', 'max' => 25),
			array('offline, preferMarkdown, sphinx_enabled, notifyAuthorOnComment, autoApproveComments', 'boolean'),
			array('sphinxHost, sphinxSource', 'length', 'max' => 255),
			array('sphinxPort', 'numerical', 'integerOnly' => true),
			array('bcrypt_cost', 'numerical', 'integerOnly'=>true, 'min' => 13, 'max' => 50),
			array('searchPaginationSize, categoryPaginationSize, contentPaginationSize', 'numerical', 'integerOnly' => true, 'min' => 1, 'max' => 100),
			array('url', 'url')
		);
	}

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('Dashboard.models-general', 'Site Name'),
			'dateFormat' => Yii::t('Dashboard.models-general', 'Date Format'),
			'timeFormat' => Yii::t('Dashboard.models-general', 'Time Format'),
			'timezone' => Yii::t('Dashboard.models-general', 'Timezone'),
			'defaultLanguage' => Yii::t('Dashboard.models-general', 'Default Language'),
			'url' => Yii::t('Dashboard.models-general', 'Site URL'),
			'subdomain' => Yii::t('Dashboard.models-general', 'CiiMS Subdomain'),
			'menu' => Yii::t('Dashboard.models-general', 'Menu Navigation'),
			'offline' => Yii::t('Dashboard.models-general', 'Offline Mode'),
			'preferMarkdown' => Yii::t('Dashboard.models-general', 'Use Markdown'),
			'bcrypt_cost' => Yii::t('Dashboard.models-general', 'Password Strength Settings'),
			'searchPaginationSize' => Yii::t('Dashboard.models-general', 'Search Post Count'),
			'categoryPaginationSize' => Yii::t('Dashboard.models-general', 'Category Post Count'),
			'contentPaginationSize' => Yii::t('Dashboard.models-general', 'Content Post Cost'),
			'sphinx_enabled' => Yii::t('Dashboard.models-general', 'Enable Sphinx Search'),
			'sphinxHost' => Yii::t('Dashboard.models-general', 'Sphinx Hostname'),
			'sphinxPort' => Yii::t('Dashboard.models-general', 'Sphinx Port'),
			'sphinxSource' => Yii::t('Dashboard.models-general', 'Sphinx Source Name'),
			'notifyAuthorOnComment' => Yii::t('Dashboard.models-general', 'Notify Author on New Comment'),
			'autoApproveComments'	=> Yii::t('Dashboard.models-general', 'Auto Approve Comments'),
		);
	}
}