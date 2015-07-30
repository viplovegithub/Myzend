<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Form\CountryForm;

use Admin\Form\StateForm;
use Admin\Form\CityCategoryForm;
use Admin\Form\CityForm;

use Admin\Form\LocationTypeForm;
use Admin\Form\LocationForm;

use Admin\Entity\Country;
use Admin\Entity\State;
use Admin\Entity\CityCategory;
use Admin\Entity\City;
use Admin\Entity\LocationType;
use Admin\Entity\Location;
use Admin\geographylisting\listing;
class GeographyController extends AbstractActionController {

    protected $em;
    protected $resultSet;
    protected $dbAdapter;
//-----START REGISTERING ENTITY MANAGER ------//
    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
   public function jsonResponse($data)
    {
        if(!is_array($data)){
            throw new Exception('$data param must be array');
        }
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($data));
        
        return $response;
    }
       public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
   //  print_r($response);die;
        return $response;
    } 
//-----END REGISTERING ENTITY MANAGER ------//

  
 
public function geodashboardAction() {
     $this->_checkIfUserIsLoggedIn();
       return new ViewModel();
    }
   
 
      
  
  //-----START COUNTRY ACTIONS ---------// 
   public function countryAction() {
 $this->_checkIfUserIsLoggedIn();
   $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'c.countryId,c.cntryName,c.status')
               ->add('from', '\Admin\Entity\Country c')
                    ->orderBy('c.countryId', 'DESC')  ;
             $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));
    }
    public function ajaxcountryAction() {
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'c.countryId,c.cntryName,c.status')
               ->add('from', '\Admin\Entity\Country c')
                    ->orderBy('c.countryId', 'DESC')  ;
             $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;


    }

    public function countryaddAction() {
         $this->_checkIfUserIsLoggedIn();
        $form = new CountryForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $country = new Country();
            
            $form->setInputFilter($country->getInputFilter());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $country->exchangeArray($form->getData());
                $this->getEntityManager()->persist($country);
                $this->getEntityManager()->flush();
                $recentid = $country->countryId;
                $filname = $data['contryphoto']['name'];
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $cntryname = str_replace(" ", "-", $data['cntryName']) . $recentid . "." . $extension;
                $country->setcountryPhoto($cntryname);
               
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/country');
                $adapter->addFilter('Rename', $cntryname, $filname);
                if ($adapter->receive($filname)) {
                    $this->getEntityManager()->persist($country);
                    $this->getEntityManager()->flush();
                      return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'country'
                ));
                    
                   
                }
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
 public function countryeditAction() {
      $this->_checkIfUserIsLoggedIn();
         
       $id = (int) $this->params()->fromRoute('id', 0);
  
        if (!$id) {
           
            return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'countryadd'
                ));
            
            
        }
 
       $country = $this->getEntityManager()->find('Admin\Entity\Country', $id);
      // print_r($city);die;
      if (!$country) {
          return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'country'
                ));
            
        }
        $em=  $this->getEntityManager();    
        $form  = new CountryForm($em);
        $tempcountry =  $country->contryphoto ;
     
        $form->bind($country);
        $form->get('save')->setAttribute('value', 'Edit');
        $form->getInputFilter()->get('contryphoto')->setRequired(false);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        
            $form->setInputFilter($country->getInputFilter());
        
             $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
             
            $form->setData($data);
            if ($form->isValid()) {
              $filname = $data['contryphoto']['name'];
               if($filname==""){
                   $filname = $tempcountry;
               }
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $cntryName = str_replace(" ", "-", $data['cntryName']) . $id . "." . $extension;
                $country->setcountryPhoto($cntryName);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/country');
                $adapter->addFilter('Rename', $cntryName, $filname);
                $adapter->receive($filname);
                $this->getEntityManager()->flush();
        
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'country'
                ));
                 
                
            }
        }
   
        return array(
            
            'id' => $id,
            'form' => $form,
        );
    }
    //-----END COUNTRY ACTIONS ---------// 
   //-----START STATE ACTIONS ---------//  
    public function stateAction(){
         $this->_checkIfUserIsLoggedIn();
           $queryBuilder =  $this->getEntityManager()->createQueryBuilder();
         $queryBuilder->add('select', 's.stateId , s.stname,c.cntryName,s.statedescription,s.statespecialInstructions,s.statebestSeasonToVisit,s.latitude,s.longitude')
              ->add('from', '\Admin\Entity\State s') 
                 ->innerJoin('\Admin\Entity\Country', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'c.countryId=s.cntryId')
                  ->orderBy('s.stateId', 'DESC');
          $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));
          
    }
     public function ajaxstateAction(){
          $queryBuilder = $this->getEntityManager()->createQueryBuilder();
              $queryBuilder->add('select', 's.stateId , s.stname,c.cntryName,s.status')
                  ->add('from', '\Admin\Entity\State s') 
                 ->innerJoin('\Admin\Entity\Country', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'c.countryId=s.cntryId')
                      ->orderBy('s.stateId', 'DESC');
             $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;





         
     }
    
    public function stateaddAction() {
         $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
        $form = new StateForm($em);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $state = new State();
            $form->setInputFilter($state->getInputFilter());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
     $form->setData($data);
            if ($form->isValid()) {
                $state->exchangeArray($form->getData());
                $this->getEntityManager()->persist($state);
                $this->getEntityManager()->flush();
                $recentid = $state->stateId;
                $filname = $data['statePhoto']['name'];
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $stname = str_replace(" ", "-", $data['stname']) . $recentid . "." . $extension;
                $state->setstatePhoto($stname);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/state');
                $adapter->addFilter('Rename', $stname, $filname);
                if ($adapter->receive($filname)) {
                    $this->getEntityManager()->persist($state);
                    $this->getEntityManager()->flush();
                   return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'state'
                ));
                    
                }
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
public function stateeditAction(){
     $this->_checkIfUserIsLoggedIn();
      $id = (int) $this->params()->fromRoute('id', 0);
  
        if (!$id) {
           return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'stateadd'
                ));
            
        }
 
       $state = $this->getEntityManager()->find('Admin\Entity\State', $id);
      // print_r($city);die;
      if (!$state) {
         return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'state'
                ));
           
        }
        $em=  $this->getEntityManager();    
        $form  = new StateForm($em);
        $tempstate =  $state->statePhoto;
        $form->bind($state);
        $form->get('save')->setAttribute('value', 'Edit');
        $form->getInputFilter()->get('statePhoto')->setRequired(false);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $form->setInputFilter($state->getInputFilter());
            //print_r($request->getPost());die;
             $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
           $form->setData($data);
            if ($form->isValid()) {
              $filname = $data['statePhoto']['name'];
               if($filname==""){
                   $filname = $tempstate;
               }
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $stname = str_replace(" ", "-", $data['stname']) . $id . "." . $extension;
                $state->setstatePhoto($stname);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/state');
                $adapter->addFilter('Rename', $stname, $filname);
                $adapter->receive($filname);
                $this->getEntityManager()->flush();
             return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'state'
                ));
                
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
}
  public function statedeleteAction(){
        $result=false;
        $textData =  filter_input(INPUT_POST, "textData");
        if($textData)
        {
         $id = (int) $textData;
               
                $state = $this->getEntityManager()->find('Admin\Entity\State', $id);
                $stateimg="public/images/state/".$state->statePhoto;
                if ($state) {
                    $this->getEntityManager()->remove($state);
                    $this->getEntityManager()->flush();   
                    unlink($stateimg);
                }
       $result = true;
       echo json_encode($result);
       exit;
    }
    
    }
        //-----END STATE ACTIONS ---------//  
 //-----START CITY CATEGORY ACTIONS ---------//     
     public function citycategoryAction() {
          $this->_checkIfUserIsLoggedIn();
           $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));
        //        $citycategory = $this->getEntityManager()->getRepository('\Admin\Entity\CityCategory')->findAll();
        //        return new ViewModel(array('citycategory' => $citycategory));
    }
     public function ajaxcitycategoryAction() {
         
        //        $citycategory = $this->getEntityManager()->getRepository('\Admin\Entity\CityCategory')->findAll();
        //        return new ViewModel(array('citycategory' => $citycategory));
       $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'cc.cityCategoryId,cc.ctCatName,cc.ctCatInfo,cc.status')
               ->add('from', '\Admin\Entity\CityCategory cc')
                      ->orderBy('cc.cityCategoryId', 'DESC');;
               $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;





         
    }
    public function citycategoryaddAction() {
          $this->_checkIfUserIsLoggedIn();
        $form = new CityCategoryForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ctcat = new CityCategory();
            $form->setInputFilter($ctcat->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ctcat->exchangeArray($form->getData());
                $this->getEntityManager()->persist($ctcat);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'citycategory'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
    public function citycategoryeditAction() {
          $this->_checkIfUserIsLoggedIn();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {

            
             return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'citycategoryadd'
                ));
                
        }

        $citycategory = $this->getEntityManager()->find('Admin\Entity\CityCategory', $id);
       
        if (!$citycategory) {

         
             return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'citycategory'
                ));
        }
         
        $form = new CityCategoryForm();
        $form->bind($citycategory);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
         
        if ($request->isPost()) {

            $form->setInputFilter($citycategory->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
             
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'citycategory'
                ));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

  //-----END CITY CATEGORY ACTIONS ---------// 
    
  //-----START CITY ACTIONS ---------//    
    
public function cityAction(){
      $this->_checkIfUserIsLoggedIn();
       $queryBuilder = $this->getEntityManager()->createQueryBuilder();
              $queryBuilder->add('select', 'ct.cityId ,ct.ctname,st.stname,cnt.cntryName,ct.status')
              ->add('from', '\Admin\Entity\City ct') 
                 ->innerJoin('\Admin\Entity\State', 'st',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'ct.stid=st.stateId')
                ->innerJoin('\Admin\Entity\Country', 'cnt',\Doctrine\ORM\Query\Expr\Join::WITH,
                'st.cntryId=cnt.countryId')
                      ->orderBy('ct.cityId', 'DESC');
               $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));

    
}    
    
public function ajaxcityAction() {
              $queryBuilder = $this->getEntityManager()->createQueryBuilder();
              $queryBuilder->add('select', 'ct.cityId ,ct.ctname,st.stname,cnt.cntryName,ct.status')
              ->add('from', '\Admin\Entity\City ct') 
                 ->innerJoin('\Admin\Entity\State', 'st',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'ct.stid=st.stateId')
                ->innerJoin('\Admin\Entity\Country', 'cnt',\Doctrine\ORM\Query\Expr\Join::WITH,
                'st.cntryId=cnt.countryId')
                      ->orderBy('ct.cityId', 'DESC');
             $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;           
        
}
    public function cityaddAction() {
        $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
        $form = new CityForm($em);
        $request = $this->getRequest();
         $error = array();
        if ($request->isPost()) {
         
            $city = new City();
          
            $form->setInputFilter($city->getInputFilter());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);
            
         if ($form->isValid()) {
              
                $city->exchangeArray($form->getData());
                $this->getEntityManager()->persist($city);
                $this->getEntityManager()->flush();
                $recentid = $city->cityId;
                $filname = $data['cityPhoto']['name'];
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $ctname = str_replace(" ", "-", $data['ctname']) . $recentid . "." . $extension;
                $city->setcityPhoto($ctname);
                    $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/city');
                $adapter->addFilter('Rename', $ctname, $filname);
                if ($adapter->receive($filname)) {
                    $this->getEntityManager()->persist($city);
                    $this->getEntityManager()->flush();
                     return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'city'
                ));
                    
                }
           
        } 
        
       }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
    
     public function cityeditAction() {
         $this->_checkIfUserIsLoggedIn();
       
       $id = (int) $this->params()->fromRoute('id', 0);
  
        if (!$id) {
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'cityadd'
                ));
             
        }
 
       $city = $this->getEntityManager()->find('Admin\Entity\City', $id);
      // print_r($city);die;
      if (!$city) {
          return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'city'
                ));
            
        }
        $em=  $this->getEntityManager();    
        $form  = new CityForm($em);
     //  echo  $form->get('cityPhoto')->getValue();
        $tempcity =  $city->cityPhoto ;
        $form->bind($city);
        $form->get('save')->setAttribute('value', 'Edit');
        $form->getInputFilter()->get('cityPhoto')->setRequired(false);
        $request = $this->getRequest();
        $error = array();
        if ($request->isPost()) {
            
            $form->setInputFilter($city->getInputFilter());
            //print_r($request->getPost());die;
             $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
             
            $form->setData($data);
            if ($form->isValid()) {
              
                    $filname = $data['cityPhoto']['name'];
                      if($filname==""){
                          $filname = $tempcity;
                      }
                       $extension = pathinfo($filname, PATHINFO_EXTENSION);
                       $ctname = str_replace(" ", "-", $data['ctname']) . $id . "." . $extension;
                       $city->setcityPhoto($ctname);
                       $adapter = new \Zend\File\Transfer\Adapter\Http();
                       $adapter->setDestination('public/images/city');
                       $adapter->addFilter('Rename', $ctname, $filname);
                       $adapter->receive($filname);
                       $this->getEntityManager()->flush();
                        return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'city'
                ));
                       
                
            }
        }
    return array(
            'id' => $id,
            'form' => $form,
            'err' =>$error,    
        );
    }
    public function citydeleteAction(){
        $result=false;
        $textData =  filter_input(INPUT_POST, "textData");
        if($textData)
        {
         $id = (int) $textData;
                $city = $this->getEntityManager()->find('Admin\Entity\City', $id);
                $cityimg="public/images/city/".$city->cityPhoto;
                if ($city) {
                    $this->getEntityManager()->remove($city);
                    $this->getEntityManager()->flush();   
                    unlink($cityimg);
                }
       $result = true;
       echo json_encode($result);
       exit;
    }
    
    }
//-----END CITY ACTIONS ---------// 
    
//-----START AJAX ACTIONS ---------//     
    public function getstateAction() {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 's.stateId ,s.stname')
                ->add('from', '\Admin\Entity\State s')
                ->where("s.cntryId = '" . $_POST['cid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
        echo json_encode($result);
        exit;
    }
    public function getgeographyAction() {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'u.firstName ,u.uId,u.lastName')
                ->add('from', '\Admin\Entity\Signup u')
                ->where("u.uId = '" . $_POST['uid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
    //    print_r($result);
        echo json_encode($result);
        exit;
    }
   public function getcompanybranchesAction(){
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'cb.companyBranchId ,cb.branchName')
                ->add('from', '\Admin\Entity\CompanyBranches cb')
                ->where("cb.uCompDId = '" . $_POST['cid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
        echo json_encode($result);
        exit;
   }
     public function getsubgeographyAction() {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'su.subUTypeId ,su.subAdmintype')
                ->add('from', '\Admin\Entity\SubAdmin su')
                ->where("su.subAdminCategory = '" . $_POST['cid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
        echo json_encode($result);
        exit;
    } 
   public function getcityAction() {
  
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'c.cityId ,c.ctname')
                ->add('from', '\Admin\Entity\City c')
                ->where("c.stid =  '" . $_POST['cid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
        echo json_encode($result);
        exit;
    }
    
     public function getlocationAction() {
  
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select', 'l.locationId ,l.locname')
                ->add('from', '\Admin\Entity\Location l')
                ->where("l.ctid =  '" . $_POST['cid'] . "'");
        $result = $queryBuilder->getQuery()->getArrayResult();
        echo json_encode($result);
        exit;
    }
   //-----END AJAX ACTIONS ---------//   
   
 
    
 public function locationtypeAction() {
     $this->_checkIfUserIsLoggedIn();
      $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));
//        $locationtype = $this->getEntityManager()->getRepository('\Admin\Entity\LocationType')->findAll();
//
//        return new ViewModel(array('locationtype' => $locationtype));
    }
    
public function  ajaxlocationtypeAction(){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'loct.locationTypeId,loct.locTypeName,loct.status')
               ->add('from', '\Admin\Entity\LocationType loct')
                       ->orderBy('loct.locationTypeId', 'DESC');
               $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;





}

    public function locationtypeaddAction() {
         $this->_checkIfUserIsLoggedIn();
        $form = new LocationTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $locationtype = new LocationType();
            $form->setInputFilter($locationtype->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $locationtype->exchangeArray($form->getData());
                $this->getEntityManager()->persist($locationtype);
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'locationtype'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
 public function locationtypeeditAction() {
      $this->_checkIfUserIsLoggedIn();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'locationtypeadd'
                ));
           
        }

        $locationtype = $this->getEntityManager()->find('Admin\Entity\LocationType', $id);
       
        if (!$locationtype) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'locationtype'
                ));
          
        }
         
        $form = new LocationTypeForm();
        $form->bind($locationtype);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
         
        if ($request->isPost()) {

            $form->setInputFilter($locationtype->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'locationtype'
                ));
                 
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

     public function locationAction() {
         $this->_checkIfUserIsLoggedIn();
             $queryBuilder =  $this->getEntityManager()->createQueryBuilder();
         $queryBuilder->add('select', 'loc.locationId ,loc.locname,loc.bestSeasonToVisit,loc.timingOpeningNClosing,loc.specialInstructions,c.cntryName,s.stname,ct.ctname,lt.locTypeName')
              ->add('from', '\Admin\Entity\Location loc')
                 ->innerJoin('\Admin\Entity\LocationType', 'lt',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.loctypeid=lt.locationTypeId')
                  ->innerJoin('\Admin\Entity\State', 's',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.stid=s.stateId')
                  ->innerJoin('\Admin\Entity\Country', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 's.cntryId=c.countryId')
                 ->innerJoin('\Admin\Entity\City', 'ct',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.ctid=ct.cityId')
                     ->orderBy('loc.locationId ', 'DESC');
             $data= $queryBuilder->getQuery()->getArrayResult();  
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $key=str_replace("_"," ",$key);
        $keys[]=$key ; 
    }
   break;
}
              $fieldNames=$keys;
               $this->layout()->setVariable('fildnames', $fieldNames);
        return new ViewModel(array('fildnames'=>$fieldNames));
    }
 public function ajaxlocationAction() {
       $queryBuilder = $this->getEntityManager()->createQueryBuilder();
             $queryBuilder->add('select', 'loc.locationId ,loc.locname,c.cntryName,s.stname,ct.ctname,lt.locTypeName,loc.status')
              ->add('from', '\Admin\Entity\Location loc')
                 ->innerJoin('\Admin\Entity\LocationType', 'lt',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.loctypeid=lt.locationTypeId')
                  ->innerJoin('\Admin\Entity\State', 's',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.stid=s.stateId')
                  ->innerJoin('\Admin\Entity\Country', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 's.cntryId=c.countryId')
                 ->innerJoin('\Admin\Entity\City', 'ct',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'loc.ctid=ct.cityId')
                      ->orderBy('loc.locationId ', 'DESC');
               $data= $queryBuilder->getQuery()->getArrayResult();  
    //print_r($data);
    $keys=array();
          foreach($data as $array){    
    foreach($array as $key=>$value){
        $keys[]=$key ; 
    }
   break;
} 
     //  $columns=sizeof($keys); die;
              $fieldNames=$keys;
                 
        for($i=0;count($data)>$i;$i++)
       {
            $totalString1="";
           $string1="[";
           $totalString1[]='""';
            for($j=0;count($fieldNames)>$j;$j++)
             {
                $fieldnameval=$fieldNames[$j];
               $totalString1[] = '"'.$data[$i][$fieldnameval].'"';
             }
            $implodeString=implode(",", $totalString1);
            //echo $implodeString;die;
            $string1=$string1.$implodeString."]";
          $totalString2[$i]=$string1;
            
       }
     //print_r($totalString2);die;
      echo $implodeString2='{"data": ['.implode(",", $totalString2).']}'; 
            exit;
 }
    
    public function locationaddAction() {
        $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
        $form = new LocationForm($em);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $location = new Location();
            $form->setInputFilter($location->getInputFilter());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );

            $form->setData($data);

            if ($form->isValid()) {
               $location->exchangeArray($form->getData());
                $this->getEntityManager()->persist($location);
                $this->getEntityManager()->flush();
                $locid = $location->locationId;
                $filname = $data['locationPhoto']['name'];
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $locimg = str_replace(" ", "-", $data['locname']) . $locid . "." . $extension;
                $location->setlocationPhoto($locimg);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/locationPic');
                $adapter->addFilter('Rename', $locimg, $filname);
                if ($adapter->receive($filname)) {
                    $this->getEntityManager()->persist($location);
                    $this->getEntityManager()->flush();
                     return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'location'
                ));
                     
                }
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }

      public function locationeditAction() {
          $this->_checkIfUserIsLoggedIn();
         
       $id = (int) $this->params()->fromRoute('id', 0);
  
        if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'locationadd'
                ));
            
        }
 
       $location = $this->getEntityManager()->find('Admin\Entity\Location', $id);
      // print_r($city);die;
      if (!$location) {
          return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'location'
                ));
            
        }
        $em=  $this->getEntityManager();    
        $form  = new LocationForm($em);
     //  echo  $form->get('cityPhoto')->getValue();
        $templocationPhoto =  $location->locationPhoto ;
        $form->bind($location);
        $form->get('save')->setAttribute('value', 'Edit');
        $form->getInputFilter()->get('locationPhoto')->setRequired(false);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $form->setInputFilter($location->getInputFilter());
            //print_r($request->getPost());die;
             $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
             
            $form->setData($data);
            if ($form->isValid()) {
              $filname = $data['locationPhoto']['name'];
               if($filname==""){
                   $filname = $templocationPhoto;
               }
                $extension = pathinfo($filname, PATHINFO_EXTENSION);
                $locname = str_replace(" ", "-", $data['locname']) . $id . "." . $extension;
                $location->setlocationPhoto($locname);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('public/images/locationPic');
                $adapter->addFilter('Rename', $locname, $filname);
                $adapter->receive($filname);
                $this->getEntityManager()->flush();
                
                
                  return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'geography',                       
                            'action' => 'location'
                ));
 
               
            }
        }
   
        return array(
            
            'id' => $id,
            'form' => $form,
        );
    }
public function locationdeleteAction(){
      $result=false;
        $textData =  filter_input(INPUT_POST, "textData");
        if($textData)
        {
         $id = (int) $textData;
                $location = $this->getEntityManager()->find('Admin\Entity\Location', $id);
                $locimg="public/images/locationPic/".$location->locationPhoto;
                if ($location) {
                    $this->getEntityManager()->remove($location);
                    $this->getEntityManager()->flush();       
                    unlink($locimg);
                }
       $result = true;
       echo json_encode($result);
      exit;
    }
    
}

   public function changestatusAction(){
        $result=false;
        $textData = filter_input(INPUT_POST, 'textData'); 
        $type=filter_input(INPUT_POST, 'type'); 
      
        if($textData)
        {
         $id = (int) $textData;
                // $str="User\Entity"."\\".$type; 
                $statusChk = $this->getEntityManager()->find("Admin\Entity"."\\".$type, $id);
                if ($statusChk->status==1) {
                    $statusChk->status=0;
                    $this->getEntityManager()->flush();           
                }
                else { 
                    $statusChk->status=1; 
                    $this->getEntityManager()->flush();
                }
        $result = true;
        echo json_encode($result);
       exit;
        }
  }  
    public function successAction() {
        return new ViewModel(array(
            'msg' => 'Data Added Successfully',
        ));
    }

}
