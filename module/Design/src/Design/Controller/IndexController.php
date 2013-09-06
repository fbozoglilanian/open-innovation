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

class IndexController extends AbstractActionController
{
	/**
	 * 
	 * @var ChallengeTable
	 */
	private $_challengeTable = null;
	
    public function indexAction()
    {
        return new ViewModel(array(
            'lastChallenges' => $this->getChallengeTable()->getLastFive()
        ));
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
