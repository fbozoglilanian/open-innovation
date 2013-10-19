<?php
namespace Design\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="challenge_comment")
 * @property datetime $dateAdded
 * @property \Application\Entity\User $user
 * @property string $comment
 * @property \Design\Entity\Challenge $challenge
 * @property int $id
 */
class ChallengeComment implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(name="comment_id", type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\ManyToOne(targetEntity="Challenge")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id")
     */
    public $challenge;
    /**
     * @ORM\Column(type="string")
     */
    public $comment;
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    public $user;
    /**
     * @ORM\Column(name="date_added", type="datetime")
     */
    public $dateAdded;


    public function populate($data)
    {
        $this->id = (isset($data['comment_id'])) ? $data['comment_id'] : null;
        $this->challenge = (isset($data['challenge'])) ? $data['challenge'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : "";
        $this->user = (isset($data['user'])) ? $data['user'] : null;
        $this->dateAdded = (isset($data['date_added'])) ? $data['date_added'] : null;
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
                'name' => 'comment_id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'challenge_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'user_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'comment',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 1000,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}