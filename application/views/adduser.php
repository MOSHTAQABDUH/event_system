<div class="text-center clearfix">
         <h1 class="text-center">تسجيل الحضور</h1>
                    
                </div>
<div class="col-lg-4 col-lg-offset-4">
    <!-- <h2>Hello <
      ?php echo $first_name; ?>,</h2> -->
    <!-- <h5>Please enter the required information below.</h5>      -->
    <?php 
        $fattr = array('class' => 'form-signin');
        echo form_open('/main/adduser', $fattr);
    ?>
    <div class="form-group">
      <?php echo form_input(array('name'=>'firstname', 'id'=> 'firstname', 'placeholder'=>'الاسم', 'class'=>'form-control', 'value' => set_value('firstname'))); ?>
      <?php echo form_error('firstname');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'phone', 'id'=> 'phone','type'=> 'number', 'placeholder'=>'التلفون', 'class'=>'form-control', 'value'=> set_value('phone'))); ?>
      <?php echo form_error('phone');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'email', 'id'=> 'email', 'placeholder'=>'الايميل','type'=> 'email', 'class'=>'form-control', 'value'=> set_value('email'))); ?>
      <?php echo form_error('email');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'company', 'id'=> 'company', 'placeholder'=>'الجهة', 'class'=>'form-control', 'value'=> set_value('company'))); ?>
      <?php echo form_error('company');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'dpt', 'id'=> 'dpt', 'placeholder'=>'المسمى الوظيفي', 'class'=>'form-control', 'value'=> set_value('dpt'))); ?>
      <?php echo form_error('dpt');?>
    </div>
     
    <?php echo form_submit(array('value'=>'إضافة', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
    <?php echo form_close(); ?>
</div>