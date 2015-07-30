<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace Admin\banklisting\listing;

use ZfTable\AbstractTable;

class BankAccounts extends AbstractTable
{
   
    
 protected $config = array(
       // 'name' => 'Doctrine 2',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'showColumnFilters' => true,
    );

    //Definition of headers
    protected $headers = array(
             
             'bankName' =>        array( 'tableAlias' => 'ba', 'title' => 'Bank Name' , 'filters' => 'text'),
             'beneficiaryName' =>        array( 'tableAlias' => 'ba', 'title' => 'Beneficiary' , 'filters' => 'text'),
             'accountNumber' =>        array( 'tableAlias' => 'ba', 'title' => 'Account Number' , 'filters' => 'text'),
             'firstName' =>        array( 'tableAlias' => 'u', 'title' => 'First Name' , 'filters' => 'text'),
             'bankacctype' =>        array( 'tableAlias' => 'bat', 'title' => 'Account type' , 'filters' => 'text'),
             'status' =>     array( 'tableAlias' => 'ba',  'title' => 'Status' ) ,
             'action' =>       array( 'sortable' => false),
         
    );
   // protected $no = 2; 
    
    public function init()
    {
    
            $this->getHeader('action')->getCell()->addDecorator('template', array(
           'template' =>  '<span class="userEditOptionsDiv"> '.
           '<span class="userEditOptionsPopup">'.
'<span class="userEditOptionTxt"><a href="/admin/bank/bankaccountedit/%s">Modify API User</a></span>'.
'<span class="userEditOptionTxt status"  id="%s"><a  href="javascript:void(0)" >Change Status</a></span>'.               
'</span>'
                         ,
            'vars' => array( 'bankAccId','bankAccId')
                      
        ));  
      
$this->getHeader('action')->addClass('removeColumnOptions');
$this->getHeader('status')->getCell()->addDecorator('mapper', array(
            '0' => 'Inactive',
            '1' => 'Active'
));

    }

    
    
    protected function initFilters($arrayData)
    {
      
        
           $keys = array();
                    
         foreach($arrayData as $key => $row){    
          
            if ($value = $this->getParamAdapter()->getValueOfFilter('bankName')) {
                if(stripos($row['bankName'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
          
              if ($value = $this->getParamAdapter()->getValueOfFilter('beneficiaryName')) {
                if(stripos($row['beneficiaryName'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
             if ($value = $this->getParamAdapter()->getValueOfFilter('accountNumber')) {
                if(stripos($row['accountNumber'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
             if ($value = $this->getParamAdapter()->getValueOfFilter('bankacctype')) {
                if(stripos($row['bankacctype'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
            
            
  }
   //print_r($arrayData);die;
        foreach($keys as $key){
            
             if( isset($arrayData[$key]) && $arrayData[$key] == true) 
             {
            
            unset($arrayData[$key]);
             
             }
        }
       
       
    }
}
?>

<style>
   
 .userEditOptionsPopup{
    
    display:none;
 }
    </style>
    
  <script type="text/javascript">
   
   
   
    $(document).ready( function() {
   
       
        $(function() {
    // alert('2');
   $(".userEditOptionsDiv").hover(function() {
    //$(this).next("span").show();
      $(this).children('.userEditOptionsPopup').show();
    
    },function(){
 //   $(this).next("span").hide();
     $(this).children('.userEditOptionsPopup').hide();
   });
  });
       
     
         });
 
</script>
      <script>
        $(".status").click(function (){
            var yes=confirm('Are you sure that you want to Change the item?');
           
            if(yes)
            {
                var getValue = this.id;
                var myData = {textData:getValue,type:"BankAccount"};
             $.ajax({
                type:"POST",
                url:"/cabsaas/ZendSkeletonApplication/public/user/changestatus", // call to Action method in COntroller
                data:myData,
                dataType: "JSON",

                success: function(data){
                   
                    location.reload();

                },
               failure: (function(){
                   alert("Failure!!");
                  })
             });
         }

        });
    </script> 