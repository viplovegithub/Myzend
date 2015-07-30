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

class CountryForm extends Form
{
    public function __construct ($name = NULL)
    {

        parent::__construct('CountryForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');

    $this->add(array(
    		'name' => 'cntryName',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Country name',
                                    'class' => 'commonDropDnInput',
    		),
    		
        ));
       
         $cntrydescription = new Element\Textarea('cntrydescription');
         $cntrydescription->setLabel('Country Description');
         $cntrydescription->setAttribute("class", "selectAreaInput");
         $cntrydescription->setAttribute("rows", "4");
         $cntrydescription->setAttribute("cols", "39");
         $this->add($cntrydescription);
         $cntryspecialInstructions = new Element\Textarea('cntryspecialInstructions');
         $cntryspecialInstructions->setLabel('Country Special Instruction Description');
          $cntryspecialInstructions->setAttribute("class", "selectAreaInput");
         $cntryspecialInstructions->setAttribute("rows", "4");
         $cntryspecialInstructions->setAttribute("cols", "23");
         $this->add($cntryspecialInstructions);
         $cntrybestSeasonToVisit = new Element\Textarea('cntrybestSeasonToVisit');
         $cntrybestSeasonToVisit->setLabel('Country Best Season to Visit');
         $cntrybestSeasonToVisit->setAttribute("class", "selectAreaInput");
         $cntrybestSeasonToVisit->setAttribute("rows", "4");
         $cntrybestSeasonToVisit->setAttribute("cols", "33");
         $this->add($cntrybestSeasonToVisit);
         $this->add(array(
    		'name' => 'latitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Country Lattitude',
                                 'class' => 'commonDropDnInput',
    		),
    		
        ));
        $this->add(array(
    		'name' => 'longitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Country Longitude',
                                 'class' => 'commonDropDnInput',
    		),
    		
        ));
      
      $this->add(array(
            'name' => 'contryphoto',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
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
