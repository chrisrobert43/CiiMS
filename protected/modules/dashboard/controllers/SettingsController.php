<?php

/**
 * This controller provides basic settings control and management for all basic CiiMS settings
 *
 * Actions in this controller should take advantage of CiiSettingsModel and classes extended from it
 * for automatic form construction, management, and validation
 */
class SettingsController extends CiiSettingsController
{
	/**
	 * Provides "general" settings control
	 * @class GeneralSettings
	 */
	public function actionIndex()
	{
		$model = new GeneralSettings;
		
		$this->submitPost($model);

		$this->render('form', array('model' => $model, 'header' => array(
			'h3' => 'General Settings', 
			'p' => 'Set basic information about your site and change global settings.',
			'save-text' => 'Save Changes'
		)));
	}

	/**
	 * Provides basic email control
	 * @class EmailSettings
	 */
	public function actionEmail()
	{
		$model = new EmailSettings;
		
		$this->submitPost($model);

		$this->render('form', array('model' => $model, 'header' => array(
			'h3' => 'Email Settings', 
			'p' => 'Configure and verify how CiiMS sends emails',
			'save-text' => 'Save Changes'
		)));
	}

	/**
	 * Provides "social" settings control
	 * @class GeneralSettings
	 */
	public function actionSocial()
	{
		$model = new SocialSettings;
		
		$this->submitPost($model);

		$this->render('form', array('model' => $model, 'header' => array(
			'h3' => 'Social Settings', 
			'p' => 'Provide Credentials for accessing and submitting data to various third party social media sites.',
			'save-text' => 'Save Changes'
		)));
	}

	/**
	 * Provides "general" settings control
	 * @class GeneralSettings
	 */
	public function actionAnalytics()
	{
		$model = new AnalyticsSettings;
		
		$this->submitPost($model);

		$this->render('form', array('model' => $model, 'header' => array(
			'h3' => 'Analytics Settings', 
			'p' => 'Enable and configure various Analytics providers',
			'save-text' => 'Save Changes'
		)));
	}

	/**
	 * Provides theme control settings
	 * @class ThemeSettings
	 */
	public function actionAppearance()
	{
		$model = new ThemeSettings;
		
		$this->submitPost($model);

		$this->render('form', array('model' => $model, 'header' => array(
			'h3' => 'Appearance', 
			'p' => 'Change the site theme for desktop, tablet, and mobile',
			'save-text' => 'Save Theme'
		)));
	}

	public function actionCards()
	{

	}
	
	public function actionSystem()
	{
		$this->render('system', array('header' => array(
			'h3' => 'System Information',
			'p' => 'View system and diagnostic information'
		)));
	}

	public function actionIssues()
	{
		$issues = array();
		
		// Check CiiMS version
		
		// Check if migrations have been run
		
		// Check common permission problems
	}

	/**
	 * Flushes the Yii::cache.
	 * @return bool    If the cache flush was successful or not
	 */
	public function actionFlushCache()
	{
		return Yii::app()->cache->flush();
	}

	/**
	 * Provides functionality to send a test email
	 */
	public function actionEmailTest()
	{
		if (Cii::get($_POST, 'email') !== NULL)
		{
			// Verify that the email is valid
			if (filter_var(Cii::get($_POST, 'email'), FILTER_VALIDATE_EMAIL))
			{
				// Create a user object to pass to the sender
				$user = new StdClass();
				$user->displayName = NULL;
				$user->email = Cii::get($_POST, 'email');

				// Send the test email
				$response = $this->sendEmail($user, 'CiiMS Test Email', 'application.modules.dashboard.views.email.test');

				echo $response;
				Yii::app()->end();
			}
		}

		return false;
	}

	/**
	 * Generic handler for sacing $model data since the model is completely generic.
	 * @param  CiiSettingsModel $model The model we are working with
	 */
	private function submitPost(&$model)
	{
		if (Cii::get($_POST, get_class($model)) !== NULL)
		{
			$model->populate($_POST);

			if ($model->save())
				Yii::app()->user->setFlash('success', 'Your settings have been updated.');
			else
				Yii::app()->user->setFlash('error', 'There was an error saving your settings.');
		}
	}
}