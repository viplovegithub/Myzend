<?php
namespace Vehicle\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Doctrine\ORM\EntityManager;

class VehicleModelVersionForm extends Form 
{
     protected $em;
    public function __construct(EntityManager $em, $name = null, $options = array())
    {
        $this->em = $em;
    // we want to ignore the name passed
        parent::__construct('vehiclemodelversion');

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'vehicleModelVersionId',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        
        
    /*     
        $this->add(array(
            'name' => 'vehicleModelId',
            
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                     'label' => 'Vehicle Model',
                     'empty_option' => 'Select',
                
               
             ),
            'attributes' => array(
         'id' => 'vehicleModel',
    ),
        )); 
        
        */
        
       $vehicleModel = new Element\Select('vehicleModelId');
       $vehicleModel->setEmptyOption("Models");
       $vehicleModel->setAttributes(array(
        'id'  => 'vehicleModel',
           'class' => 'dropDnInput',
       ));
       $vehicleModel->setDisableInArrayValidator(TRUE);
       $this->add($vehicleModel);
      
       
       $vehicleBrand = new Element\Select('vehicleBrandId');
       $vehicleBrand->setEmptyOption("Select Brand");
       $vehicleBrand->setAttributes(array(
        'id'  => 'vehicleBrand',
           'class' => 'dropDnInput',
        ));
       $vehicleBrand->setValueOptions(
           $this->getOptionsForSelectBrands()
         );
       $vehicleBrand->setDisableInArrayValidator(TRUE);
       $this->add($vehicleBrand);
      
      
       
        $this->add(array(
            'name' => 'vehicleModelVersionName',
            'attributes' => array(
                'type'  => 'text',
                'class'=>'commonDropDnInput',
                'placeholder' => 'Vehicle Model Version Name',
            ),
            
            
        ));

        
        $vehicleModeltemp = new Element\Hidden('vehicleModelIdTemp');
        $vehicleModeltemp->setLabel('Vehicle ModelId');
        $vehicleModeltemp->setAttributes(array(
        'id'  => 'vehicleModelIdTemp',
         ));
        $this->add($vehicleModeltemp);
       
        
        
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
                'value' => 'vehiclemodelversionadd',
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
              ->add('from', '\Vehicle\Entity\VehicleBrand vb') ;
            $result = $queryBuilder->getQuery()->getArrayResult();
             $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['vehicleBrandId']] = $res['vehicleBrandName'];
        }
     //   print_r($selectData);die;
        return $selectData;
    }
 
    
    
    public function getOptionsForSelectModel()
    {
        $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'vm.vehicleModelId, vm.vehicleBrandId , vm.vehicleModelName')
              ->add('from', '\Vehicle\Entity\VehicleBrand vb')
              ->innerJoin('\Vehicle\Entity\VehicleModel', 'vm',\Doctrine\ORM\Query\Expr\Join::WITH,
                'vb.vehicleBrandId=vm.vehicleBrandId');
            $result = $queryBuilder->getQuery()->getArrayResult();
        // print_r($result);die;
        foreach ($result as $res) {
           $selectData[$res['vehicleModelId']] = $res['vehicleModelName'];
            
        }
     //   print_r($selectData);
    // die;
        return $selectData;
        
        
    }
     
}


?>
