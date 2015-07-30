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
 * @ORM\Table(name="vehicle")
 * @property int $vehicleId;
 * @property string $vehicleName;

 * @property int $vehicleType;
 * @property int $vehicleCategory; 
 * @property int $vehicleModel; 
 * @property int $vehicleBrand;
 * @property int $vehicleModelVersion;
* @property int $vehicleColor
 * @property int  $status; 

 * 
 */
    class Vehicle implements InputFilterAwareInterface 
{
    
    protected $inputFilter;
    protected $validator;  // VALIDATION
    protected $validatorStr; // VALIDATION
    
     /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $vehicleId;
 
    
    /**
     * @ORM\Column(type="string")
     */
    protected $vehicleName;
    
 
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $vehicleBrand;
    
    
     /**
     * @ORM\Column(type="integer")
     */
    protected $vehicleModel;
    
     /**
     * @ORM\Column(name="vehicleModelVersion",type="integer")
     */
    protected $vehicleModelVersion;
  
   /**
     * @ORM\Column(type="integer")
     */
    protected $vehicleType;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected  $vehicleCategory;
    

    
     /**
     * @ORM\Column(type="integer")
     */
    protected  $vehicleColor;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected  $status;

    

    
    
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
         
     //   $this->vehicleId = $data['vehicleId'];
        $this->vehicleName = $data['vehicleName'];
     
        $this->vehicleType=$data['vehicleType'];
        $this->vehicleCategory=$data['vehicleCategory'];
        $this->vehicleBrand = $data['vehicleBrand'];
        $this->vehicleModel = $data['vehicleModel'];
        $this->vehicleModelVersion=$data['vehicleModelVersion'];
         $this->vehicleColor=$data['vehicleColor'];
       
        $this->status=$data['status'];
     
        
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
				'name' => 'vehicleName',
				'requiered' => TRUE,
				'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
    		)));
                
                
             
                 
                 $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleType',
				'requiered' => TRUE,
                     'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
				
    		)));
        
                 $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleCategory',
				'requiered' => TRUE,
                     'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
                     
				
    		)));
              
                $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleModel',
				'requiered' => TRUE,
                    'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleBrand',
				'requiered' => TRUE,
                    'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleModelVersion',
				'requiered' => TRUE,
                    'validators' => array(array('name' => 'alpha', 
    'options' => array('allowWhiteSpace' => true),),)
				
    		)));
                
                   $inputFilter->add($factory->createInput(array(
				'name' => 'vehicleColor',
				'requiered' => TRUE,
                       'validators' => array(array('name' => 'alpha', 
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