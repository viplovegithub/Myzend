<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

use Admin\Form\VehicleForm;

use  Admin\Entity\Vehicle;
use Admin\Entity\VehicleBrand;
use Admin\Entity\VehicleTrim;
use Admin\Entity\VehicleType;
use Admin\Entity\VehicleCategory;
use Admin\Entity\VehicleModel;
use Admin\Entity\VehicleColor; 
use Admin\Entity\VehicleModelVersion;

class VehicleController extends AbstractActionController
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    /**   
     * Entity manager instance
     *           
     * @var Doctrine\ORM\EntityManager
     */ 
    
    protected $em;
    
     /**
     * Returns an instance of the Doctrine entity manager loaded from the service 
     * locator
     * 
     * @return Doctrine\ORM\EntityManager
     */
    protected $resultSet;
    protected $dbAdapter;
 
public function getEntityManager() {
    if (null === $this->em) {
         $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_alternative');
        //$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
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

    
public function vehicledashboardAction() {
     $this->_checkIfUserIsLoggedIn();
       return new ViewModel();
    }
    
    
    public function addAction() {
        $this->_checkIfUserIsLoggedIn();
         $em=  $this->getEntityManager();
         
        $form = new VehicleForm($em);
        $form->get('submit')->setValue('Add Vehicle');
 
        $request = $this->getRequest();
        if ($request->isPost()) {
          //  print_r($request->isPost());die;
            
            $vehicle = new Vehicle();
           $vehiclebrand =   new VehicleBrand();
             $vehicletype =   new VehicleType();
              $vehiclecategory =   new VehicleCategory();
               $vehiclemodel =   new VehicleModel();
                $vehiclecolor =   new VehicleColor();
                 $vehiclemodelversion =   new VehicleModelVersion();
                 
           
            
            $form->setInputFilter($vehicle->getInputFilter());
           
            $form->setData($request->getPost()); 
            if ($form->isValid()) {
                 $vehicledata = $form->getData();
                $em = $this->getEntityManager();
                $queryBuilder = $em->createQueryBuilder();
                $queryBuilder->add('select', 'v.vehicleName ')
                        ->add('from', '\Admin\Entity\Vehicle v')
                        ->where("v.vehicleName = '" . $vehicledata['vehicleName'] . "'")
                        ->getQuery();
                $result = $queryBuilder->getQuery()->getArrayResult();
                if (count($result) == 0) {
               
                  $vehiclecategory->exchangeArray($form->getData());               
                $this->getEntityManager()->persist($vehiclecategory);
                    $this->getEntityManager()->flush();
                 $recentvcidid = $vehiclecategory->vehicleCategoryId;
                
                  $vehicletype->exchangeArray($form->getData());
                 $this->getEntityManager()->persist($vehicletype);
                  $this->getEntityManager()->flush();
                    $recentvtypeid = $vehicletype->vehicleTypeId;
                    
               $vehiclebrand->exchangeArray($form->getData());
                 $this->getEntityManager()->persist($vehiclebrand);
                  $this->getEntityManager()->flush();
                  $recentvbrandid = $vehiclebrand->vehicleBrandId;
                 
                  $vehiclemodel->exchangeArray($form->getData());
                $this->getEntityManager()->persist($vehiclemodel);
                 $this->getEntityManager()->flush();
                  $recentvmodelid = $vehiclemodel->vehicleModelId;
                
                $vehiclemodelversion->exchangeArray($form->getData());
                $this->getEntityManager()->persist($vehiclemodelversion);
                 $this->getEntityManager()->flush();
                  $recentvmodelvid = $vehiclemodelversion->vehicleModelVersionId;
                
                 $vehiclecolor->exchangeArray($form->getData());
                $this->getEntityManager()->persist($vehiclecolor);
                 $this->getEntityManager()->flush();
                 $recentvcolorid = $vehiclecolor->colorId;
                 
                
                 
                 $arrData['vehicleCategory'] =$recentvcidid;
                 $arrData['vehicleType'] =$recentvtypeid;
                 $arrData['vehicleBrand'] =$recentvbrandid;
                 $arrData['vehicleModel'] =$recentvmodelid;
                 $arrData['vehicleModelVersion'] =$recentvmodelvid;
                 $arrData['vehicleColor'] =$recentvcolorid;
                  $arrData['vehicleName'] =$vehicledata['vehicleName'];
                   $arrData['status'] =$vehicledata['status'];
                 
                 
                 $vehicle->exchangeArray($arrData);
                $this->getEntityManager()->persist($vehicle);
                
              
                $this->getEntityManager()->flush();
                  
                 $this->flashMessenger()->addSuccessMessage('Successfully add data!');
                
                      return $this->redirect()->toRoute('admin');
                }
 else {
                    $this->flashMessenger()->addErrorMessage('Vehicle Name already exists!');
                }
              
            }
            
        }
        return array('form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
            );
        
    }
    
    public function editAction() {
         $this->_checkIfUserIsLoggedIn();
         
      $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('vehicle', array(
                'action' => 'add'
            ));
        }
 
        $vehicle = $this->getEntityManager()->find('Vehicle\Entity\Vehicle', $id);
        if (!$vehicle) {
            return $this->redirect()->toRoute('vehicle');
        }
 
        $em=  $this->getEntityManager();
        $form = new VehicleForm($em);
        $tempPhoto =  $vehicle->vehiclePhoto ;
        $form->bind($vehicle);
        $form->get('submit')->setAttribute('value', 'Edit Vehicle');
 $form->getInputFilter()->get('vehiclePhoto')->setRequired(false);
        $request = $this->getRequest();
    
       if ($request->isPost()) {
            
            $form->setInputFilter($vehicle->getInputFilter());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                 $filename = $data['vehiclePhoto']['name'];
               if($filename==""){
                   $filename = $tempPhoto;
               }
               $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $vehicleName = str_replace(" ", "-", $data['vehicleName']) . $id . "." . $extension;
                $vehicle->setvehiclePhoto($vehicleName);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('./data/VehicleImg');
                $adapter->addFilter('Rename', $vehicleName, $filename);
               $adapter->receive($filename);
                   $this->getEntityManager()->flush();
                      return $this->redirect()->toRoute('vehicle');
               
 
               // $this->getEntityManager()->flush();
               // return $this->redirect()->toRoute('vehicle');
            }
        }
 
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function statusAction() {
        //echo   $textData = filter_input(INPUT_POST, 'textData'); die;
        $result=false;
        $textData = filter_input(INPUT_POST, 'textData'); 
        if($textData)
        {
         $id = (int) $textData;
                $vehicles = $this->getEntityManager()->find('Vehicle\Entity\Vehicle', $id);
              //  echo $vehicles->status;die;
                if ($vehicles->status==1) {
                    
                    $vehicles->status=0;
                    //$this->getEntityManager()->remove($vpurchaseinfos);
                    $this->getEntityManager()->flush();           
                }else{ $vehicles->status=1; $this->getEntityManager()->flush(); }
        $result = true;
        
        echo json_encode($result);
 
        exit;
        }
   }
  
 
        
        
        public function getautodataAction()    {
    //echo "here"; die;
       
        $em = $this->getEntityManager();
        $queryBuildervc = $em->createQueryBuilder();
        $queryBuildervt = $em->createQueryBuilder();
        $queryBuildervb = $em->createQueryBuilder();
        $queryBuildervm = $em->createQueryBuilder();
        $queryBuildervmv = $em->createQueryBuilder();
         $queryBuildervco = $em->createQueryBuilder();
        
        $queryBuildervc->add('select', 'DISTINCT vc.vehicleCategory ')
              ->add('from', '\Admin\Entity\VehicleCategory vc');
        $resultvc = $queryBuildervc->getQuery()->getArrayResult();
        
        $queryBuildervt->add('select', 'DISTINCT vt.vehicleTypeName')
              ->add('from', '\Admin\Entity\VehicleType vt');
        $resultvt = $queryBuildervt->getQuery()->getArrayResult();
        
        $queryBuildervb->add('select', 'DISTINCT vb.vehicleBrandName')
              ->add('from', '\Admin\Entity\VehicleBrand vb');
        $resultvb = $queryBuildervb->getQuery()->getArrayResult();
        
        $queryBuildervm->add('select', 'DISTINCT vm.vehicleModelName')
              ->add('from', '\Admin\Entity\VehicleModel  vm');
        $resultvm = $queryBuildervm->getQuery()->getArrayResult();
        
        $queryBuildervmv->add('select', 'DISTINCT vmv.vehicleModelVersionName')
              ->add('from', '\Admin\Entity\VehicleModelVersion vmv');
        $resultvmv = $queryBuildervmv->getQuery()->getArrayResult();
        
        $queryBuildervco->add('select', 'DISTINCT vco.colorName')
              ->add('from', '\Admin\Entity\VehicleColor vco');
        $resultvco = $queryBuildervco->getQuery()->getArrayResult();
      
         $results=  array_merge($resultvc,$resultvt,$resultvb,$resultvm,$resultvmv,$resultvco); 
        
        echo json_encode($results);
 
        exit;
 
        }
        
 final private function _checkIfUserIsLoggedIn() {
        $em=$this->getEntityManager();
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($authService->hasIdentity()) {
            $user=$authService->getIdentity();
          //  print_r($user);die;
            $userId=$authService->getIdentity()->uId;
           $this->layout()->setVariable('user', $authService->getIdentity());
           
         //   $success = $em->getRepository('User\Entity\Company')->findOneByuserid($userId);
            
          //  $this->layout()->setVariable('company', $success);
            
        } else {
			//$this->flashMessenger()->clearMessagesFromContainer();
            $this->flashMessenger()->addErrorMessage('Session expired or not valid.');
            return $this->redirect()->toRoute('signin');
        }
	
        
    }
        
}