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


class ChallengeController extends AbstractActionController
{
	/**
	 *
	 * @var ChallengeTable
	 */
	private $_challengeTable = null;

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

	public function viewAction()
	{
		if (!$this->zfcUserAuthentication()->hasIdentity()) {
			return $this->redirect()->toUrl("/user/login");
		}else {
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id) {
				return $this->redirect()->toUrl('/design/challenge');
			}
			$challenge = $this->getChallengeTable()->getChallenge($id);
			return array('challenge' => $challenge);
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
}
