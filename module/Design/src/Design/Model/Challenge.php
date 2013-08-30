<?php
namespace Design\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Challenge implements InputFilterAwareInterface
{
    public $id;
    public $challenge;
    public $motivation;
    public $userId;
    public $dateAdded;
    public $dateEdited;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['challenge_id'])) ? $data['challenge_id'] : null;
        $this->challenge = (isset($data['challenge'])) ? $data['challenge'] : "";
        $this->userId = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->motivation  = (isset($data['motivation'])) ? $data['motivation'] : "";
        $this->dateAdded  = (isset($data['date_added'])) ? $data['date_added'] : null;
        $this->dateEdited  = (isset($data['date_edited'])) ? $data['date_edited'] : null;
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
    				'name'     => 'challenge',
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
    										'min'      => 10,
    										'max'      => 100,
    								),
    						),
    				),
    		));
    
    		$inputFilter->add(array(
    				'name'     => 'motivation',
    				'required' => false,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 0,
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