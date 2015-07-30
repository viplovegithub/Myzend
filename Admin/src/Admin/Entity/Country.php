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
 * A   country.
 *
 * @ORM\Entity
 * @ORM\Table(name="country")
 * @property string $countryName;
 * @property string $description;
 * @property string $specialInstructions;
 * @property string $bestSeasonToVisit;
 * @property string $latitude;
 * @property string $longitude;
 * @property string $countryPhoto;
 * @property int $status;
 * @property  $countryId
 */
class Country implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $countryId;
        
    /**
     * @ORM\Column(name="countryName",type="string")
     */
    protected $cntryName;
    /**
     * @ORM\Column(name="description",type="string")
     */
    protected $cntrydescription;
   /**
     * @ORM\Column(name="specialInstructions",type="string");
   */
    protected $cntryspecialInstructions;
     /**
     * @ORM\Column(name="bestSeasonToVisit",type="string")
     */
    protected $cntrybestSeasonToVisit;
     /**
     * @ORM\Column(name="latitude",type="string")
     */
    protected $latitude;
     /**
     * @ORM\Column(name="longitude",type="string")
     */
    protected $longitude;
     /**
     * @ORM\Column(name="countryPhoto",type="string")
     */
    protected $contryphoto;
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
    public function setcountryPhoto($photovar){
        $this->contryphoto=$photovar;
        
    }
    public function exchangeArray($data)
    {
        $this->cntryName = (isset($data['cntryName'])) ? $data['cntryName'] : NULL;
    	$this->cntrydescription     = (isset($data['cntrydescription'])) ? ucfirst($data['cntrydescription']) : NULL;
        $this->cntryspecialInstructions = (isset($data['cntryspecialInstructions']))  ? $data['cntryspecialInstructions'] : NULL;
     	$this->cntrybestSeasonToVisit = (isset($data['cntrybestSeasonToVisit'])) ? $data['cntrybestSeasonToVisit'] : NULL;
    	$this->latitude = (isset($data['latitude']))  ? $data['latitude']  : NULL;
    	$this->longitude = (isset($data['longitude'])) ? $data['longitude'] : NULL;
    	$this->contryphoto =(isset($data['contryphoto']['name']))? $data['contryphoto']['name']    : NULL;
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
				'name' => 'cntryName',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
                      'validators' => array(array('name' => 'alpha', 
                      'options' => array('allowWhiteSpace' => true),),)
				
    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'cntrydescription',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				
    		)));
                
                	$inputFilter->add($factory->createInput(array(
				'name' => 'cntryspecialInstructions',
				'requiered' => TRUE,
				
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'cntrybestSeasonToVisit',
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
				'name' => 'contryphoto',
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