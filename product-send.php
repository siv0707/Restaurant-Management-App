<?php include 'includes/header.php'; ?>

<?php 
	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}
			
    $result = $connect->query("SELECT c.category_name, p.* FROM tbl_product p, tbl_category c WHERE p.category_id = c.category_id AND p.product_id = '$ID'");
    $data = $result->fetch_assoc();
			
?>

<?php
  $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $oneSignalAppId = $settings_row['onesignal_app_id']; 
  $oneSignalRestApiKey = $settings_row['onesignal_rest_api_key'];
 
  $redirect = "Location:product.php";

  if (isset($_POST['submit'])) {

        $title = $_POST["title"];
        $message = $_POST["message"];
        $postId = $_POST["post_id"];
        $link = $_POST["link"];

        $actualLink = (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://" . $_SERVER["SERVER_NAME"].dirname($_SERVER["REQUEST_URI"]);
        $bigImage = $actualLink . '/upload/product/'.$data['image'];

        $uniqueId = rand(1000, 9999);

        oneSignalPush(
            $uniqueId,
            $title,
            $message,
            $bigImage,
            $link,
            $postId,
            $oneSignalAppId,
            $oneSignalRestApiKey,
            $redirect
        );

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
          <li><a href="product.php">Menu</a></li>
          <li class="active">Send</li>
        </ol>
        <!--breadcrum end-->
    
        <div class="section"> 

            <form id="validationForm" method="post" enctype="multipart/form-data">
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">

                    <div class="group-fields clearfix row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="lead">SEND NOTIFICATION</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">SEND NOW</button></p>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="title" class="control-label">
                                    Title *
                                </label>
                                <input type="text" name="title" id="title" class="form-control" value="<?php echo $data['category_name']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="message" class="control-label">
                                    Message *
                                </label>
                                <input type="text" name="message" id="message" class="form-control" value="<?php echo $data['product_name']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Big Image ( jpg / png) *</label>
                                <input type="file" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/product/<?php echo $data['product_image']; ?>" data-show-remove="false" disabled/>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="link" id="link" value="0" />
                    <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['product_id']; ?>" />         

                </div>
            </div> <!-- section content end -->  
            </form>
        </div>
            
    </div><!-- tab end -->

</div><!--end content area -->

<?php include 'includes/footer.php'; ?>