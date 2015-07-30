<?php
namespace Vehicle\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class VehicleTypeForm extends Form 
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('vehicletype');

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'vehicleTypeId',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'vehicleTypeName',
            'attributes' => array(
                'type'  => 'text',
                 'class'=>'commonDropDnInput',
                'placeholder' => 'Vehicle Type Name',
                
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
                'value' => 'vehiclebrandadd',
                'id' => 'submitbutton',
                'class'=>"btn-blue"
            ),
        ));
      

    }
}
