<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This controller provides functionality to view and add comment
 *
 * PHP version 5
 *
 * MIT LICENSE Copyright (c) 2012-2013 Charles R. Portwood II
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
 * files (the "Software"), to deal in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom 
 * the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION 
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category   CategoryName
 * @package    CiiMS Content Management System
 * @author     Charles R. Portwood II <charlesportwoodii@ethreal.net>
 * @copyright  Charles R. Portwood II <https://www.erianna.com> 2012-2013
 * @license    http://opensource.org/licenses/MIT  MIT LICENSE
 * @link       https://github.com/charlesportwoodii/CiiMS
 */
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
			throw new CHttpException(400, Yii::t('ciims.controllers.Comments', 'Unable to retrieve comments for the requested post.'));

		$comments = Comments::model()->findAllByAttributes(array('content_id' => $id));
        
		return Comments::model()->thread(array_reverse($comments));
	}

	/**
	 * Provides functionality to make a comment
	 */
	public function actionComment()
	{
		if (Yii::app()->request->isAjaxRequest && Cii::get($_POST, 'Comments'))
		{
			$comment = new Comments();
			$comment->attributes = array(
				'user_id'	=>	Yii::app()->user->id,
				'content_id'=>	$_POST['Comments']['content_id'],
				'comment'	=>	$_POST['Comments']['comment'],
				'parent_id'	=>	Cii::get($_POST['Comments'], 'parent_id', 0),
				'approved'	=>	Cii::getConfig('autoApproveComments', 1) == null ? 1 : Cii::getConfig('autoApproveComments', 1),
			);
			
			if ($comment->save())
			{
				$content = Content::model()->findByPk($comment->content_id);
				// Pass the values as "now" for the comment view"
				$comment->created = $comment->updated = Yii::t('ciims.controllers.Comments', "now");

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
				throw new CHttpException(400, Yii::t('ciims.controllers.Comments', 'There was an error saving your comment.'));
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
				throw new CHttpException(400, Yii::t('ciims.controllers.Comments', 'Invalid request. Please do not repeat this request again.'));
			
			$comment->approved = '-1';
			
			if($comment->save())
				return true;
			else
				throw new CHttpException(400, Yii::t('ciims.controllers.Comments', 'There was an error flagging this comment.'));
		}
		else
			throw new CHttpException(400, Yii::t('ciims.controllers.Comments', 'Invalid request. Please do not repeat this request again.'));
	}
}
