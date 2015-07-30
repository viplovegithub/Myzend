<?php
namespace Vehicle\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Doctrine\ORM\EntityManager;

class VehicleModelForm extends Form 
{ 
    protected $em;
    
    public function __construct(EntityManager $em, $name = null, $options = array())
    {
          $this->em = $em;
          
        // we want to ignore the name passed
        parent::__construct('vehiclemodel');

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'vehicleModelId',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

       
       $vehicleBrand = new Element\Select('vehicleBrandId');
 $vehicleBrand->setAttributes(array(
               'class' => 'dropDnInput',
        ));       $vehicleBrand->setEmptyOption("Select Brand");
       
       $vehicleBrand->setValueOptions(
             $this->getOptionsForSelectBrands()
         );
       $vehicleBrand->setDisableInArrayValidator(TRUE);
       $this->add($vehicleBrand);
       
       
        $this->add(array(
            'name' => 'vehicleModelName',
            'attributes' => array(
                'type'  => 'text',
                 'class'=>'commonDropDnInput',
                'placeholder' => 'Vehicle Model Name',
            ),
            
        ));

        
       
          $this->add(array(
            'name' => 'status',
            'type'  => 'Select',
              'attributes' => array(
                'class' => 'dropDnInput',
            ),
            'options' => array(
                'empty_option' => 'Select Status',
                 'value_options' => array(
                             '0' => 'InActive',
                             '1' => 'Active',
                           ),
            ),
        ));  
 
        
 
             $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'vehiclModeladd',
                'id' => 'submitbutton',
                'class'=>"btn-blue"
            ),
        ));
      

    }
    
    public function getOptionsForSelectBrands()
    {
        $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
      
        $queryBuilder->add('select', 'vb.vehicleBrandId , vb.vehicleBrandName')
              ->add('from', '\Vehicle\Entity\VehicleBrand vb') 
             ;
            $result = $queryBuilder->getQuery()->getArrayResult();
             $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['vehicleBrandId']] = $res['vehicleBrandName'];
        }
     //   print_r($selectData);die;
        return $selectData;
    }
 
}
