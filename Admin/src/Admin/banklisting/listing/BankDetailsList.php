<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace Admin\banklisting\listing;

use ZfTable\AbstractTable;

class BankDetailsList extends AbstractTable
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
        
           //  'bankDetailsId' =>     array('tableAlias' => 'bd',  'title' => 'Id' ) ,
             'bankName' =>        array( 'tableAlias' => 'b', 'title' => 'Bank Name' , 'filters' => 'text'),
             'branchName' =>        array( 'tableAlias' => 'bd', 'title' => 'Branch Name' , 'filters' => 'text'),
             'ifscCode' =>        array( 'tableAlias' => 'bd', 'title' => 'IFSC Code' , 'filters' => 'text'),
             'ctname' =>        array( 'tableAlias' => 'ct', 'title' => 'City' , 'filters' => 'text'),
             'status' =>     array( 'tableAlias' => 'ba',  'title' => 'Status' ) ,
             'action' =>       array( 'sortable' => false),
         
    );
   // protected $no = 2; 
    
    public function init()
    {
    
            $this->getHeader('action')->getCell()->addDecorator('template', array(
           'template' =>  '<span class="userEditOptionsDiv"> '.
           '<span class="userEditOptionsPopup">'.
'<span class="userEditOptionTxt"><a href="/admin/bank/bankdetailsedit/%s">Modify Bank Details</a></span>'.
'<span class="userEditOptionTxt status"  id="%s"><a  href="javascript:void(0)" >Change Status</a></span>'.               
'</span>'
                      ,
            'vars' => array( 'bankDetailsId','bankDetailsId')
                      
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
          
              if ($value = $this->getParamAdapter()->getValueOfFilter('branchName')) {
                if(stripos($row['branchName'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
             if ($value = $this->getParamAdapter()->getValueOfFilter('ifscCode')) {
                if(stripos($row['ifscCode'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
             if ($value = $this->getParamAdapter()->getValueOfFilter('ctname')) {
                if(stripos($row['ctname'], $value) === false && !isset($keys[$key]) ){
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
                var myData = {textData:getValue,type:"BankDetails"};
             $.ajax({
                type:"POST",
                url:"/admin/bank/changestatus", // call to Action method in COntroller
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