<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

use Admin\Form\DesignationForm;
use Admin\Form\DepartmentForm;
use Admin\Form\AddressTypeForm;
use Admin\Form\DocumentTypeForm;
use Admin\Form\SubUserForm;
use Admin\Form\CompanyTypeForm;
use Admin\Form\BranchTypeForm;

use Admin\Entity\Designation;
use Admin\Entity\Department;
use Admin\Entity\AddressType;
use Admin\Entity\DocumentType;
use Admin\Entity\SubUser;
use Admin\Entity\CompanyType;
use Admin\Entity\BranchType;
use Admin\userlisting\listing;

class UserController extends AbstractActionController
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
     //return $this->em;
//return $this->em;
    
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
public function userdashboardAction() {
     $this->_checkIfUserIsLoggedIn();
       return new ViewModel();
    }
     public function designationAction() {
         
       $this->_checkIfUserIsLoggedIn();   
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'desgn.designationId,desgn.designationName,desgn.status')
               ->add('from', '\Admin\Entity\Designation desgn')
                      ->orderBy('desgn.designationId', 'DESC')
                      ;
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
         
//        $designation = $this->getEntityManager()->getRepository('\User\Entity\Designation')->findAll();
//
//        return new ViewModel(array('designation' => $designation));
    }
     public function ajaxdesignationAction() {
          $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'desgn.designationId,desgn.designationName,desgn.status')
               ->add('from', '\Admin\Entity\Designation desgn')
                      ->orderBy('desgn.designationId', 'DESC')
                      ;
                      
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
    
    public function designationaddAction() {
      $this->_checkIfUserIsLoggedIn();
        $form = new DesignationForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $designation = new Designation();
            $form->setInputFilter($designation->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $designation->exchangeArray($form->getData());
                $this->getEntityManager()->persist($designation);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'designation'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
    
      public function designationeditAction() {
          $this->_checkIfUserIsLoggedIn();
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'designationadd'
                ));
            
        }

        $designation = $this->getEntityManager()->find('Admin\Entity\Designation', $id);
       
        if (!$designation) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'designation'
                ));
            
        }
         
        $form = new DesignationForm();
        $form->bind($designation);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
         
        if ($request->isPost()) {

            $form->setInputFilter($designation->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'designation'
                ));
              
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
      }
    
  //-----END DESIGNATION ACTIONS ---------//       
  //-----START DEPARTMENT ACTIONS ---------//    
    public function departmentAction() {

        $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
         $queryBuilder->add('select', 'dept.departmentid as Id,dept.departmentName as Department_Name,dept.status as Status')
               ->add('from', '\Admin\Entity\Department dept')
                       ->orderBy('dept.departmentid', 'DESC');
              
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
    
     public function ajaxdepartmentAction() {
         
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $queryBuilder->add('select', 'dept.departmentid,dept.departmentName,dept.status')
               ->add('from', '\Admin\Entity\Department dept')
                       ->orderBy('dept.departmentid', 'DESC');
             
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
 
    public function departmenteditAction(){
        $this->_checkIfUserIsLoggedIn();
           $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
             return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'departmentadd'
                ));
           
        }
        $department = $this->getEntityManager()->find('Admin\Entity\Department', $id);
        if (!$department) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'department'
                ));
            
        }
       $form = new DepartmentForm();
        $form->bind($department);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
         
        if ($request->isPost()) {

            $form->setInputFilter($department->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'department'
                ));
                 
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
        
    }

       public function addresstypeAction() {
            $this->_checkIfUserIsLoggedIn();
             $queryBuilder = $this->getEntityManager()->createQueryBuilder();
              $queryBuilder->add('select', 'addr.addresstypeid ,addr.addrTypeName,addr.status')
              ->add('from', '\Admin\Entity\AddressType addr')
               ->orderBy('addr.addresstypeid', 'DESC');
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
     public function ajaxaddresstypeAction() {
           $queryBuilder = $this->getEntityManager()->createQueryBuilder();
              $queryBuilder->add('select', 'addr.addresstypeid ,addr.addrTypeName,addr.status')
              ->add('from', '\Admin\Entity\AddressType addr')
               ->orderBy('addr.addresstypeid', 'DESC');
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
  
   public function addresstypeaddAction() {
       $this->_checkIfUserIsLoggedIn();
        $form = new AddressTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $addresstype = new AddressType();
            $form->setInputFilter($addresstype->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $addresstype->exchangeArray($form->getData());
                $this->getEntityManager()->persist($addresstype);
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'addresstype'
                ));
               
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }

    public function addresstypeeditAction() {
        $this->_checkIfUserIsLoggedIn();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'addresstypeadd'
                ));
            
        }
    $addresstype = $this->getEntityManager()->find('Admin\Entity\AddressType', $id);
        if (!$addresstype) {
return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'addresstype'
                ));
            
        }
        $form = new AddressTypeForm();
        $form->bind($addresstype);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($addresstype->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'addresstype'
                ));
                 
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
     public function documentAction() {
         $this->_checkIfUserIsLoggedIn();
 $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'doct.docTypeId,doct.docTypeName,doct.status')
               ->add('from', '\Admin\Entity\DocumentType doct')
                      ->orderBy('doct.docTypeId', 'DESC');
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
   public function ajaxdocumentAction() {
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'doct.docTypeId,doct.docTypeName,doct.status')
               ->add('from', '\Admin\Entity\DocumentType doct')
                      ->orderBy('doct.docTypeId', 'DESC');;
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
    public function documentaddAction() {
        $this->_checkIfUserIsLoggedIn();
        $form = new DocumentTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $documentType = new DocumentType();
            $form->setInputFilter($documentType->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $documentType->exchangeArray($form->getData());
                $this->getEntityManager()->persist($documentType);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'document'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
  public function documenteditAction() {
      $this->_checkIfUserIsLoggedIn();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'documentadd'
                ));
          
        }

        $document = $this->getEntityManager()->find('Admin\Entity\DocumentType', $id);
       
        if (!$document) {
return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'document'
                ));
           
        }
         
        $form = new DocumentTypeForm();
        $form->bind($document);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($document->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'document'
                ));
                
            }
        }
    return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function subuserAction() {
        $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
        
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $queryBuilder->add('select', 'su.subUTypeId as Id,su.subUsertype as UserType,su.status as Status')
            ->add('from', '\Admin\Entity\SubUser su') 
              //->innerJoin('\Admin\Entity\UserCategory', 'uc',\Doctrine\ORM\Query\Expr\Join::WITH,
                // 'su.subUserCategory=uc.usercatId') 
                   ->orderBy('su.subUTypeId', 'DESC');
              
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

      public function ajaxsubuserAction() {
          $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $queryBuilder->add('select', 'su.subUTypeId ,su.subUsertype,su.status')
            ->add('from', '\Admin\Entity\SubUser su') 
              //->innerJoin('\Admin\Entity\UserCategory', 'uc',\Doctrine\ORM\Query\Expr\Join::WITH,
                // 'su.subUserCategory=uc.usercatId') 
                   ->orderBy('su.subUTypeId', 'DESC');
             
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
       
    public function subuseraddAction() {
        $this->_checkIfUserIsLoggedIn();
         $em = $this->getEntityManager();
        $form = new SubUserForm( $em);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $subuser = new SubUser();
            $form->setInputFilter($subuser->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $subuser->exchangeArray($form->getData());
                $this->getEntityManager()->persist($subuser);
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'subuser'
                ));
               
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }

    
    
     public function subusereditAction() {
          $this->_checkIfUserIsLoggedIn();
         $id = (int) $this->params()->fromRoute('id', 0);
          if (!$id) {
  return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'subusertypeadd'
                ));
          
         }
          $subuser = $this->getEntityManager()->find('Admin\Entity\SubUser', $id);
        if (!$subuser) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'subuser'
                ));
           
        }
        $em = $this->getEntityManager();
       $form = new SubUserForm($em);
        $form->bind($subuser);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
       if ($request->isPost()) {
            $form->setInputFilter($subuser->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'subuser'
                ));
                
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function subuserdeleteAction(){
        $result=false;
        $textData =  filter_input(INPUT_POST, "textData");
        if($textData)
        {
                $id = (int) $textData;
                $subUser = $this->getEntityManager()->find('Admin\Entity\SubUser', $id);
                if ($subUser) {
                    $this->getEntityManager()->remove($subUser);
                    $this->getEntityManager()->flush();   
                }
             $result = true;
             echo json_encode($result);
             exit;
          }
    }
     //----START COMPANY TYPE ------//
   public function companytypeAction() {
          $this->_checkIfUserIsLoggedIn();   
       $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'ct.comptypeid,ct.companyType,ct.status')
               ->add('from', '\Admin\Entity\CompanyType ct')
                  ->orderBy('ct.comptypeid', 'DESC');
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
      public function ajaxcompanytypeAction() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'ct.comptypeid,ct.companyType,ct.status')
               ->add('from', '\Admin\Entity\CompanyType ct')
                  ->orderBy('ct.comptypeid', 'DESC');
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
    
    public function companytypeaddAction() {
        $this->_checkIfUserIsLoggedIn();
        $form = new CompanyTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $companyType = new CompanyType();
            $form->setInputFilter($companyType->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $companyType->exchangeArray($form->getData());
                $this->getEntityManager()->persist($companyType);
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'companytype'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }

    public function companytypeeditAction() {
        $this->_checkIfUserIsLoggedIn();
    $id = (int) $this->params()->fromRoute('id', 0);
       if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'companytypeadd'
                ));
           
        }
         $companyType = $this->getEntityManager()->find('Admin\Entity\CompanyType', $id);
        if (!$companyType) {
                return $this->redirect()->toRoute('admin/default', array(
                                     'controller' => 'user',                       
                                            'action' => 'companytype'
                                ));
           
        }
        $form = new CompanyTypeForm();
        $form->bind($companyType);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
       if ($request->isPost()) {
            $form->setInputFilter($companyType->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'companytype'
                ));
                
            }
        }
      return array(
            'id' => $id,
            'form' => $form,
        );
    }
//---END COMPANY TYPE------//  
    public function branchtypeAction() {
        $this->_checkIfUserIsLoggedIn();   
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'bt.branchtypeid,bt.branchtype,bt.status')
               ->add('from', '\Admin\Entity\BranchType bt')
             ->orderBy('bt.branchtypeid', 'DESC');
                      ;
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
//        $branchtype = $this->getEntityManager()->getRepository('\User\Entity\BranchType')->findAll();
//
//        return new ViewModel(array('branchtype' => $branchtype));
    }
 public function ajaxbranchtypeAction() {
       
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'bt.branchtypeid,bt.branchtype,bt.status')
               ->add('from', '\Admin\Entity\BranchType bt')
             ->orderBy('bt.branchtypeid', 'DESC');
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
    public function branchtypeaddAction() {
        $this->_checkIfUserIsLoggedIn();
        $form = new BranchTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $branchtype = new BranchType();
            $form->setInputFilter($branchtype->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $branchtype->exchangeArray($form->getData());
                $this->getEntityManager()->persist($branchtype);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'user',                       
                            'action' => 'branchtype'
                ));
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
public function branchtypeeditAction() {
    $this->_checkIfUserIsLoggedIn();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
        return $this->redirect()->toRoute('admin/default', array(
                            'controller' => 'user',                       
                                   'action' => 'branchtypeadd'
                       ));
            
        }

        $branchtype = $this->getEntityManager()->find('Admin\Entity\BranchType', $id);
       
        if (!$branchtype) {
return $this->redirect()->toRoute('admin/default', array(
                            'controller' => 'user',                       
                            'action' => 'branchtype'
                       ));
           
        }
         
        $form = new BranchTypeForm();
        $form->bind($branchtype);
        $form->get('save')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
         
        if ($request->isPost()) {

            $form->setInputFilter($branchtype->getInputFilter());
            $form->setData($request->getPost());
                       
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                            'controller' => 'user',                       
                                   'action' => 'branchtype'
                       ));
               
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
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
