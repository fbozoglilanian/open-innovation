<?php
namespace Design\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use ZfcUser\Entity\User;
use ZfcUser\Controller\UserController;
class ChallengeComment implements InputFilterAwareInterface
{
    public $id;
    public $challengeId;
    public $comment;
    public $userId;
    public $dateAdded;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['comment_id'])) ? $data['comment_id'] : null;
        $this->challengeId = (isset($data['challenge_id'])) ? $data['challenge_id'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : "";
        $this->userId = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->dateAdded  = (isset($data['date_added'])) ? $data['date_added'] : null;
    }
        

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                    'name'     => 'comment_id',
                    'required' => true,
                    'filters'  => array(
                            array('name' => 'Int'),
                    ),
            ));

            $inputFilter->add(array(
                    'name'     => 'challenge_id',
                    'required' => true,
                    'filters'  => array(
                            array('name' => 'Int'),
                    ),
            ));

            $inputFilter->add(array(
                    'name'     => 'user_id',
                    'required' => true,
                    'filters'  => array(
                            array('name' => 'Int'),
                    ),
            ));

            $inputFilter->add(array(
                    'name'     => 'comment',
                    'required' => true,
                    'filters'  => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                            array(
                                    'name'    => 'StringLength',
                                    'options' => array(
                                            'encoding' => 'UTF-8',
                                            'min'      => 2,
                                            'max'      => 1000,
                                    ),
                            ),
                    ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}