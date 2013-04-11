<?php
class CommentController extends CiiController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl'
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow authenticated users to perform any action
				'users'=>array('@'),
			),
			array('allow',
				'actions' => array('getComments'),
				'users'=>array('*')
			),
			array('allow',
				'users'=>array('@'),
				'expression'=>'!Yii::app()->user->isGuest'
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Retrieves a renderPartial view of all comments for a particular post
	 * @param  int $id 		The id of the content
	 * @return viewfile 	Returns a renderPartial view for ThreadedComments
	 */
	public function actionGetComments($id = NULL)
	{
		$this->layout = false;

		if ($id == NULL)
			throw new CHttpException(400, 'Unable to retrieve comments for that post');

		$comments = Comments::model()->findAllByAttributes(array('content_id' => $id));
        
		return Comments::model()->thread(array_reverse($comments));
	}

	/**
	 * Provides functionality to make a comment
	 */
	public function actionComment()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST))
		{
			$comment = new Comments();
			$comment->attributes = array(
				'user_id'	=>	Yii::app()->user->id,
				'content_id'=>	$_POST['Comments']['content_id'],
				'comment'	=>	$_POST['Comments']['comment'],
				'parent_id'	=>	Cii::get($_POST['Comments'], 'parent_id', 0),
				'approved'	=>	1,
			);
			
			if ($comment->save())
			{
				$content = Content::model()->findByPk($_POST['Comments']['content_id']);
				$content->comment_count++;
				$content->save();
				
				// Send an email to the author if someone makes a comment on their blog
				if ($content->author->id != Yii::app()->user->id && Configuration::model()->findByAttributes(array('key'=>'notifyAuthorOnComment'))->value == 1) 
				{
					Yii::import('application.extensions.phpmailer.JPhpMailer');
					$mail = new JPhpMailer;
					$mail->IsSMTP();
					$mail->SetFrom('noreply@'. Cii::get($_SERVER, 'HTTP_HOST', Cii::get($_SERVER, 'SERVER_NAME', 'localhost')), 'No Reply');
					$mail->Subject = 'New Comment Notification From CiiMS Blog';
					$mail->AltBody = 'New Comment on: ' . $content->title;
					$mail->MsgHTML($this->renderPartial('/email/comments', array('content'=>$content, 'comment'=>$comment), true, true));
					$mail->AddAddress($content->author->email, $content->author->displayName);
					$mail->Send();
				}

				// Pass the values as "now" for the comment view"
				$comment->created = $comment->updated = "now";

				// Set the attributed id to make life easier...
				header("X-Attribute-Id: {$comment->id}");
				$this->renderPartial('comment', array(
					'count'=>$content->comment_count, 
					'comment'=>$comment,
					'depth' => Cii::get($_POST['Comments'], 'parent_id', 0) == 0 ? 0 : 1,
					'md' => new CMarkdownParser
				));
			}
			else
			{
				throw new CHttpException(400, 'Missing or malformed request');
			}
		}
	}
	
	/**
	 * Provides functionality for authenticated users to flag a comment
	 * @param  int $id    The id of a user
	 * @return true       on success, CHttpException 400 on error
	 */
	public function actionFlag($id=NULL)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$comment = Comments::model()->findByPk($id);
			if ($comment == NULL)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			
			$comment->approved = '-1';
			if($comment->save())
				return true;
			else
				throw new CHttpException(400, 'Something went wrong');
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}