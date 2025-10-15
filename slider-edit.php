<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }

    $qry = "SELECT * FROM tbl_slider WHERE slider_id ='".$ID."'";
    $result = mysqli_query($connect, $qry);
    $data = mysqli_fetch_assoc($result);

    if(isset($_POST['update'])) {

        if ($_FILES['slider_image']['name'] != '') {
            unlink('upload/slider/'.$_POST['old_image']);
            $slider_image = time().'_'.$_FILES['slider_image']['name'];
            $pic2 = $_FILES['slider_image']['tmp_name'];
            $tpath2 = 'upload/slider/'.$slider_image;
            copy($pic2, $tpath2);
        } else {
            $slider_image = $_POST['old_image'];
        }
 
        $data = array(                                           
            'slider_title' => $_POST['slider_title'],
            'slider_image' => $slider_image,
            'slider_description' => $_POST['slider_description']
        );  

        $update = Update('tbl_slider', $data, "WHERE slider_id = '$ID'");
        if ($update > 0) {
            redirect("slider-edit.php?id=$ID", "Changes saved...");
        }

    }

?>

<div id="content" class="pmd-content content-area dashboard">

    <div class="container-fluid full-width-container">
        <h1></h1>

        <ol class="breadcrumb text-left">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="slider.php">Slider</a></li>
          <li class="active">Edit</li>
      </ol>

      <div class="section"> 

        <form id="validationForm" method="post" enctype="multipart/form-data">
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">

                    <div class="group-fields clearfix row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="lead">EDIT SLIDER</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="update">Update</button></p>
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
                                <input type="text" name="slider_title" id="slider_title" class="form-control" value="<?php echo $data['slider_title']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="regular1" class="control-label">Slider Image ( jpg / png) *</label>
                                <input type="file" name="slider_image" id="slider_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/slider/<?php echo $data['slider_image']; ?>" data-show-remove="false"/>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Slider Description *</label>
                                <textarea required class="form-control" name="slider_description"><?php echo $data['slider_description'];?></textarea>
                                <script>                             
                                    CKEDITOR.replace( 'slider_description' );
                                </script>   
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="old_image" value="<?php echo $data['slider_image'];?>">

                </div>
            </div>
        </form>
    </div>

</div>

</div>

<?php include 'includes/footer.php'; ?>