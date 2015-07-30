<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 
use Zend\Validator\Regex as RegexValidator;
 use Zend\Form\Element;
 
/**
 *
 * @ORM\Entity
 * @ORM\Table(name="vehiclecolor")
 * @property int $colorId;
 * @property string $colorName ;
 
 * 
 *  
 *
 * 
 */
class VehicleColor implements InputFilterAwareInterface 
{
    
    protected $inputFilter;
    protected $validator;  // VALIDATION
    protected $validatorStr; // VALIDATION
    
     /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $colorId;
 
    
    /**
     * @ORM\Column(type="string")
     */
    protected $colorName;
 
    
  
  
    
    
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
       // $this->colorId = $data['colorId'];
        $this->colorName= $data['vehicleColor'];
     //   $this->status= $data['status'];
        
    }
  
    
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        
      if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
                
    		$inputFilter->add($factory->createInput(array(
				'name' => 'colorName',
				'requiered' => TRUE,
                    )));
             
    		$inputFilter->add($factory->createInput(array(
				'name' => 'status',
				'requiered' => TRUE,
                    )));
             
              
             
    		$this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }

}