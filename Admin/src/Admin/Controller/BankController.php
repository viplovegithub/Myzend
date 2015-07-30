<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

use Admin\Form\BankForm;

use Admin\Form\BankAccountTypeForm;
use Admin\Form\BankDetailsForm;

use Admin\Entity\Bank;

use Admin\Entity\BankAccountType;
use Admin\Entity\BankDetails;
 
use Admin\banklisting\listing;
 
 

class BankController extends AbstractActionController
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
public function bankdashboardAction() {
     $this->_checkIfUserIsLoggedIn();
       return new ViewModel();
    }
     public function bankAction() {
        $this->_checkIfUserIsLoggedIn();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'b.bankId,b.bankName,b.status')
               ->add('from', '\Admin\Entity\Bank b')
                      ->orderBy('b.bankId', 'DESC');
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
      public function ajaxbankAction() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->add('select', 'b.bankId,b.bankName,b.status')
               ->add('from', '\Admin\Entity\Bank b')
                      ->orderBy('b.bankId', 'DESC');
                                
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

    
    
       public function bankaddAction() {
              $this->_checkIfUserIsLoggedIn();
        $form = new BankForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $bank = new Bank();
            $form->setInputFilter($bank->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $bank->exchangeArray($form->getData());
                $this->getEntityManager()->persist($bank);
                $this->getEntityManager()->flush();
                
                  return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bank'
                ));
                 
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }

    public function bankeditAction() {
           $this->_checkIfUserIsLoggedIn();
    $id = (int) $this->params()->fromRoute('id', 0);
       if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankadd'
                ));
         
        }
         $bank = $this->getEntityManager()->find('Admin\Entity\Bank', $id);
        if (!$bank) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bank'
                ));
           
        }
        $form = new BankForm();
        $form->bind($bank);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
       if ($request->isPost()) {
            $form->setInputFilter($bank->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bank'
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
        
        
        
     
 //-----END BANK ACCOUNT ACTIONS ---------//  
  
  //-----BANK DETAILS ACTIONS ---------//  
     public function bankdetailsAction() {
            $this->_checkIfUserIsLoggedIn();
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();
             $queryBuilder->add('select', 'bd.bankDetailsId,bd.branchName ,bd.ifscCode,ct.ctname,b.bankName,bd.status')
              ->add('from', '\Admin\Entity\BankDetails bd') 
                 ->innerJoin('\Admin\Entity\City', 'ct',\Doctrine\ORM\Query\Expr\Join::WITH,
            'bd.cityId=ct.cityId')
                ->innerJoin('\Admin\Entity\Bank', 'b',\Doctrine\ORM\Query\Expr\Join::WITH,
            'bd.bankName=b.bankId')
                ->orderBy('bd.bankDetailsId', 'DESC');
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
  
  
     public function ajaxbankdetailsAction() {
         
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();
             $queryBuilder->add('select', 'bd.bankDetailsId,bd.branchName ,bd.ifscCode,ct.ctname,b.bankName,bd.status')
              ->add('from', '\Admin\Entity\BankDetails bd') 
                 ->innerJoin('\Admin\Entity\City', 'ct',\Doctrine\ORM\Query\Expr\Join::WITH,
            'bd.cityId=ct.cityId')
                ->innerJoin('\Admin\Entity\Bank', 'b',\Doctrine\ORM\Query\Expr\Join::WITH,
            'bd.bankName=b.bankId')
                ->orderBy('bd.bankDetailsId', 'DESC');
                                          
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
     public function bankdetailsaddAction() {
            $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
         $form = new BankDetailsForm($em);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $bankdetails = new BankDetails();
            $form->setInputFilter($bankdetails->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $bankdetails->exchangeArray($form->getData());
                $this->getEntityManager()->persist($bankdetails);
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankdetails'
                ));
             
                
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
       public function bankdetailseditAction() {
              $this->_checkIfUserIsLoggedIn();
    $id = (int) $this->params()->fromRoute('id', 0);
       if (!$id) {
           return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankdetailsadd'
                ));
             
        }
         $bankdetails = $this->getEntityManager()->find('Admin\Entity\BankDetails', $id);
        if (!$bankdetails) {
return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankdetails'
                ));
            
        }
        $em = $this->getEntityManager();
        $form = new BankDetailsForm($em);
        $form->bind($bankdetails);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
       if ($request->isPost()) {
            $form->setInputFilter($bankdetails->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankdetails'
                ));
              
            }
        }
      return array(
            'id' => $id,
            'form' => $form,
        );
    }
     public function bankdetailsdeleteAction(){
          $result=false;
          $textData =  filter_input(INPUT_POST, "textData");
          if($textData)
              {
                   $id = (int) $textData;
                   $bankDetails = $this->getEntityManager()->find('Admin\Entity\BankDetails', $id);
                   if ($bankDetails) {
                     $this->getEntityManager()->remove($bankDetails);
                     $this->getEntityManager()->flush();           
                 }
        $result = true;
        echo json_encode($result);
        exit;
    }
  }
  //-----END BANK DETAILS ACTIONS ---------//    
    //-----START BANK ACCOUNT TYPE ACTIONS ---------// 
  
  public function bankaccounttypeAction(){
         $this->_checkIfUserIsLoggedIn();
         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->add('select', 'bat.bactypeid,bat.bankacctype,bat.status')
               ->add('from', '\Admin\Entity\BankAccountType bat')
               ->orderBy('bat.bactypeid', 'DESC');
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
    public function ajaxbankaccounttypeAction() {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->add('select', 'bat.bactypeid,bat.bankacctype,bat.status')
               ->add('from', '\Admin\Entity\BankAccountType bat')
               ->orderBy('bat.bactypeid', 'DESC');
                                  
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

    public function bankaccountypeaddAction() {
           $this->_checkIfUserIsLoggedIn();
        $form = new BankAccountTypeForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $baccounttype = new BankAccountType();
            $form->setInputFilter($baccounttype->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $baccounttype->exchangeArray($form->getData());
                $this->getEntityManager()->persist($baccounttype);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankaccounttype'
                ));
               
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
        ));
    }
    
    
  public function   bankaccounttypeeditAction(){
         $this->_checkIfUserIsLoggedIn();
       $id = (int) $this->params()->fromRoute('id', 0);
       if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankaccountypeadd'
                ));
            
        }
        $bankaccounttype = $this->getEntityManager()->find('Admin\Entity\BankAccountType', $id);
        if (!$bankaccounttype) {
 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankaccounttype'
                ));
            
        }
       $form = new BankAccountTypeForm();
        $form->bind($bankaccounttype);
        $form->get('save')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
       if ($request->isPost()) {
            $form->setInputFilter($bankaccounttype->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEntityManager()->flush();
                 return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'bank',                       
                            'action' => 'bankaccounttype'
                ));
                    
               
            }
        }
      return array(
            'id' => $id,
            'form' => $form,
        );
      
  }   

        
}