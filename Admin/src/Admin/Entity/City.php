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
 * @ORM\Table(name="city")
 * @property string $cityName;
 * @property int $stateId;
 * @property int $countryId;
 * @property string $description;
 * @property string $specialInstructions;
 * @property string $bestSeasonToVisit;
 * @property string $latitude;
 * @property string $longitude;
 * @property string $cityPhoto;
 * @property string $categoryId;
 * @property int $cityId;
 * @property int $status
 */
class City implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $cityId;
    
    
     
   // protected $state;
  
    /**
     * @ORM\Column(name="cityName",type="string")
     */
    protected $ctname;
    
    /**
     * @ORM\Column(name="stateId",type="integer")
     */
     protected $stid;
    
//      /**
//     * @ORM\Column(name="countryId",type="integer")
//     */
//    protected $cntryId;
   /**
     * @ORM\Column(name="description",type="string");
   */
    protected $ctdescription;
     /**
     * @ORM\Column(name="specialInstructions",type="string")
     */
    protected $ctspecialInstructions;
     /**
     * @ORM\Column(name="bestSeasonToVisit",type="string")
     */
    protected $ctbestSeasonToVisit;
     /**
     * @ORM\Column(name="latitude",type="string")
     */
    protected $ctlatitude;
     /**
     * @ORM\Column(name="longitude",type="string")
     */
    protected $ctlongitude;
     /**
     * @ORM\Column(name="cityPhoto",type="string")
     */
    protected $cityPhoto;
      /**
     * @ORM\Column(name="categoryId",type="integer")
     */
    protected $ctcatid;
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
    public function setcityPhoto($photovar){
        $this->cityPhoto=$photovar;
        
    }
    public function exchangeArray($data)
    {
  
        $this->ctname = (isset($data['ctname'])) ? $data['ctname'] : NULL;
    	$this->stid     = (isset($data['stid'])) ? $data['stid'] : NULL;
     //   $this->cntryId     = (isset($data['cntryId'])) ? $data['cntryId'] : NULL;
       //  $this->stidTemp     = $data['stidTemp'];
        $this->ctdescription = (isset($data['ctdescription']))  ? $data['ctdescription'] : NULL;
        $this->status = 0;
     	$this->ctspecialInstructions =(isset($data['ctspecialInstructions'])) ? $data['ctspecialInstructions'] : NULL;
    	$this->ctbestSeasonToVisit = (isset($data['ctbestSeasonToVisit']))  ? $data['ctbestSeasonToVisit']  : NULL;
    	$this->ctlatitude = (isset($data['ctlatitude'])) ? $data['ctlatitude'] : NULL;
        $this->ctlongitude = (isset($data['ctlongitude'])) ? $data['ctlongitude'] : NULL;
        $this->cityPhoto =(isset($data['cityPhoto']['name']))? $data['cityPhoto']['name']  : NULL;
        $this->ctcatid = (isset($data['ctcatid'])) ? $data['ctcatid'] : NULL;
    	
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
				'name' => 'ctname',
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
				'name' => 'ctcatid',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'ctdescription',
				'requiered' => TRUE,
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'ctspecialInstructions',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'ctbestSeasonToVisit',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                
                $inputFilter->add($factory->createInput(array(
				'name' => 'ctlatitude',
				'required' => TRUE,
                           
                           
    		)));
                
                
    		$inputFilter->add($factory->createInput(array(
				'name' => 'ctlongitude',
				'required' => TRUE,
				
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'cityPhoto',
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