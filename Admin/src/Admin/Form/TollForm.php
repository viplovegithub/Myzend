<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Doctrine\ORM\EntityManager;

class TollForm extends Form
{
    protected $em;

    public function __construct(EntityManager $em, $name = null, $options = array())
    {
        $this->em = $em;
        parent::__construct('toll');
        
        $this->setAttribute('method', 'post');
       $this->add(array(
            'name' => 'tollName',
            'attributes' => array(
                'type' => 'text',
                  'class' => 'commonDropDnInput',
                'placeholder' => 'Toll Name',
            ),
             
        ));
        
        $city = new Element\Select('cityId');
        $city->setEmptyOption("Select City Name");
         $city->setAttributes(array("id"=>"cityId"));
         $city->setAttributes(array("class"=>"dropDnInput"));
      //  $city->setAttributes(array('onChange'=>'setter("city",this.options[this.selectedIndex].text)','class'=>'dropDnInput'));
        $city->setValueOptions($this->getOptionsForSelectCity());
        $this->add($city);
        
        $locationId = new Element\Select('locationId');
      
        $locationId->setEmptyOption("Select Location Name");
         // $locationId->setAttributes("id","location");
              $locationId->setAttributes(array("id"=>"location"));
               $locationId->setAttributes(array("class"=>"dropDnInput"));
       // $locationId->setAttributes(array('onChange'=>'setter("city",this.options[this.selectedIndex].text)','class'=>'dropDnInput'));
        $locationId->setValueOptions($this->getOptionsForSelectLocation());
           $locationId->setDisableInArrayValidator(true);
        $this->add($locationId);
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
               'class'=>"btn-blue"
            ),
        ));
    }
        
    public function getOptionsForSelectCity()
    {
        $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder->add('select', 'v.cityId , v.ctname')
                ->add('from', '\Admin\Entity\City v')
        ;
        $result = $queryBuilder->getQuery()->getArrayResult();
        $selectData = array();

        foreach ($result as $res)
        {
            $selectData[$res['cityId']] = $res['ctname'];
        }

        return $selectData;
    }

    public function getOptionsForSelectLocation()
    {
        $em = $this->em;
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder->add('select', 'v.locationId , v.locname')
                ->add('from', '\Admin\Entity\Location v')
        ;
        $result = $queryBuilder->getQuery()->getArrayResult();
        $selectData = array();

        foreach ($result as $res)
        {
            $selectData[$res['locationId']] = $res['locname'];
        }

        return $selectData;
    }
        
}

