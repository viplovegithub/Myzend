<?php
namespace Vehicle\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class VehicleCategoryForm extends Form 
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('vehiclecategory');

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'vehicleCategoryId',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'vehicleCategoryName',
            'attributes' => array(
                'type'  => 'text',
                'class'=>'commonDropDnInput',
                'placeholder' => 'Vehicle Category Name',
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
                'value' => 'vehiclecategoryadd',
                'id' => 'submitbutton',
                 'class'=>"btn-blue"
            ),
        ));
      

    }
}
