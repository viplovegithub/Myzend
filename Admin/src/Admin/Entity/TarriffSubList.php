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
 * @ORM\Table(name="tariffsublist")
 * @ORM\Entity 
 * @property int tariffSListId
 * @property int tariffListId
 * @property string tariffSubName
 * @property int status
 */

class TarriffSubList
{
    protected $inputFilter;
    /**
     * @var integer
     *
     * @ORM\Column(name="tariffSListId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tariffSListId;

    /**
     * @var string
     *
     * @ORM\Column(name="tariffListId", type="integer",  nullable=false)
     */
    private $tariffListId;

    /**
     * @var integer
     *
     * @ORM\Column(name="tariffSubName", type="string", nullable=false)
     */
    private $tariffSubName;

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
        $this->tariffListId = $data['tariffListId'];                  
        $this->tariffSubName = $data['tariffSubName'];                  
        $this->status = 1;  
        
    }

   
 
  
}

