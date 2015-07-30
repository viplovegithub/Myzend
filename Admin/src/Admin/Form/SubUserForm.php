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
use Doctrine\ORM\EntityManager;
class SubUserForm extends Form
{
    public function __construct (EntityManager $em, $name = null, $options = array())
    {

        parent::__construct('SubUserForm');
        $this->em = $em;   
     $this->setAttribute('method', 'post');
     $this->setAttribute('class', 'form-horizontal');
     
//      $usertype = new Element\Select('subUserCategory');
//        $usertype->setLabel('Type of user');
//        $usertype->setValueOptions(  $this->getOptionUserCat());
//       $usertype->setAttribute("class", "dropDnInput");
//       $usertype->setAttribute("id", "subUserCategory");
//        $this->add($usertype);
 
 $this->add(array(
    		'name' => 'subUsertype',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Add  User Type',
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
    
    
   
}
