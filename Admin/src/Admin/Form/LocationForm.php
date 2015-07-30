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
class LocationForm extends Form
{
    public function __construct (EntityManager $em, $name = null, $options = array())
    {
        $this->em = $em;
        parent::__construct('LocationForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');

    $this->add(array(
    		'name' => 'locname',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Location name',
                                 'class' => 'commonDropDnInput',
    		),
    	
        ));
     
   
    
    $stid = new Element\Select('stid');
       
           $stid->setLabel('State');
             $stid->setValueOptions(
             array(
                 '' => 'State',
               )
           );
           $stid->setValueOptions(
                    $this->getOptionState()
          );
          $stid->setAttributes(array(
                     'id'  => 'state',
           ));
              $stid->setAttribute("class", "dropDnInput");
              $stid->setDisableInArrayValidator(true);
         $this->add($stid); 
     
        $ctid = new Element\Select('ctid');
        $ctid->setLabel('City');
        $ctid->setAttributes(array(
            'id'  => 'city',
           ));
        $ctid->setValueOptions(
         array(
                 '' => 'City',
              )
         );
       $ctid->setAttribute("class", "dropDnInput");
       $ctid->setDisableInArrayValidator(true);
       $this->add($ctid); 
       
       $locationtype = new Element\Select('loctypeid');
         $locationtype->setLabel('Location Type');
         $locationtype->setValueOptions(
             $this->getOptionLocationType()
         );
         $locationtype->setAttribute("class", "dropDnInput");
         $this->add($locationtype); 
         
         $bestSeasonToVisit = new Element\Textarea('bestSeasonToVisit');
         $bestSeasonToVisit->setLabel('Best Season to visit');
          $bestSeasonToVisit->setAttribute("class", "selectAreaInput");
         $bestSeasonToVisit->setAttribute("rows", "4");
         $bestSeasonToVisit->setAttribute("cols", "50");
         $this->add($bestSeasonToVisit);
         
        $entry = new Element\Select('entry');
        $entry->setLabel('Entry');
        $entry->setValueOptions(array(
              '' => 'Entry',
             '1' => 'Yes',
             '2' => 'No',
         ));
       $entry->setAttribute("class", "dropDnInput");
       $this->add($entry);
      
         $timingOpeningNClosing= new Element\Textarea('timingOpeningNClosing');
         $timingOpeningNClosing->setLabel('Opening and closing timing');
         $timingOpeningNClosing->setAttribute("class", "selectAreaInput");
         $timingOpeningNClosing->setAttribute("rows", "4");
         $timingOpeningNClosing->setAttribute("cols", "50");
         $this->add($timingOpeningNClosing);
         $description= new Element\Textarea('description');
         $description->setLabel('Description');
         $description->setAttribute("class", "selectAreaInput");
         $description->setAttribute("rows", "4");
         $description->setAttribute("cols", "50");
         $this->add($description);
         
         $specialInstructions = new Element\Textarea('specialInstructions');
         $specialInstructions->setLabel('Special Instruction Description');
         $specialInstructions->setAttribute("class", "selectAreaInput");
         $specialInstructions->setAttribute("rows", "4");
         $specialInstructions->setAttribute("cols", "50");
          $this->add($specialInstructions);
           $notAllowed = new Element\Textarea('notAllowed');
         $notAllowed->setLabel('Not allowed reason');
         $notAllowed->setAttribute("class", "selectAreaInput");
         $notAllowed->setAttribute("rows", "4");
         $notAllowed->setAttribute("cols", "50");
          $this->add($notAllowed);
         
          $airportRailwayStatus = new Element\Select('airportRailwayStatus');
        $airportRailwayStatus->setLabel('Airport/Railway');
        $airportRailwayStatus->setValueOptions(array(
              '' => 'Select',
             '1' => 'Yes',
             '2' => 'No',
         ));
       $airportRailwayStatus->setAttribute("class", "dropDnInput");
       $this->add($airportRailwayStatus);
          
          $this->add(array(
    		'name' => 'citytemp',
    		'attributes' => array(
				'type' => 'hidden',
				'placeholder' => 'citytemp',
                                 'class' => 'commonDropDnInput',
                                'id'=>'citytemp',
    		),
    		
        )); 
         $this->add(array(
    		'name' => 'latitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'City Lattitude',
                                 'class' => 'commonDropDnInput',
    		),
    		
        ));
        $this->add(array(
    		'name' => 'longitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'City Longitude',
                               'class' => 'commonDropDnInput',
    		),
    		
        ));
      
      $this->add(array(
                'name' => 'locationPhoto',
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
    public function getOptionState(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 's.stateId , s.stname')
              ->add('from', '\Admin\Entity\State s') ;
        $result = $queryBuilder->getQuery()->getArrayResult();
         $selectData = array(''=>'select State');
           foreach ($result as $res) {
            $selectData[$res['stateId']] = $res['stname'];
        }
        return $selectData;
    }
    
    public function getOptionLocationType(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'loctype.locationTypeId ,loctype.locTypeName')
              ->add('from', '\Admin\Entity\LocationType loctype') ;
        $result = $queryBuilder->getQuery()->getArrayResult();
         $selectData = array(''=>'Location type');
           foreach ($result as $res) {
            $selectData[$res['locationTypeId']] = $res['locTypeName'];
        }
        return $selectData;
    }
}
