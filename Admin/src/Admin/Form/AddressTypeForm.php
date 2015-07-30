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

class AddressTypeForm extends Form
{
    public function __construct ( $name = null)
    {

        parent::__construct('AddressTypeForm');

     $this->setAttribute('method', 'post');
     $this->setAttribute('class', 'form-horizontal');
     
       $this->add(array(
                        'name' => 'addrTypeName',
                        'attributes' => array(
                                        'type' => 'text',
                                        'placeholder' => 'Address Type',
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
    		'options' => array(
				'label' => 'Save'
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
}
