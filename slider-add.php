<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

    if (isset($_POST['submit'])) {

        $slider_image = time().'_'.$_FILES['slider_image']['name'];
        $file = $_FILES['slider_image']['tmp_name'];
        $path = 'upload/slider/'.$slider_image;
        copy($file, $path);

        $data = array(
            'slider_title' => $_POST['slider_title'],
            'slider_image' => $slider_image,
            'slider_description' => $_POST['slider_description']
        );      

        $qry = Insert('tbl_slider', $data);                                    
        redirect("slider.php", "Slider added successfully...");
    }

?>

<div id="content" class="pmd-content content-area dashboard">

    <div class="container-fluid full-width-container">
        <h1></h1>

        <ol class="breadcrumb text-left">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="slider.php">Slider</a></li>
          <li class="active">Add</li>
      </ol>

      <div class="section"> 

        <form id="validationForm" method="post" enctype="multipart/form-data">
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">

                    <div class="group-fields clearfix row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="lead">ADD SLIDER</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Submit</button></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="slider_title" class="control-label">
                                    Slider Title *
                                </label>
                                <input type="text" name="slider_title" id="slider_title" placeholder="Slider Title" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="regular1" class="control-label">Slider Image ( jpg / png) *</label>
                                <input type="file" name="slider_image" id="slider_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" required/>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Slider Description *</label>
                                <textarea required class="form-control" name="slider_description"></textarea>
                                <script>                             
                                    CKEDITOR.replace('slider_description');
                                </script>   
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

</div>

</div>

<?php include 'includes/footer.php'; ?>