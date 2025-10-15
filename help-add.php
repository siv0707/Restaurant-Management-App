<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php 

    if (isset($_POST['submit'])) {

        $data = array(
            'title' => $_POST['help_title'],
            'content' => $_POST['help_content']
        );      

        $qry = Insert('tbl_help', $data);                                    
                      
        redirect("help.php", "Help added successfully...");

    }

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

    <!--tab start-->
    <div class="container-fluid full-width-container">
        <h1></h1>
            
        <!--breadcrum start-->
        <ol class="breadcrumb text-left">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="help.php">Help</a></li>
          <li class="active">Add</li>
        </ol>
        <!--breadcrum end-->
    
        <div class="section"> 

            <form id="validationForm" method="post" enctype="multipart/form-data">
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">

                    <div class="group-fields clearfix row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="lead">ADD HELP</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Submit</button></p>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="help_title" class="control-label">
                                    Title *
                                </label>
                                <input type="text" name="help_title" id="help_title" class="form-control" placeholder="Title" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Content *</label>
                                <textarea required class="form-control" name="help_content"></textarea>
                                <script>                             
                                    CKEDITOR.replace( 'help_content' );
                                    <?php if ($ENABLE_RTL_MODE == 'true') { ?>
                                        CKEDITOR.config.contentsLangDirection = 'rtl';
                                    <?php } ?>
                                </script>   
                            </div>
                        </div>
                    </div>

                </div>
  
            </div> <!-- section content end -->  
            </form>
        </div>
            
    </div><!-- tab end -->

</div><!--end content area -->

<?php include 'includes/footer.php'; ?>