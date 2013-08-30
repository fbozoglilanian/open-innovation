<?php
namespace Design\Form;

use Zend\Form\Form;

class ChallengeForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('challenge');

        $this->add(array(
                'name' => 'challenge_id',
                'type' => 'Hidden',
        ));
        $this->add(array(
        		'name' => 'user_id',
        		'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'challenge',
                'type' => 'Text',
                'options' => array(
                        'label' => 'Challenge',
                ),
        ));
        $this->add(array(
                'name' => 'motivation',
                'type' => 'Textarea',
                'options' => array(
                        'label' => 'Motivation',
                ),
        ));
        $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                        'value' => 'Save',
                        'id' => 'submitbutton',
                ),
        ));
    }
}