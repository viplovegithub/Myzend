<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Doctrine\ORM\EntityManager;

class TerifflistForm extends Form 
{
     /**
     * @var EntityManager
     */
   
    public function __construct( $name = null)
    {
      
         // we want to ignore the name passed
        parent::__construct('vehicle');

        $this->setAttribute('method', 'post');
       $this->add(array(
            'name' => 'tariffName',
            'attributes' => array(
                'type'  => 'text',
                'id'=>'tariffName',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Tarriff Name',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
        ));
         
          $this->add(array(
            'name' => 'tariffSubName',
            'attributes' => array(
                'type'  => 'text',
                 'id'=>'tariffSubName',
                'class' => 'commonDropDnInput',
                'placeholder' => 'Tarriff Sub Name',
            ),
//            'options' => array(
//                'label' => 'vehicleName',
//            ),
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
