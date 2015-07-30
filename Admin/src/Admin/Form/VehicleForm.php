<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Doctrine\ORM\EntityManager;

class VehicleForm extends Form 
{
     /**
     * @var EntityManager
     */
    protected $em;
    
    public function __construct(EntityManager $em, $name = null)
    {
       $this->em = $em;
        // we want to ignore the name passed
        parent::__construct('vehicle');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'vehicleId',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
         $this->add(array(
            'name' => 'vehicleCategory',
            'attributes' => array(
                'type'  => 'text',
                'id'=>'vehicleCategorys',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Category',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));
         
          $this->add(array(
            'name' => 'vehicleType',
            'attributes' => array(
                'type'  => 'text',
                 'id'=>'vehicleTypes',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Type',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));

          $this->add(array(
            'name' => 'vehicleBrand',
            'attributes' => array(
                'type'  => 'text',
                 'id'=>'vehicleBrands',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Brand',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));

           $this->add(array(
            'name' => 'vehicleModel',
            'attributes' => array(
                'type'  => 'text',
                    'id'=>'vehicleModels',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Model',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));

            $this->add(array(
            'name' => 'vehicleModelVersion',
            'attributes' => array(
                'type'  => 'text',
                 'id'=>'vehicleModelVersions',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle ModelVersion',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));
$this->add(array(
            'name' => 'vehicleColor',
            'attributes' => array(
                'type'  => 'text',
                'id'=>'vehicleColors',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Color',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));
             $this->add(array(
            'name' => 'vehicleName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Vehicle Name',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));

        
 
         
        
       
      
           $this->add(array(
            'name' => 'status',
            'type'  => 'Select',
               'attributes' => array(
                  'class' => 'dropDnInput',
            ),
               
            'options' => array(
               //  'label' => 'Status',
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
                'value' => 'add',
                'id' => 'submitbutton',
                'class'=>"btn-blue"
            ),
        ));
      

    }
    
    
     
    
}
