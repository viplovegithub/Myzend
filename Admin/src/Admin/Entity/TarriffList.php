<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 

/**
 * Toll
 *
 * @ORM\Table(name="tarifflist")
 * @ORM\Entity
 * @property int tariffListId
 * @property string tariffName
 * @property int status
 */

class TarriffList
{
    protected $inputFilter;
    /**
     * @var integer
     *
     * @ORM\Column(name="tariffListId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tariffListId;

    /**
     * @var string
     *
     * @ORM\Column(name="tariffName", type="string", length=200, nullable=false)
     */
    private $tariffName;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    

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

                  
    public function exchangeArray  ($data = array()) 
    {
     //   $this->tollId = $data['tollId'];                  
        $this->tariffName = $data['tariffName'];                  
        $this->status = 1;  
        
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
                'name'     => 'tariffName',
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
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
                ),
            ));
     $inputFilter->add(array(
                'name'     => 'tariffSubName',
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
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
                ),
            ));
 
          
 
            $this->inputFilter = $inputFilter;
 
        }
    
        return $this->inputFilter;
    }    

}

