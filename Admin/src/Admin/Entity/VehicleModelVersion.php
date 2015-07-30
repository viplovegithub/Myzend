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
 * @ORM\Table(name="vehiclemodelversion")
 * 
 * @property int $vehicleModelVersionId;
 
 * @property string $vehicleModelVersionName ;
  
 * 
 * 
 *  
 *
 * 
 */
class VehicleModelVersion implements InputFilterAwareInterface 
{
    
    protected $inputFilter;
    protected $validator;  // VALIDATION
    protected $validatorStr; // VALIDATION
    
    protected $vehicleModelIdTemp;
     

    
     
     /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $vehicleModelVersionId;
 
    
    
   
    
    
    /**
     * @ORM\Column(type="string")
     */
    protected $vehicleModelVersionName;
    
    
    
    
  
  
    
    
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
        // print_r($data);die; 
       //  $this->vehicleModelVersionId = $data['vehicleModelVersionId'];
      //   $this->vehicleBrandId = $data['vehicleBrandId']; 
         //(!empty($data['artist']))? $data['artist']:null;
       //   $this->vehicleModelId = $data['vehicleModelId'];
          //$this->vehicleModelId = (isset($data['vehicleModelId']))? 1:1;
  
        $this->vehicleModelVersionName= $data['vehicleModelVersion'];
      //  $this->status = $data['status'];
        
        
       // $this->vehicleModelIdTemp = $data['vehicleModelIdTemp'];
       
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
				'name' => 'vehicleModelId',
				'requiered' => TRUE,
				
    		)));
                
                 $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleModelIdTemp',
                                'requiered' => TRUE,
                     
                     
    		)));
                
    		$inputFilter->add($factory->createInput(array(
				'name' => 'vehicleBrandId',
				'requiered' => TRUE,
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleModelVersionName',
				'requiered' => TRUE,
				'validators' => array(array('name' => 'alnum',
                                      'options' => array('allowWhiteSpace' => true),),)
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