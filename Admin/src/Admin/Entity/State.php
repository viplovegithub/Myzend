<?php
/**
 * This file is part of the TarSignup Module (https://github.com/xFran/TarSignup.git)
 *
 * @link      https://github.com/xFran/TarSignup.git for the canonical source repository
 * @copyright Copyright (c) 2013 Francisc Tar (https://github.com/xFran/TarSignup.git)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 
use Zend\InputFilter\FileInput;

/**
 * A   state.
 *
 * @ORM\Entity
 * @ORM\Table(name="state")
 * @property string $stateName;
 * @property int $country;
 * @property string $description;
 * @property string $specialInstructions;
 * @property string $bestSeasonToVisit;
 * @property string $latitude;
 * @property string $longitude;
 * @property string $statePhoto;
 * @property int $status;
 * @property  $stateId
 */
class State implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $stateId;
  
    /**
     * @ORM\Column(name="stateName",type="string")
     */
    protected $stname;
    /**
     * @ORM\Column(name="country",type="integer")
     */
    protected $cntryId;
   /**
     * @ORM\Column(name="description",type="string");
   */
    protected $statedescription;
     /**
     * @ORM\Column(name="specialInstructions",type="string")
     */
    protected $statespecialInstructions;
     /**
     * @ORM\Column(name="bestSeasonToVisit",type="string")
     */
    protected $statebestSeasonToVisit;
     /**
     * @ORM\Column(name="latitude",type="string")
     */
    protected $latitude;
     /**
     * @ORM\Column(name="longitude",type="string")
     */
    protected $longitude;
     /**
     * @ORM\Column(name="statePhoto",type="string")
     */
    protected $statePhoto;
     /**
     * @ORM\Column(name="status",type="integer")
     */
    protected $status;
   
    protected $inputFilter;
    
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
    public function setstatePhoto($photovar){
        $this->statePhoto=$photovar;
        
    }
    public function exchangeArray($data)
    {
        $this->stname = (isset($data['stname'])) ? $data['stname'] : NULL;
    	$this->cntryId     = (isset($data['cntryId'])) ? ucfirst($data['cntryId']) : NULL;
        $this->statedescription = (isset($data['statedescription']))  ? $data['statedescription'] : NULL;
     	$this->statespecialInstructions = (isset($data['statespecialInstructions'])) ? $data['statespecialInstructions'] : NULL;
    	$this->statebestSeasonToVisit = (isset($data['statebestSeasonToVisit']))  ? $data['statebestSeasonToVisit']  : NULL;
    	$this->latitude = (isset($data['latitude'])) ? $data['latitude'] : NULL;
        $this->longitude = (isset($data['longitude'])) ? $data['longitude'] : NULL;
    	$this->statePhoto =(isset($data['statePhoto']['name']))? $data['statePhoto']['name']    : NULL;
        $this->status =0;
        
    	
    }
   public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
    		$inputFilter->add($factory->createInput(array(
				'name' => 'stname',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
                                'validators' => array(array('name' => 'alpha', 
                                'options' => array('allowWhiteSpace' => true),),)
                        )));
                $inputFilter->add($factory->createInput(array(
				'name' => 'cntryId',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                
                	$inputFilter->add($factory->createInput(array(
				'name' => 'statedescription',
				'requiered' => TRUE,
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'statespecialInstructions',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'statebestSeasonToVisit',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'latitude',
				'required' => TRUE,
                           
                           
    		)));
                
                
    		$inputFilter->add($factory->createInput(array(
				'name' => 'longitude',
				 'required' => TRUE,
				
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'statePhoto',
                                'type'  => 'Zend\InputFilter\FileInput',
                                //'type' =>'file',
                                'validators' => array(
                                                     array(
        'name' => '\Zend\Validator\File\Extension',
        'options' => array(
            'extension' => array(
                'png', 'jpeg', 'jpg','gif',
            ),
            'break_chain_on_failure' => true,
        ),
    ),
                                                   array('name' => 'Zend\Validator\File\Size',
                                                      'options' => array(
                                                    'min' => '10KB', 'max' => '4MB',
                                                     'useByteString' => true,),),    
                                                  )
				
    		)));
    		
    		$this->inputFilter = $inputFilter;
    	}
    
        return $this->inputFilter;
    }
    
}