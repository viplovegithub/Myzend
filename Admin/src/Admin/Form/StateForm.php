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

class StateForm extends Form
{
   
    public function __construct (EntityManager $em, $name = null, $options = array())
    {
         
        $this->em = $em;
       
        parent::__construct('StateForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');

   
      $country = new Element\Select('cntryId');
       $country->setLabel('Country name');
            $country->setValueOptions(
               $this->getOptionCountry()
             );
      $country->setAttribute("class", "dropDnInput");
       $this->add($country); 
        $this->add(array(
    		'name' => 'stname',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'State name',
                                'class' => 'commonDropDnInput',
    		),
    		
        ));
         $cntrydescription = new Element\Textarea('statedescription');
         $cntrydescription->setLabel('State Description');
         $cntrydescription->setAttribute("class", "selectAreaInput");
         $cntrydescription->setAttribute("rows", "4");
          $cntrydescription->setAttribute("cols", "50");
         $this->add($cntrydescription);
        
         $cntryspecialInstructions = new Element\Textarea('statespecialInstructions');
         $cntryspecialInstructions->setLabel('State Special Instruction Description');
         $cntryspecialInstructions->setAttribute("class", "selectAreaInput");
         $cntryspecialInstructions->setAttribute("rows", "4");
         $cntryspecialInstructions->setAttribute("cols", "50");
         $this->add($cntryspecialInstructions);
         $cntrybestSeasonToVisit = new Element\Textarea('statebestSeasonToVisit');
         $cntrybestSeasonToVisit->setLabel('State Best Season to visit');
         $cntrybestSeasonToVisit->setAttribute("class", "selectAreaInput");
         $cntrybestSeasonToVisit->setAttribute("rows", "4");
         $cntrybestSeasonToVisit->setAttribute("cols", "50");
         $this->add($cntrybestSeasonToVisit);
         $this->add(array(
    		'name' => 'latitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'State Lattitude',
                               'class' => 'commonDropDnInput',
    		),
    		
        ));
        $this->add(array(
    		'name' => 'longitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'State Longitude',
                                 'class' => 'commonDropDnInput',
    		),
    		
        ));
     $this->add(array(
            'name' => 'statePhoto',
            'attributes' => array(
                'type'  => 'file',
                'allowEmpty'=>False,
            ),
            'options' => array(
               'label' => 'File Upload',
            ),
          
        )); 
//         

        // File Input
        
      
        

 
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
    
     
    public function getOptionCountry(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'c.countryId , c.cntryName')
              ->add('from', '\Admin\Entity\Country c') ;
        $result = $queryBuilder->getQuery()->getArrayResult();
         $selectData = array(''=>'select Country');
           foreach ($result as $res) {
            $selectData[$res['countryId']] = $res['cntryName'];
        }
        return $selectData;
    }
    
}
 