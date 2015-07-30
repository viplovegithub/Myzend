<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

use Admin\Form\TollForm;
use Admin\Form\TerifflistForm;



use Admin\Entity\Toll;
use Admin\Entity\TarriffList;
use Admin\Entity\TarriffSubList;


use Admin\tarrifflisting\listing;


class TarriffController extends AbstractActionController
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
public function tariffdashboardAction() {
     $this->_checkIfUserIsLoggedIn();
       return new ViewModel();
    }
     public function addAction()
    {
       $this->_checkIfUserIsLoggedIn();
        $em = $this->getEntityManager();
        $form = new TollForm($em);
        $form->get('submit')->setValue('Add');
         $request = $this->getRequest();
          
         
        if ($request->isPost()) {
          
            $halfday = new \Admin\Entity\Toll();
            $form->setInputFilter($halfday->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                 
                $halfday->exchangeArray($form->getData());
                $this->getEntityManager()->persist($halfday);

                $this->getEntityManager()->flush();

                
                    return $this->redirect()->toRoute('admin/default', array(
                     'controller' => 'tarriff',                       
                            'action' => 'view'
                ));
               

            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {

 $this->_checkIfUserIsLoggedIn();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id)
        {
            return $this->redirect()->toRoute('admin/default', array(
                        'controller' => 'tarriff',
                        'action' => 'add'
            ));
        }

        $halfday = $this->getEntityManager()->find('Admin\Entity\Toll', $id);
        if (!$halfday)
        {
            return $this->redirect()->toRoute('admin/default', array(
                        'controller' => 'tarriff',
                        'action' => 'view'
            ));
        }

        $em = $this->getEntityManager();
        $form = new TollForm($em);
        $form->bind($halfday);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter($halfday->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $this->getEntityManager()->flush();

                
                return $this->redirect()->toRoute('admin/default', array(
                        'controller' => 'tarriff',
                        'action' => 'view'
            ));

            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id)
        {
            return $this->redirect()->toRoute('tariff/default', array(
                        'controller' => 'toll',
                        'action' => 'view'
            ));

        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes')
            {
                $id = (int) $request->getPost('id');
                $tariff = $this->getEntityManager()->find('Tariff\Entity\Toll', $id);
                if ($tariff)
                {
                    $this->getEntityManager()->remove($tariff);
                    $this->getEntityManager()->flush();
                }
            }

            
            return $this->redirect()->toRoute('tariff/default', array(
                        'controller' => 'toll',
                        'action' => 'view'
            ));

        }

        return array(
            'id' => $id,
            'tariff' => $this->getEntityManager()->find('Admin\Entity\Toll', $id)
        );
    }
    
    public function viewAction()
    {
        
         $this->_checkIfUserIsLoggedIn();
         $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select','a.tollId,a.tollName,c.ctname,l.locname,a.status')
                    ->add('from','Admin\Entity\Toll a')
                 ->innerJoin('\Admin\Entity\City', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'a.cityId=c.cityId')
                  ->innerJoin('\Admin\Entity\Location', 'l',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'a.locationId=l.locationId');;
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

    public function ajaxviewAction()
    {
      
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->add('select','a.tollId,a.tollName,c.ctname,l.locname,a.status')
                    ->add('from','Admin\Entity\Toll a')
                 ->innerJoin('\Admin\Entity\City', 'c',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'a.cityId=c.cityId')
                  ->innerJoin('\Admin\Entity\Location', 'l',\Doctrine\ORM\Query\Expr\Join::WITH,
                 'a.locationId=l.locationId');;
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
   //ADD TARRIFF AND TARRIFF SUB LIST
    public function tarriffaddAction(){
         $this->_checkIfUserIsLoggedIn();
         $form = new TerifflistForm();
          $form->get('submit')->setValue('Add Tarriff');
         $request = $this->getRequest();
        if ($request->isPost()) {
          //  print_r($request->isPost());die;
            
            $tarriffList = new TarriffList();
            $tarriffSubList =   new TarriffSubList();
            $form->setInputFilter($tarriffList->getInputFilter());
           
            $form->setData($request->getPost()); 
            if ($form->isValid()) {
                 $tarriffListdata = $form->getData();
                $em = $this->getEntityManager();
                $queryBuilder = $em->createQueryBuilder();
                $queryBuilder->add('select', 'tl.tariffName ')
                        ->add('from', '\Admin\Entity\TarriffList tl')
                        ->where("tl.tariffName = '" . $tarriffListdata['tariffName'] . "'")
                        ->getQuery();
                $result = $queryBuilder->getQuery()->getArrayResult();
                if (count($result) == 0) {
               
                  $tarriffList->exchangeArray($form->getData());               
                 $this->getEntityManager()->persist($tarriffList);
                 $this->getEntityManager()->flush();
                 $recenttariffListId = $tarriffList->tariffListId;
                 $arrData['tariffListId'] =$recenttariffListId;
                 $arrData['tariffSubName'] =$tarriffListdata['tariffSubName'];
                $tarriffSubList->exchangeArray($arrData);
                $this->getEntityManager()->persist($tarriffSubList);
                $this->getEntityManager()->flush();
                  
                 $this->flashMessenger()->addSuccessMessage('Successfully add data!');
                
                      return $this->redirect()->toRoute('admin');
                }
 else {
                    $this->flashMessenger()->addErrorMessage('Tarriff Name already exists!');
                }
              
            }
            
        }
        return array('form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
            );
          return array('form' => $form,
            'scc' => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err' => $this->flashMessenger()->getCurrentErrorMessages(),
            );
    }
    public function gettarrifflistAction()    {
    //echo "here"; die;
       
        $em = $this->getEntityManager();
        $queryBuildertl = $em->createQueryBuilder();
        $queryBuildertsl = $em->createQueryBuilder();
       
        
        $queryBuildertl->add('select', 'tl.tariffName')
              ->add('from', '\Admin\Entity\TarriffList tl');
        $resulttl = $queryBuildertl->getQuery()->getArrayResult();
        
        $queryBuildertsl->add('select', 'tsl.tariffSubName')
              ->add('from', '\Admin\Entity\TarriffSubList tsl');
        $resulttsl = $queryBuildertsl->getQuery()->getArrayResult();
      
         $results=  array_merge($resulttl,$resulttsl); 
        
        echo json_encode($results);
 
        exit;
 
        }
    
}