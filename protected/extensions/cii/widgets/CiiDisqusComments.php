<?php

/**
 * @class CiiDisqusComments
 * Automatically displays and renders Disqus Comments in the view 
 */
class CiiDisqusComments extends CWidget
{
	/**
	 * Content::model attributes passed from the Controller
	 * @var array
	 */
	public $content;

	/**
	 * The Disqus short name
	 * @var string
	 */
	private $_shortname = NULL;

	/**
	 * Init function to start the rendering process
	 */
	public function init()
	{
		$this->_shortname = Cii::getConfig('disqus_shortname');
		$asset = Yii::app()->assetManager->publish(YiiBase::getPathOfAlias('ext.cii.assets.js'), true, -1, YII_DEBUG);
		Yii::app()->clientScript->registerScriptFile($asset. '/disqus.js');

		if ($this->content != false)
			$this->renderCommentBox();
		else
			$this->renderCommentCount();
	}

	/**
	 * Renders the Disqus Comment Box on the page
	 */
	private function renderCommentBox()
	{
		$link = CHtml::link('0', Yii::app()->createAbsoluteUrl($this->content['slug']) . '#disqus_thread', array('data-disqus-identifier' => $this->content['id']));
		Yii::app()->clientScript->registerScript('DisqusComments', "
			// Load the Endpoint
			var endpoint = $('#endpoint').attr('data-attr-endpoint') + '/';

			// Set the Disqus variables
			disqus_shortname = \"{$this->_shortname}\";
            disqus_identifier = \"{$this->content['id']}\";
            disqus_title = \"{$this->content['title']}\";
            disqus_url = endpoint  + \"{$this->content['slug']}\";

            // Update the comment div
            $('#comment').addClass('disqus')');
            $('.comment-count').addClass('registered').append('$link');

            // Load Disqus
            Disqus.load();
            Disqus.commentCount();
		");
	}

	/**
	 * Renders the Diqsus Comment count on the page
	 */
	private function renderCommentCount()
	{
		Yii::app()->clientScript->registerScript('DisqusCommentCount', "
			// Load the Endpoint
			var endpoint = $('#endpoint').attr('data-attr-endpoint') + '/';

			// Set the Disqus variables
			disqus_shortname = \"{$this->_shortname}\";

			Disqus.commentCount();
		");
	}
}