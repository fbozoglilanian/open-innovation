<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Design\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Design\Model\ChallengeTable;
use Design\Form\ChallengeForm;
use Design\Model\Challenge;
use Design\Model\ChallengeCommentTable;
use Design\Form\ChallengeCommentForm;
use Design\Model\ChallengeComment;
use Zend\View\Model\JsonModel;

class ChallengeController extends AbstractActionController
{
	/**
	 *
	 * @var ChallengeTable
	 */
	private $_challengeTable = null;
	
	/**
	 * 
	 * @var ChallengeCommentTable
	 */
	private $_challengeCommentTable = null;

	public function indexAction()
	{
		return new ViewModel(array(
				'lastChallenges' => array() //$this->getChallengeTable()->getLastChallenges(5)
		));
	}
	/**
	 * Add a challenge. Requires login
	 * @author fbozoglilanian
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Design\Form\ChallengeForm
	 */
	public function addAction()
	{
		if (!$this->zfcUserAuthentication()->hasIdentity()) {
			return $this->redirect()->toUrl("/user/login");
		} else {
			$form = new ChallengeForm();
			$form->get('submit')->setValue('Add');

			$request = $this->getRequest();
			if ($request->isPost()) {
				$challenge = new Challenge();
				$form->setInputFilter($challenge->getInputFilter());
				
				$data = $request->getPost();
				$data["user_id"] = $this->zfcUserAuthentication()->getIdentity()->getId();
				
				$form->setData($data);
				
				if ($form->isValid()) {
					$challenge->exchangeArray($form->getData());
					$id = $this->getChallengeTable()->saveChallenge($challenge);

					// Redirect to list of albums
					return $this->redirect()->toUrl('/design/challenge/view/' . $id);
				}
			}
			return array('form' => $form);
		}
	}
	
	
	public function addCommentAction()
	{
	    if (!$this->zfcUserAuthentication()->hasIdentity()) {
	        $this->getResponse()->setStatusCode(403); //not authorized
	        return;
	    } else {
	        
	        $request = $this->getRequest();
	        $success = FALSE;
	        $messages = array("Unknwon error");
	        
	        if ($request->isPost()) {
	            $form = new ChallengeCommentForm();
	            $form->get('submit')->setValue('Add');
	            
	            $comment = new ChallengeComment();
	            $form->setInputFilter($comment->getInputFilter());
	
	            $data = $request->getPost();
	            $data["user_id"] = $this->zfcUserAuthentication()->getIdentity()->getId();
	
	            $form->setData($data);
	
	            if ($form->isValid()) {
	                $comment->exchangeArray($form->getData());
	                $this->getChallengeCommentTable()->addComment($comment);
	                $success = TRUE;
	                $messages = array("Comment Added");
	            } else {
	                $messages = $form->getMessages();
	                $success = FALSE;
	                $comment = null;
	            }
	            
	        }
	        $result = array(
	                'messages'   => $messages,
	                'success'    => $success,
	                'comment'    => $comment
	        );
	         
	        $jsonModel = new JsonModel($result);
	        
	        echo $jsonModel->serialize(); exit();
	    }
	}
	
	public function getCommentsAction()
	{
	    if (!$this->zfcUserAuthentication()->hasIdentity()) {
	        $this->getResponse()->setStatusCode(403); //not authorized
	        return;
	    } else {
	         
	        $request = $this->getRequest();
	        $challengeId = $this->getRequest()->getPost('challenge_id');
	        $comments = array();
	        $commentsRS = $this->getChallengeCommentTable()->getComments($challengeId);
	        
	        $message = "";
	        if ($commentsRS->count() == 0) {
	            $success = false;
	            $message = "Be the first to leave a comment";
	        } else {
	            $success = true;
	            foreach ($commentsRS as $comment) {
	                $comments[] = $comment;
	            }
	        }
	        
	        $result = array(
	                'success' => $success,
	                'message' => $message,
	                'comments' => $comments
	        );
	
	        $jsonModel = new JsonModel($result);
	         
	        echo $jsonModel->serialize(); exit();
	    }
	}

	public function viewAction()
	{
		if (!$this->zfcUserAuthentication()->hasIdentity()) {
			return $this->redirect()->toUrl("/user/login");
		}else {
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id) {
				return $this->redirect()->toUrl('/design/challenge');
			}
			$request = $this->getRequest();
			$form = new ChallengeCommentForm();
			$data = $request->getPost();
			$data["user_id"] = $this->zfcUserAuthentication()->getIdentity()->getId();
			$data["challenge_id"] = $id;
			
			$form->setData($data);
			$form->get('submit')->setValue('Add');
			
			$challenge = $this->getChallengeTable()->getChallenge($id);
			return array('challenge' => $challenge, 'commentForm' => $form);
		}
		 
	}
	/**
	 *
	 * @return ChallengeTable <object, multitype:>
	 */
	public function getChallengeTable()
	{
		if (is_null($this->_challengeTable)) {
			$sm = $this->getServiceLocator();
			$this->_challengeTable = $sm->get('Design\Model\ChallengeTable');
		}
		return $this->_challengeTable;
	}
	/**
	 *
	 * @return ChallengeCommentTable <object, multitype:>
	 */
	public function getChallengeCommentTable()
	{
	    if (is_null($this->_challengeCommentTable)) {
	        $sm = $this->getServiceLocator();
	        $this->_challengeCommentTable = $sm->get('Design\Model\ChallengeCommentTable');
	    }
	    return $this->_challengeCommentTable;
	}
}
