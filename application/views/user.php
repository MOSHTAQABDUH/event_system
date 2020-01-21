<div class="text-center clearfix">
         <h1 class="text-center">تسجيل الحضور</h1>
                     
                </div>
    <div class="container">
        <h2>الاعضاء</h2>
        <table class="table table-hover table-bordered table-striped datatable">
            <thead>

          <tr>
              <th>
                  الرقم
              </th>
              <th>
                  الاسم
              </th>
              <th>
              المؤهل
              </th>              
              <th>
              المسمى الوظيفي
              </th>  
              <th>
              جهة العمل
              </th>
              <th>
              طبيعة العمل
              </th>
              <th>
              عدد سنوات الخبرة
              </th>
              <th>
              رقم التواصل
              </th>
              <th>
              الايميل
              </th>
              <th>
              وقت الحضور
              </th>
              <th>
                  action
              </th>
          </tr>
          </thead> 
          <tbody> 
                 <?php foreach($groups  as $row) : ?>  
                    <?php if($row->id!=0) :?> 
                     <tr class="text-center align-items-center h6">
                         
                         <td>
                            <?php echo $row->id;?>
                        </td>
                         <td><?php echo $row->first_name;?></td>
                         <td><?php echo $row->qualification;?></td>
                         <td><?php echo $row->dpt;?></td>
                         <td><?php echo $row->company;?></td>
                         <td><?php echo $row->work_nature;?></td>
                         <td><?php echo $row->experience;?></td>
                         <td><?php echo $row->phone;?></td>
                         <td><?php echo $row->email;?></td>
                         <td><?php echo $row->last_login;?></td>
                         <td>  
                         <!-- <span style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="'.'update'.'"><i class="glyphicon glyphicon-edit" onclick="edituser(<?php echo $row->id;?>)"></i></span> -->
                          
                             <?php if(!$row->last_login) :?> 
                             <button type="submit" class="btn btn-success btn-sm" name="btn_do" id="btn_enable" onclick="present(<?php echo $row->id;?>)" value="enable"/>
                             <?php echo 'تسجيل حضور' ;?>
                             </button> 
                             <?php else:?>
                         <button type="submit" class="btn btn btn-warning btn-sm" name="btn_do" id="btn_enable" onclick="cancel(<?php echo $row->id;?>)" value="enable"/>
                             <?php echo 'إلغاء حضور' ;?>
                             </button> 
                             <?php endif;?>
                                <button type="submit" class="btn btn btn-warning btn-sm" name="btn_do" id="btn_enable" onclick="updateuser(<?php echo $row->id;?>)" value="enable"/>
                             <?php echo ' تعديل بيانات' ;?>
                             
                     </tr>
                 </form> 
                 <?php endif;?>
                 <?php endforeach;?>
                 

                
          </tbody>
        </table>
    </div>
   
<script type="text/javascript">  
function updateuser(id)
{ 
    window.location.href = "<?php echo base_url(); ?>main/updateuser/"+id;
    console.log(id);
    
} 
function present(id)
{ 
    console.log(id);
            $.getJSON("<?php echo base_url(); ?>main/present/"+id ,
                                    function (data, textStatus, jqXHR) { 
                                        
                                    },
                                    function (e) {    
                                        
                                    }
                                );    
                                location.reload();  
} 
function cancel(id)
{ 
    console.log(id);
            $.getJSON("<?php echo base_url(); ?>main/cancel/"+id ,
                                    function (data, textStatus, jqXHR) { 
                                        
                                    },
                                    function (e) {    
                                        
                                    }
                                );    
                                location.reload();  
} 
    
</script>
