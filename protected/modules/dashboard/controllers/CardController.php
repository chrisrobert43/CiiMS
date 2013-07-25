<?php

class CardController extends CiiDashboardController
{
	public function actionDelete($id)
	{

	}

	public function actionAdd($name=NULL)
	{
		Yii::import("application.modules.dashboard.cards.{$name}.*");
		$card = new $name;
		$card->create();

		$data = json_decode($card->getJSON(), true);
		$data['id'] = $card->id;

		// Update the user's card information
		$meta = UserMetadata::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'key' => 'dashboard'));
		if ($meta == NULL)
		{
			$meta = new UserMetadata();
			$meta->key = 'dashboard';
			$meta->user_id = Yii::app()->user->id;
			$meta->value = array();
		}

		if (!is_array($meta->value))
			$order = json_decode($meta->value);
		else
			$order = $meta->value;

		$order[] = $card->id;
		$meta->value = json_encode($order);
		$meta->save();

		return $data;
	}

	/**
	 * Provides functionality for resizing any widget based upon it's ID
	 * NOTE, that this widget DOES NOT perform any validation. Validation is done client side.
	 * @param  string $id  The card ID
	 * @return [type]     [description]
	 */
	public function actionResize($id)
	{
		if (Cii::get($_POST, 'activeSize'))
		{
			if ($id == NULL)
				throw new CHttpException(400, 'An ID must be specified');

			$name = Yii::app()->db->createCommand("SELECT name FROM `cards` WHERE uid = :uid")->bindParam(':uid', $id)->queryScalar();

			if ($name == NULL)
				throw new CHttpException(400, 'No card with that ID exists');

			Yii::import("application.modules.dashboard.cards.{$name}.*");

			$card = new $name($id);

			$data = json_decode($card->getJSON(), true);
			$data['activeSize'] = $_POST['activeSize'];

			if ($card->updateData($data))
				return;
		}

		throw new CHttpException(400, 'Missing POST data');
	}

	public function getCards()
	{

	}
}