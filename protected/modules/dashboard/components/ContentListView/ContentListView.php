<?php 
Yii::import('zii.widgets.CListView');
class ContentListView extends CListView
{
	public $beforeAjaxUpdate;
	public $afterAjaxUpdate;
	public $ajaxUpdateError;

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();

		if($this->ajaxUpdate===false)
			$ajaxUpdate=array();
		else
			$ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
		$options=array(
			'ajaxUpdate'=>$ajaxUpdate,
			'ajaxVar'=>$this->ajaxVar,
			'pagerClass'=>$this->pagerCssClass,
			'loadingClass'=>$this->loadingCssClass,
			'sorterClass'=>$this->sorterCssClass,
			'enableHistory'=>$this->enableHistory
		);
		if($this->ajaxUrl!==null)
			$options['url']=CHtml::normalizeUrl($this->ajaxUrl);
		if($this->updateSelector!==null)
			$options['updateSelector']=$this->updateSelector;
		foreach(array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError') as $event)
		{
			if($this->$event!==null)
			{
				if($this->$event instanceof CJavaScriptExpression)
					$options[$event]=$this->$event;
				else
					$options[$event]=new CJavaScriptExpression($this->$event);
			}
		}

		$options=CJavaScript::encode($options);
		$cs=Yii::app()->getClientScript();

		$cs->registerCoreScript('bbq', CClientScript::POS_HEAD);

		$cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiilistview.js',CClientScript::POS_END);
		$cs->registerScript(__CLASS__.'#'.$id,"$(document).ready(function() { $('#$id').yiiListView($options); });");
	}

	/**
	 * Renders the data item list.
	 */
	public function renderItems()
	{
		parent::renderItems();
		echo CHtml::tag('div', array('class' => 'preview'));
		echo CHtml::tag('div', array('class' => 'clearfix'));
	}

	/**
	 * Renders the sorter
	 */
	public function renderSorter()
	{
		parent::renderSorter();
		echo CHtml::closeTag('div');
	}
}