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
class CityForm extends Form
{
    public function __construct (EntityManager $em, $name = null, $options = array())
    {
        $this->em = $em;
        parent::__construct('CityForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');

    $this->add(array(
    		'name' => 'ctname',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'City name',
                               'class' => 'commonDropDnInput',
    		),
    		
        ));
     
        $stid = new Element\Select('stid');
           $stid->setLabel('State name');
             $stid->setAttributes(array(
             'id'  => 'stid',
              ));
            $stid->setValueOptions(
               $this->getOptionState()
             );
             $stid->setAttribute("class", "dropDnInput");
           $this->add($stid); 
    
        $ctcatid = new Element\Select('ctcatid');
           $ctcatid->setLabel('City category');
            $ctcatid->setValueOptions(
             $this->getOptionCityCat()
             );
              $ctcatid->setAttribute("class", "dropDnInput");
       $this->add($ctcatid); 
       
         $ctdescription = new Element\Textarea('ctdescription');
         $ctdescription->setLabel('City Description');
          $ctdescription->setAttribute("class", "selectAreaInput");
         $ctdescription->setAttribute("rows", "4");
         $ctdescription->setAttribute("cols", "50");
         $this->add($ctdescription);
         $ctspecialInstructions= new Element\Textarea('ctspecialInstructions');
         $ctspecialInstructions->setLabel('City Special Instruction Description');
         $ctspecialInstructions->setAttribute("class", "selectAreaInput");
         $ctspecialInstructions->setAttribute("rows", "4");
         $ctspecialInstructions->setAttribute("cols", "33");
         $this->add($ctspecialInstructions);
         $ctbestSeasonToVisit = new Element\Textarea('ctbestSeasonToVisit');
         $ctbestSeasonToVisit->setLabel('City Best Season to visit');
         $ctbestSeasonToVisit->setAttribute("class", "selectAreaInput");
         $ctbestSeasonToVisit->setAttribute("rows", "4");
         $ctbestSeasonToVisit->setAttribute("cols", "43");
         
         $this->add($ctbestSeasonToVisit);
         $this->add(array(
    		'name' => 'ctlatitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'City Lattitude',
                                 'class' => 'commonDropDnInput',
    		),
    		
        ));
        $this->add(array(
    		'name' => 'ctlongitude',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'City Longitude',
                                'class' => 'commonDropDnInput',
    		),
    		
        ));
      
      $this->add(array(
            'name' => 'cityPhoto',
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
//    public function getOptionCountry(){
//         $em = $this->em;
//        $queryBuilder = $em->createQueryBuilder();
//        $queryBuilder->add('select', 'c.countryId , c.cntryName')
//              ->add('from', '\Geography\Entity\Country c') ;
//        $result = $queryBuilder->getQuery()->getArrayResult();
//         $selectData = array(''=>'select Country');
//           foreach ($result as $res) {
//            $selectData[$res['countryId']] = $res['cntryName'];
//        }
//        return $selectData;
//    }
    public function getOptionCityCat(){
         $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'cc.cityCategoryId ,cc.ctCatName')
              ->add('from', '\Admin\Entity\CityCategory cc') ;
        $result = $queryBuilder->getQuery()->getArrayResult();
         $selectData = array(''=>'City Category');
           foreach ($result as $res) {
            $selectData[$res['cityCategoryId']] = $res['ctCatName'];
        }
        return $selectData;
    }
}
