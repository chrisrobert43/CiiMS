<?php

Yii::import('cii.widgets.CiiBaseActiveForm');
class CiiActiveForm extends CiiBaseActiveForm
{
	public $registerPureCss = true;

	public $registerPrism = true;

	/**
	 * Initializes CiiActiveForm
	 *
	 * CiiActiveForm provides a number of enhanced functionalities and tools that wouldn't otherwise be provided, such as HTML5 elements
	 * However it's primary benefit comes from using CiiSettingsModel via the dashboard. While use in the dashboard is recommended, it can
	 * be used outside of that. However for the sake of extensibility it needs to be a part of Cii itself so that it can be used elsewhere
	 * within the application by developers if they so choose to use it.
	 * 
	 * @see CActiveForm::init()
	 */
	public function init()
	{
		$asset = Yii::app()->assetManager->publish(YiiBase::getPathOfAlias('application.extensions.cii.assets'), true, -1, YII_DEBUG);
		$cs = Yii::app()->getClientScript();

		if ($this->registerPureCss)
			$cs->registerCssFile($asset.'/css/pure.css'); 

		if ($this->registerPrism)
		{
			$cs->registerCssFile($asset.'/prism/prism-light.css'); 
			$cs->registerScriptFile($asset.'/prism/prism.js', CClientScript::POS_END); 
		}

		return parent::init();
	}


	/**
	 * EmailField type
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function emailFieldRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		if ($validators == NULL)
			$validators = $model->getValidators($property);

		if ($validators !== NULL)
		{
			foreach ($validators as $k=>$v)
			{
				if (get_class($v) == "CStringValidator")
				{
					if (isset($v->min))
						$htmlOptions['min']  = $v->min;

					if (isset($v->max))
						$htmlOptions['max']  = $v->max;
				}

				if (get_class($v) == "CRequiredValidator")
					$htmlOptions['required'] = true;
			}
		}

		$htmlOptions['value'] = $model->$property;
		$htmlOptions['type'] = 'email';
		$htmlOptions['id'] = get_class($model) . '_' . $property;
		$htmlOptions['name'] = get_class($model) . '[' . $property .']';

		echo CHtml::tag('label', array(), $model->getAttributeLabel($property) . (Cii::get($htmlOptions, 'required', false) ? CHtml::tag('span', array('class' => 'required'), ' *') : NULL));
		echo CHtml::tag('input', $htmlOptions);
	}

	/**
	 * TbActiveForm::textFieldRow() with min/max character length support.
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function textFieldRowLabelFix($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		if ($validators !== NULL)
		{
			foreach ($validators as $k=>$v)
			{
				if (get_class($v) == "CStringValidator")
				{
					if (isset($v->min))
						$htmlOptions['min']  = $v->min;

					if (isset($v->max))
						$htmlOptions['max']  = $v->max;
				}

				if (get_class($v) == "CRequiredValidator")
					$htmlOptions['required'] = true;
			}
		}

		$htmlOptions['value'] = $model->$property;
		$htmlOptions['type'] = 'text';
		$htmlOptions['id'] = get_class($model) . '_' . $property;
		$htmlOptions['name'] = get_class($model) . '[' . $property .']';

		echo CHtml::tag('label', array(), Cii::underscoretowords($model->getAttributeLabel($property)) . (Cii::get($htmlOptions, 'required', false) ? CHtml::tag('span', array('class' => 'required'), ' *') : NULL));
		echo CHtml::tag('input', $htmlOptions);
	}

	/**
	 * TbActiveForm::textFieldRow() with min/max character length support.
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function textFieldRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		if ($validators !== NULL)
		{
			foreach ($validators as $k=>$v)
			{
				if (get_class($v) == "CStringValidator")
				{
					if (isset($v->min))
						$htmlOptions['min']  = $v->min;

					if (isset($v->max))
						$htmlOptions['max']  = $v->max;
				}

				if (get_class($v) == "CRequiredValidator")
					$htmlOptions['required'] = true;
			}
		}

		echo parent::textFieldRow($model, $property, $htmlOptions);
	}

	/**
	 * passwordFieldRow provides a password box that decrypts the database stored value since it will be encrypted in the db
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function passwordFieldRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		$htmlOptions['value'] = Cii::decrypt($model->$property);
		$htmlOptions['type'] = 'password';
		$htmlOptions['id'] = get_class($model) . '_' . $property;
		$htmlOptions['name'] = get_class($model) . '[' . $property .']';
		echo CHtml::tag('label', array(), $model->getAttributeLabel($property) . (Cii::get($htmlOptions, 'required', false) ? CHtml::tag('span', array('class' => 'required'), ' *') : NULL));
		echo CHtml::tag('input', $htmlOptions);
	}

	/**
	 * numberRow HTML5 number elemtn to work with
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function numberFieldRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		if ($validators !== NULL)
		{
			foreach ($validators as $k=>$v)
			{
				if (get_class($v) == "CNumberValidator")
				{
					$htmlOptions['min']  = $v->min;
					$htmlOptions['step'] = 1;
				}

				if (get_class($v) == "CRequiredValidator")
					$htmlOptions['required'] = true;
			}
		}

		$htmlOptions['value'] = $model->$property;
		$htmlOptions['type'] = 'number';
		$htmlOptions['id'] = get_class($model) . '_' . $property;
		$htmlOptions['name'] = get_class($model) . '[' . $property .']';
		echo CHtml::tag('label', array(), $model->getAttributeLabel($property) . (Cii::get($htmlOptions, 'required', false) ? CHtml::tag('span', array('class' => 'required'), ' *') : NULL));
		echo CHtml::tag('input', $htmlOptions);
	}

	/**
	 * rangeRow provides a pretty ish range slider with view controls
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function rangeFieldRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		if ($validators !== NULL)
		{
			foreach ($validators as $k=>$v)
			{
				if (get_class($v) == "CNumberValidator")
				{
					$htmlOptions['min']  = $v->min;
					$htmlOptions['max']  = $v->max;
					$htmlOptions['step'] = 1;
				}
				
				if (get_class($v) == "CRequiredValidator")
					$htmlOptions['required'] = true;
			}
		}

		echo CHtml::tag('label', array(), $model->getAttributeLabel($property) . (Cii::get($htmlOptions, 'required', false) ? CHtml::tag('span', array('class' => 'required'), ' *') : NULL));
		echo $this->rangeField($model, $property, $htmlOptions);
		echo CHtml::tag('div', array('class' => 'output'), NULL);

		// Register a script. Allow it to be overriden since it is global
		Yii::app()->getClientScript()->registerScript('slider', '
			$("input[type=\"range\"]").each(function() {
				$(this).parent().find(".output").html($(this).val());
			})

			$("input[type=\"range\"]").change(function() { 
				$(this).parent().find(".output").html($(this).val()); 
			});
		');
	}

	/**
	 * toggleButtonRow provides a checkbox with toggle support via purecss.io and prism.js
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function toggleButtonRow($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		echo CHtml::tag('label', array(), $model->getAttributeLabel($property));
		echo CHtml::openTag('div', array('class' => Cii::get($htmlOptions, 'class', 'pure-input-2-3'), 'style' => 'display: inline-block'));
			echo CHtml::openTag('label', array('class' => 'switch-light toggle candy blue'));
				$checked = array();
				if($model->$property == 1)
					$checked = array('checked' => 'checked');

				echo CHtml::openTag('input', CMap::mergeArray(array(
					'type' => 'checkbox',
					'id' => get_class($model) . '_' . $property,
					'name' => get_class($model) . '[' . $property . ']',
					'class' => Cii::get($htmlOptions, 'class', NULL),
					'value' => '1'
				), $checked));

				echo CHtml::openTag('span');
					echo CHtml::tag('span', array(), Yii::t('Dashboard.widgets', 'Off'));
					echo CHtml::tag('span', array(), Yii::t('Dashboard.widgets', 'On'));
				echo CHtml::closeTag('span');

				echo CHtml::tag('a', array('class' => 'slide-button'), NULL);
			echo CHtml::closeTag('label');
		echo CHtml::closeTag('div');
	}

	/**
	 * toggleButtonRow provides a checkbox with toggle support via purecss.io and prism.js
	 * @param  CActiveForm      $form        The CActiveForm element
	 * @param  CiiSettingsModel $model       The model that we are operating on
	 * @param  string           $property    The name of the property we are working with
	 * @param  array            $htmlOptions An array of HTML Options
	 * @param  CValidator       $validators  The Validator(s) for this property
	 *                                       Since we already have it, it's worth passing through
	 */
	public function toggleButtonRowFix($model, $property, $htmlOptions=array(), $validators=NULL)
	{
		return $this->toggleButtonRow($model, $property, $htmlOptions, $validators);
	}
}