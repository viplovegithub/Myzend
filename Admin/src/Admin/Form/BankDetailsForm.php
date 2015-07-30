<?php
/**
 * This file is part of the TarSignup Module (https://github.com/xFran/TarSignup.git)
 *
 * @link      https://github.com/xFran/TarSignup.git for the canonical source repository
 * @copyright Copyright (c) 2013 Francisc Tar (https://github.com/xFran/TarSignup.git)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;   
use Doctrine\ORM\EntityManager;
class BankDetailsForm extends Form
{
    protected $em;
    public function __construct (EntityManager $em, $name = null, $options = array())
    {
         $this->em = $em;
        parent::__construct('BankDetailsForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
          $city = new Element\Select('cityId');
          $city->setLabel('city');
          $city->setValueOptions(
             $this->getOptionCity()
          );
          $city->setAttribute("class", "dropDnInput");
          $city->setAttribute("id", "cityId");
          $city->setDisableInArrayValidator(true);
          $this->add($city);
          
           $bankName = new Element\Select('bankName');
           $bankName->setLabel('Bank Name');
           $bankName->setValueOptions(
               $this->getOptionBankNames()
             );
           $bankName->setAttribute("class", "dropDnInput");
           $this->add($bankName);       
            $this->add(array(
                      'name' => 'branchName',
                      'attributes' => array(
                                      'type' => 'text',
                                      'placeholder' => 'Branch Name',
                                      'class' => 'commonDropDnInput',
                      ),
                    
              ));
                $this->add(array(
                        'name' => 'ifscCode',
                        'attributes' => array(
                                        'type' => 'text',
                                        'placeholder' => 'IFSC code',
                                       'class' => 'commonDropDnInput',
                        ),
                       
                ));
        
         
     
        $this->add(array(
    		'name' => 'save',
    		'attributes' => array(
				'type' => 'submit',
				'value' => 'Submit',
				'class'=>"btn-blue"
    		),
    		
        ));
//        $this->add(array(
//    		'name' => 'cancel',
//    		'attributes' => array(
//				'type' => 'cancel',
//				'value' => 'Cancel',
//				'class' => 'btn btn-primary',
//    		),
//    		'options' => array(
//				'label' => 'Cancel'
//    		),
//        ));
    }
    public function getOptionBankNames(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'b.bankId , b.bankName')
              ->add('from', '\Admin\Entity\Bank b') ;
            $result = $queryBuilder->getQuery()->getArrayResult();
             $selectData = array(''=>'select Bank Name');
           foreach ($result as $res) {
            $selectData[$res['bankId']] = $res['bankName'];
        }
        return $selectData;
    }
     public function getOptionCity(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'c.cityId , c.ctname')
              ->add('from', '\Admin\Entity\City c') ;
            $result = $queryBuilder->getQuery()->getArrayResult();
             $selectData = array(''=>'Select City');
           foreach ($result as $res) {
            $selectData[$res['cityId']] = $res['ctname'];
        }
        return $selectData;
    }
   
}
