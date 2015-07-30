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
 * A   user.
 *
 * @ORM\Entity
 * @ORM\Table(name="subusertype")
 
 * @property string $type;
 * @property int $subUTypeId;
 * @property int $status
 * 
 */
class SubUser 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $subUTypeId;
    
    /**
     * @ORM\Column(name="type",type="string")
     */
    protected $subUsertype;
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
    public function exchangeArray($data)
    {
      // $this->subUserCategory = (isset($data['subUserCategory'])) ? $data['subUserCategory'] : NULL;
       $this->subUsertype = (isset($data['subUsertype'])) ? $data['subUsertype'] : NULL;
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
//    		$inputFilter->add($factory->createInput(array(
//				'name' => 'subUserCategory',
//				'requiered' => TRUE,
//				'filters' => array(
//					array('name' => 'StripTags'),
//					array('name' => 'StringTrim'),
//				),
//				'validators' => array(
//					array(
//						'name'    => 'StringLength',
//						'options' => array(
//							'encoding' => 'UTF-8',
//							
//						),
//					),
//				    
//				),
//    		)));
                $inputFilter->add($factory->createInput(array(
				'name' => 'subUsertype',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							
						),
					),
				    
				),
    		)));
                
   		$this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }
}