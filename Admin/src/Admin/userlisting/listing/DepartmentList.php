<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */

namespace Admin\userlisting\listing;

use ZfTable\AbstractTable;

class DepartmentList extends AbstractTable {

     
    protected $config = array(
        // 'name' => 'Doctrine 2',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        // 'zfTableItemPerPage' => 5,
        'valuesOfItemPerPage' => array(5),
        'itemCountPerPage' => 5,
        'showColumnFilters' => true,
    );
    //Definition of headers
    protected $headers = array(
        
        //     'departmentid' =>     array('tableAlias' => 'dept',  'title' => 'Department id' ) ,
        'departmentName' => array('tableAlias' => 'dept', 'title' => 'Department Name', 'filters' => 'text'),
        'status' => array('tableAlias' => 'dept', 'title' => 'Status'),
        'action' => array('sortable' => false),
    );

    // protected $no = 2;

     

    public function init() {
        //print_r($this->config);

        $this->getHeader('action')->getCell()->addDecorator('template', array(
            'template' => '<span class="userEditOptionsDiv"> ' .
            '<span class="userEditOptionsPopup">' .
            '<span class="userEditOptionTxt"><a href="/admin/user/departmentedit/%s">Modify Department</a></span>' .
            '<span class="userEditOptionTxt status"  id="%s"><a  href="javascript:void(0)" >Change Status</a></span>' .
            '</span>'
            . '</span>'
            ,
            'vars' => array('departmentid', 'departmentid')
        ));

        
        $this->getHeader('action')->addClass('removeColumnOptions');
        $this->getHeader('action')->addClass('removeColumnOptions');
        $this->getHeader('status')->getCell()->addDecorator('mapper', array(
            '0' => 'Inactive',
            '1' => 'Active'
        ));
    }

    protected function initFilters($arrayData) {


        $keys = array();

        foreach ($arrayData as $key => $row) {
            if ($value = $this->getParamAdapter()->getValueOfFilter('departmentName')) {
                if (stripos($row['departmentName'], $value) === false && !isset($keys[$key])) {
                    $keys[] = $key;
                }
            }
        }
        // print_r($arrayData);die;
        foreach ($keys as $key) {

            if (isset($arrayData[$key]) && $arrayData[$key] == true) {

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

    $(document).ready(function () {


        $(function () {
            // alert('2');
            $(".userEditOptionsDiv").hover(function () {
                //$(this).next("span").show();
                $(this).children('.userEditOptionsPopup').show();

            }, function () {
                //   $(this).next("span").hide();
                $(this).children('.userEditOptionsPopup').hide();
            });
        });



    });

</script>

<script>
    $(".status").click(function () {
        var yes = confirm('Are you sure that you want to Change the item?');

        if (yes)
        {
            var getValue = this.id;
            var myData = {textData: getValue, type: "Department"};
            $.ajax({
                type: "POST",
                url: "/admin/user/changestatus", // call to Action method in COntroller
                data: myData,
                dataType: "JSON",
                success: function (data) {
                    
                     $("#tableContainer").zfTable('/admin/user/ajax-department', {
            sendAdditionalParams: function() {
                var data =$('.params-wrap :input').serialize();
                return '&' + data;
            },
        });
                  //  var asc = 'asc';
              //   $.redirect('/admin/user/ajax-department', {'zfTableItemPerPage': 5, 'zfTableOrder': asc,'zfTablePage': 2},{'method' :'POST'}); 
               //  location.reload();

                },
                failure: (function () {
                    alert("Failure!!");
                })
            });
        }

    });
</script>  