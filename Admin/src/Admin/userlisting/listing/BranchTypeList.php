<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace Admin\userlisting\listing;

use ZfTable\AbstractTable;

class BranchTypeList extends AbstractTable
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
            
             //'branchtypeid' =>     array('tableAlias' => 'bt',  'title' => 'Id' ) ,
             'branchtype' =>        array( 'tableAlias' => 'bt', 'title' => 'Bank Name' , 'filters' => 'text'),
             'status' =>     array( 'tableAlias' => 'bt',  'title' => 'Status' ) ,
             'action' =>       array( 'sortable' => false),
         
    );
   // protected $no = 2;
       
    public function init()
    {
    
    $this->getHeader('action')->getCell()->addDecorator('template', array(
          'template' =>  '<span class="userEditOptionsDiv"> '.
            '<span class="userEditOptionsPopup">'.
'<span class="userEditOptionTxt"><a href="/admin/user/branchtypeedit/%s">Modify Branch Type</a></span>'.
 '<span class="userEditOptionTxt status"  id="%s"><a  href="javascript:void(0)" >Change Status</a></span>'.                  
'</span>'
           . '</span>' 
                         ,
            'vars' => array( 'branchtypeid','branchtypeid')
                      
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
            if ($value = $this->getParamAdapter()->getValueOfFilter('branchtype')) {
                if(stripos($row['branchtype'], $value) === false && !isset($keys[$key]) ){
                    $keys[] = $key;
                }
            }
             
            
              
            
            
        }
     // print_r($arrayData);die;
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
                var myData = {textData:getValue,type:"BranchType"};
             $.ajax({
                type:"POST",
                url:"/admin/user/changestatus", // call to Action method in COntroller
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