<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Design;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Design\Model\Challenge;
use Design\Model\ChallengeTable;
use Design\Model\ChallengeComment;
use Design\Model\ChallengeCommentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Design\Model\ChallengeTable' =>  function($sm) {
    						$tableGateway = $sm->get('ChallengeTableGateway');
    						$table = new ChallengeTable($tableGateway);
    						return $table;
    					},
    					'ChallengeTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Challenge());
    						return new TableGateway('challenge', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Design\Model\ChallengeCommentTable' =>  function($sm) {
    					    $tableGateway = $sm->get('ChallengeCommentTableGateway');
    					    $table = new ChallengeCommentTable($tableGateway);
    					    return $table;
    					},
    					'ChallengeCommentTableGateway' => function ($sm) {
    					    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					    $resultSetPrototype = new ResultSet();
    					    $resultSetPrototype->setArrayObjectPrototype(new ChallengeComment());
    					    return new TableGateway('challenge_comment', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    	);
    }
}
