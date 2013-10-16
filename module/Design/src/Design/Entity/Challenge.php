<?php

namespace Design\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="challenge")
 * @property datetime $dateEdited
 * @property datetime $dateAdded
 * @property Application\Entity\User $user
 * @property Design\Entity\ChallengeComment $comments
 * @property string $motivation
 * @property string $challenge
 * @property int $id
 */
class Challenge implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(name="challenge_id", type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(type="string")
     */
    public $challenge;
    /**
     * @ORM\Column(type="string")
     */
    public $motivation;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    public $user;

    /**
     * @ORM\Column(name="date_added", type="datetime")
     */
    public $dateAdded;
    /**
     * @ORM\Column(name="date_edited", type="datetime")
     */
    public $dateEdited;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id = (isset($data['challenge_id'])) ? $data['challenge_id'] : null;
        $this->challenge = (isset($data['challenge'])) ? $data['challenge'] : "";
        $this->user = (isset($data['user'])) ? $data['user'] : null;
        $this->motivation = (isset($data['motivation'])) ? $data['motivation'] : "";
        $this->dateAdded = (isset($data['date_added'])) ? $data['date_added'] : null;
        $this->dateEdited = (isset($data['date_edited'])) ? $data['date_edited'] : null;
//         $this->comments  = (isset($data['comments'])) ? $data['comments'] : array();
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

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
                'name' => 'challenge',
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
                            'min' => 10,
                            'max' => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'motivation',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
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