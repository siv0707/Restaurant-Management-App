<?php if(isset($_SESSION['msg'])) { ?>
<div role="alert" class="alert alert-success alert-dismissible">
   <button style="margin-top: 4px;" aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
   <?php echo $_SESSION['msg']; ?>
</div>
<?php unset($_SESSION['msg']); }?>