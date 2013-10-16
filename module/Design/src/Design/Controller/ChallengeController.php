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

use Design\Form\ChallengeForm;
use Design\Form\ChallengeCommentForm;
use Design\Entity\ChallengeComment;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

use Design\Entity\Challenge;

class ChallengeController extends AbstractActionController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
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
                    $data = $form->getData();
                    $data["date_added"] = new \DateTime(date("Y-m-d H:i:s", time()));
                    $data["user"] = $this->getEntityManager()
                        ->getRepository('Application\Entity\User')
                        ->find($this->zfcUserAuthentication()->getIdentity()->getId());
                    $challenge->populate($data);
                    $this->getEntityManager()->persist($challenge);
                    $this->getEntityManager()->flush();

                    // Redirect to list of albums
                    return $this->redirect()->toUrl('/design/challenge/view/' . $challenge->id);
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
                    $data = $form->getData();

                    $data["date_added"] = new \DateTime(date("Y-m-d H:i:s", time()));

                    $data["challenge"] = $this->getEntityManager()
                        ->getRepository('Design\Entity\Challenge')
                        ->find($data["challenge_id"]);

                    $data["user"] = $this->getEntityManager()
                        ->getRepository('Application\Entity\User')
                        ->find($this->zfcUserAuthentication()->getIdentity()->getId());

                    $comment->populate();

                    $this->getEntityManager()->persist($comment);
                    $this->getEntityManager()->flush();

                    $success = TRUE;
                    $messages = array("Comment Added");
                } else {
                    $messages = $form->getMessages();
                    $success = FALSE;
                    $comment = null;
                }

            }
            $result = array(
                'messages' => $messages,
                'success' => $success,
                'comment' => $comment
            );

            $jsonModel = new JsonModel($result);

            echo $jsonModel->serialize();
            exit();
        }
    }

    public function getCommentsAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->getResponse()->setStatusCode(403); //not authorized
            return;
        } else {

            $request = $this->getRequest();

            $challengeId = $this->getRequest()
                ->getPost('challenge_id');

            $success = false;
            $message = "Be the first to leave a comment";

            $challenge = $this->getEntityManager()
                ->getRepository('Design\Entity\Challenge')
                ->find($challengeId);

            if (!is_null($challenge)) {
                $query = $this->getEntityManager()
                    ->createQuery('SELECT c FROM Design\Entity\ChallengeComment c WHERE c.challenge = ?1');
                $query->setParameter(1, $challengeId);
                $comments = $query->getResult();

                $message = "";
                if (count($comments) == 0) {
                    $success = false;
                    $message = "Be the first to leave a comment";
                } else {
                    $success = true;
                }
            }
            $result = array(
                'success' => $success,
                'message' => $message,
                'comments' => $comments
            );

            $jsonModel = new JsonModel($result);

            echo $jsonModel->serialize();
            exit();
        }
    }

    public function viewAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toUrl("/user/login");
        } else {
            $id = (int)$this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toUrl('/design/challenge');
            }
            $request = $this->getRequest();
            $form = new ChallengeCommentForm();
            $data = $request->getPost();
            $data["user_id"] = $this->zfcUserAuthentication()->getIdentity()->getId();
            $data["challenge_id"] = $id;

            $challenge = $this->getEntityManager()
                ->getRepository('Design\Entity\Challenge')
                ->find($data["challenge_id"]);

            $form->setData($data);
            $form->get('submit')->setValue('Add');


            return array(
                'challenge' => $challenge,
                'commentForm' => $form,
            );
        }
    }

    private function getChallengeCommentsJSON($comments)
    {

    }
}
