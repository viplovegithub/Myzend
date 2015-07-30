<?php

namespace Admin\tarrifflisting\listing;

use ZfTable\AbstractTable;



class Toll extends AbstractTable
{

    protected $config = array(
        //'name' => 'Toll table',
        'showPagination' => true,
       // 'showQuickSearch' => true,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => true,
        'valuesOfItemPerPage' => array(5, 10, 20, 50 , 100 , 200),
        'rowAction' => ''
    );

    /**
     * @var array Definition of headers
     */
    protected $headers = array(
      //  'tollId'     => array('tableAlias' => 'a','filters' => 'text','title' => 'Toll Id', 'width' => '11') ,
        'tollName'   => array('tableAlias' => 'a','filters' => 'text','title' => 'Toll Name', 'width' => '200' ),
        'ctname'     => array('tableAlias' => 'c','filters' => 'text','title' => 'City Name', 'width' => '11'  ),
        'locname' => array('tableAlias' => 'l','filters' => 'text','title' => 'Location Name', 'width' => '11'  ),
          'status' =>     array( 'tableAlias' => 'a',  'title' => 'Status' ) ,
        'action'     => array(   'sortable' => false),
    );

    public function init()
    {
        
        $this->getHeader('action')->getCell()->addDecorator('template', array(
                    
           'template' =>  '<span class="userEditOptionsDiv"> '.
            
            '<span class="userEditOptionsPopup">'.
'<span class="userEditOptionTxt"><a href="/admin/tarriff/edit/%s">Modify Toll Tariff</a></span>'.  
 '<span class="userEditOptionTxt status"  id="%s"><a  href="javascript:void(0)" >Change Status</a></span>'.
    
            '</span>'
             . '</span>'                          ,
            'vars' => array( 'tollId','tollId')                      
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

        foreach ($arrayData as $key => $row)
        {
            if ($value = $this->getParamAdapter()->getValueOfFilter('tollName'))
            {
                if (stripos($row['tollName'], $value) === false && !isset($keys[$key]))
                {
                    $keys[] = $key;
                }
            }
            if ($value = $this->getParamAdapter()->getValueOfFilter('ctname'))
            {
                if (stripos($row['ctname'], $value) === false && !isset($keys[$key]))
                {
                    $keys[] = $key;
                }
            }
             if ($value = $this->getParamAdapter()->getValueOfFilter('locname'))
            {
                if (stripos($row['locname'], $value) === false && !isset($keys[$key]))
                {
                    $keys[] = $key;
                }
            }
        }
        foreach ($keys as $key)
        {
            if (isset($arrayData[$key]) && $arrayData[$key] == true)
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
                var myData = {textData:getValue,type:"Toll"};
             $.ajax({
                type:"POST",
                url:"/admin/geography/changestatus", // call to Action method in COntroller
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