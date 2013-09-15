<?php
namespace Design\Form;

use Zend\Form\Form;

class ChallengeCommentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('comment');
        
        $this->setAttribute('action', '/design/challenge/addComment');
        $this->setAttribute('id', 'challengeComment');

        $this->add(array(
                'name' => 'challenge_id',
                'type' => 'Hidden',
                'attributes' => array(
                        'id' => 'challenge_id',
                )
        ));
        $this->add(array(
        		'name' => 'user_id',
        		'type' => 'Hidden',
                'attributes' => array(
                        'id' => 'user_id',
                )
        ));
        $this->add(array(
                'name' => 'comment',
                'type' => 'Textarea',
                'options' => array(
                        'label' => 'Comment',
                ),
                'attributes' => array(
                        'id' => 'comment',
                )
        ));
        $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                        'value' => 'Send',
                        'id' => 'submitbutton',
                ),
        ));
    }
}