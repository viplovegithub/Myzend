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
use Zend\Validator\Regex as RegexValidator;
use Zend\Http\PhpEnvironment\RemoteAddress;

/**
 * A   user.
 *
 * @ORM\Entity
 * @ORM\Table(name="bankdetails")
 * @property integer $bankName;
 * @property string $branchName;
 * @property string $ifscCode;
 * @property int $cityId;
 * @property int $bankDetailsId
 * @property int $status
 */
class BankDetails implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $bankDetailsId;
   
    /**
     * @ORM\Column(name="bankName",type="integer")
     */
    protected $bankName;
    /**
     * @ORM\Column(name="branchName",type="string")
     */
    protected $branchName;
 
    /**
     * @ORM\Column(name="ifscCode",type="string")
     */
    protected $ifscCode;
      /**
     * @ORM\Column(name="cityId",type="integer");
   */ 
    protected $cityId;
       /**
     * @ORM\Column(name="status",type="integer");
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
        $this->bankName = (isset($data['bankName']))  ? $data['bankName'] : NULL;
     	$this->branchName = (isset($data['branchName'])) ? $data['branchName'] : NULL;
    	$this->ifscCode = (isset($data['ifscCode']))  ? $data['ifscCode']  : NULL;
    	$this->cityId = (isset($data['cityId'])) ? $data['cityId'] : NULL;
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
    		$factory     = new InputFactory();
                $inputFilter->add($factory->createInput(array(
				'name' => 'cityId',
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
    		$inputFilter->add($factory->createInput(array(
				'name' => 'bankName',
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
                $inputFilter->add($factory->createInput(array(
				'name' => 'branchName',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
                        'validators' => array(array('name' => 'alpha', 
                         'options' => array('allowWhiteSpace' => true),),)
    		)));
                
                	$inputFilter->add($factory->createInput(array(
				'name' => 'cityId',
				'requiered' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
                            'validators' => array(array( 'name' => 'digits'),)
	)));
    		
        	
    		$this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }
}