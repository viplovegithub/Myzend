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
 * @ORM\Table(name="toll")
 * @ORM\Entity
 * @property int tollId
 * @property string tollName
 * @property int cityId
 * @property int locationId
 */

class Toll
{
    protected $inputFilter;
    /**
     * @var integer
     *
     * @ORM\Column(name="tollId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tollId;

    /**
     * @var string
     *
     * @ORM\Column(name="tollName", type="string", length=200, nullable=false)
     */
    private $tollName;

    /**
     * @var integer
     *
     * @ORM\Column(name="cityId", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="locationId", type="integer", nullable=false)
     */
    private $locationId;
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
        $this->tollName = $data['tollName'];                  
        $this->cityId = $data['cityId'];                  
        $this->locationId = $data['locationId'];  
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
            
//            $inputFilter->add(array(
//                'name'     => 'tollId',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'Digits'),
//                ),
//            ));
 
            $inputFilter->add(array(
                'name'     => 'tollName',
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
                'name'     => 'cityId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Digits'),
                ),
                'validators' => array(
                  array(
                      'name' => 'GreaterThan',
                      'options' => array(
                          'min' => 0
                      ),
                  ),
                ),
            ));
 
            $inputFilter->add(array(
                'name'     => 'locationId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Digits'),
                ),
                'validators' => array(
                  array(
                      'name' => 'GreaterThan',
                      'options' => array(
                          'min' => 0
                      ),
                  ),
                ),
            ));
 
            $this->inputFilter = $inputFilter;
 
        }
    
        return $this->inputFilter;
    }    

}

