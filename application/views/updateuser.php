<div class="col-lg-4 col-lg-offset-4">
    <h2>Change info</h2>
    <h5>Hello <span><?php echo $first_name; ?></span>.</h5>     
<?php 
    $fattr = array('class' => 'form-signin');
    echo form_open(site_url().'main/updateuser/'.$groups->id, $fattr); ?>
    
    
    <div class="form-group">
      <?php echo form_input(array('name'=>'firstname', 'id'=> 'firstname', 'placeholder'=>'Name', 'class'=>'form-control', 'value' => set_value('firstname', $groups->first_name)));  ?>
      <?php echo form_error('firstname');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'company', 'id'=> 'company', 'placeholder'=>'company', 'class'=>'form-control', 'value'=> set_value('company', $groups->company))); ?>
      <?php echo form_error('company');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'dpt', 'id'=> 'dpt', 'placeholder'=>'position', 'class'=>'form-control', 'value'=> set_value('dpt', $groups->dpt))); ?>
      <?php echo form_error('dpt');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'phone', 'id'=> 'phone', 'placeholder'=>'phone', 'class'=>'form-control', 'value'=> set_value('phone', $groups->phone))); ?>
      <?php echo form_error('phone');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'email', 'id'=> 'email', 'placeholder'=>'Email', 'class'=>'form-control', 'value'=> set_value('email', $groups->email))); ?>
    </div>
     
    <?php echo form_submit(array('value'=>'Change', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
    <?php echo form_close(); ?>
</div>