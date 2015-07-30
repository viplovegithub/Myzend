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
/**
 * A   city.
 *
 * @ORM\Entity
 * @ORM\Table(name="location")
 * @property string $locationName;
 * @property int $stateId;
 * @property int $cityId;
 * @property int $locationTypeId;
 * @property string $bestSeasonToVisit;
 * @property int $entry;
 * @property string $timingOpeningNClosing;
 * @property string $description;
 * @property string $specialInstructions;
 * @property string $notAllowed;
 * @property string $locationPhoto;
 * @property string $latitude;
 * @property string $longitude;
 * @property int $airportRailwayStatus;
 * @property int $locationId;
 *  @property int $status;
 */
class Location implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $locationId;
            
    /**
     * @ORM\Column(name="locationName",type="string")
     */
    protected $locname;
      /**
     * @ORM\Column(name="stateId",type="integer")
     */
     protected $stid;
    
      /**
     * @ORM\Column(name="cityId",type="integer")
     */
    protected $ctid;
    
    /**
     * @ORM\Column(name="locationTypeId",type="integer")
     */
    protected $loctypeid;
    
    /**
     * @ORM\Column(name="entry",type="integer")
     */
    protected $entry;
     /**
     * @ORM\Column(name="bestSeasonToVisit",type="string")
     */
    protected $bestSeasonToVisit;
   
   /**
     * @ORM\Column(name="timingOpeningNClosing",type="string");
   */
    protected $timingOpeningNClosing;
     /**
     * @ORM\Column(name="description",type="string")
     */
    protected $description;
     /**
     * @ORM\Column(name="specialInstructions",type="string")
     */
    protected $specialInstructions;
     /**
     * @ORM\Column(name="notAllowed",type="string")
     */
    protected $notAllowed;
     /**
     * @ORM\Column(name="longitude",type="string")
     */
    protected $longitude;
     /**
     * @ORM\Column(name="latitude",type="string")
     */
    protected $latitude;
     /**
     * @ORM\Column(name="locationPhoto",type="string")
     */
    protected $locationPhoto;
      /**
     * @ORM\Column(name="airportRailwayStatus",type="integer")
     */
    protected $airportRailwayStatus;
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
    public function setlocationPhoto($photovar){
        $this->locationPhoto=$photovar;
        
    }
    public function exchangeArray($data) 
    {
        $this->locname = (isset($data['locname'])) ? $data['locname'] : NULL;
    	$this->stid     = (isset($data['stid'])) ? $data['stid'] : NULL;
        $this->ctid     = (isset($data['ctid'])) ? $data['ctid'] : NULL;
        $this->citytemp=$data['citytemp'];
        $this->loctypeid = (isset($data['loctypeid']))  ? $data['loctypeid'] : NULL;
     	$this->bestSeasonToVisit =(isset($data['bestSeasonToVisit'])) ? $data['bestSeasonToVisit'] : NULL;
    	$this->entry = (isset($data['entry']))  ? $data['entry']  : NULL;
    	$this->timingOpeningNClosing = (isset($data['timingOpeningNClosing'])) ? $data['timingOpeningNClosing'] : NULL;
        $this->description = (isset($data['description'])) ? $data['description'] : NULL;
        $this->specialInstructions = (isset($data['specialInstructions'])) ? $data['specialInstructions'] : NULL;
        $this->notAllowed = (isset($data['notAllowed'])) ? $data['notAllowed'] : NULL;
        $this->airportRailwayStatus = (isset($data['airportRailwayStatus'])) ? $data['airportRailwayStatus'] : NULL;
        $this->latitude = (isset($data['latitude'])) ? $data['latitude'] : NULL;
        $this->longitude = (isset($data['longitude'])) ? $data['longitude'] : NULL;
    	$this->locationPhoto =(isset($data['locationPhoto']['name']))? $data['locationPhoto']['name']    : NULL;
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
				'name' => 'locname',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
                        'validators' => array(array('name' => 'alpha', 
                         'options' => array('allowWhiteSpace' => true),),)
				
    		)));

                $inputFilter->add($factory->createInput(array(
				'name' => 'stid',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'ctid',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                  $inputFilter->add($factory->createInput(array(
				'name' => 'loctypeid',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'bestSeasonToVisit',
				'requiered' => TRUE,
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'entry',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'timingOpeningNClosing',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'description',
				'required' => TRUE,
                           
                           
    		)));
                
                
    		$inputFilter->add($factory->createInput(array(
				'name' => 'specialInstructions',
				'required' => TRUE,
				
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'notAllowed',
				'required' => TRUE,
				
				
    		)));
                  $inputFilter->add($factory->createInput(array(
				'name' => 'airportRailwayStatus',
				'required' => TRUE,
				
				
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
				'name' => 'locationPhoto',
				  'type'  => 'Zend\InputFilter\FileInput',
				    'validators' => array(
                                                       array(
        'name' => '\Zend\Validator\File\Extension',
        'options' => array(
            'extension' => array(
                'png', 'jpeg', 'jpg',
            ),
            'break_chain_on_failure' => true,
        ),
    ),
                                                          array( 'name' => 'Zend\Validator\File\Size',
                                                                        'options' => array(
                                                                        'min' => '10kB', 'max' => '4MB',
                                                                        'useByteString' => true, ),), 
                                         )
				
    		)));
              
    		
    		$this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }
}