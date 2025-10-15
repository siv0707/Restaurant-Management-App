<?php include 'includes/header.php'; ?>

<?php
    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }

    $result = $connect->query("SELECT * FROM tbl_category WHERE category_id = '$ID'");
    $data = $result->fetch_assoc();


    if (isset($_POST['btnEdit'])) {
        $category_name = $_POST['category_name'];

        // get image info
        $menu_image = $_FILES['category_image']['name'];
        $image_error = $_FILES['category_image']['error'];
        $image_type = $_FILES['category_image']['type'];

        // create array variable to handle error
        $error = array();

        if (empty($category_name)) {
            $error['category_name'] = " <span class='label label-danger'>Must Insert!</span>";
        }

        // common image file extensions
        $allowedExts = array("gif", "jpeg", "jpg", "png");

        // get image file extension
        error_reporting(E_ERROR | E_PARSE);
        $extension = end(explode(".", $_FILES["category_image"]["name"]));

        if (!empty($menu_image)) {
            if (!(($image_type == "image/gif") ||
                    ($image_type == "image/jpeg") ||
                    ($image_type == "image/jpg") ||
                    ($image_type == "image/x-png") ||
                    ($image_type == "image/png") ||
                    ($image_type == "image/pjpeg")) &&
                !(in_array($extension, $allowedExts))
            ) {

                $error['category_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
            }
        }

        if (!empty($category_name) && empty($error['category_image'])) {

            if (!empty($menu_image)) {

                // create random image file name
                $string = '0123456789';
                $file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
                $category_image = get_random_string($string, 4) . "-" . date("Y-m-d") . "." . $extension;

                // delete previous image
                $delete = unlink('upload/category/' . "$previous_category_image");

                // upload new image
                $upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/category/' . $category_image);

                $sql_query = "UPDATE tbl_category
                                SET category_name = ?, category_image = ?
                                WHERE category_id = ?";

                $upload_image = $category_image;
                $stmt = $connect->stmt_init();
                if ($stmt->prepare($sql_query)) {
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('sss',
                        $category_name,
                        $upload_image,
                        $ID);
                    // Execute query
                    $stmt->execute();
                    // store result
                    $update_result = $stmt->store_result();
                    $stmt->close();
                }
            } else {

                $sql_query = "UPDATE tbl_category
                                SET category_name = ?
                                WHERE category_id = ?";

                $stmt = $connect->stmt_init();
                if ($stmt->prepare($sql_query)) {
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('ss',
                        $category_name,
                        $ID);
                    // Execute query
                    $stmt->execute();
                    // store result
                    $update_result = $stmt->store_result();
                    $stmt->close();
                }
            }

            // check update result
            if ($update_result) {
                redirect("category-edit.php?id=$ID", "Changes saved...");
            } else {
                $error['update_category'] = "<br><div class='alert alert-danger'>Update Failed</div>";
            }

        }
    }
    

?>

<div id="content" class="pmd-content content-area dashboard">
	<div class="container-fluid full-width-container">
		<h1></h1>
			
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li><a href="category.php">Category</a></li>
		  <li class="active">Edit</li>
		</ol>
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
							<div class="lead">EDIT CATEGORY</div>
						</div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="btnEdit">Update</button></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="category_name" class="control-label">
									Category Name
								</label>
								<input type="text" name="category_name" id="category_name" class="form-control" value="<?php echo $data['category_name']; ?>" required>
							</div>
						</div>
					</div>
					<div class="group-fields clearfix row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label style="margin-bottom: 5px;" class="control-label">
									Category Image
								</label>
								<input type="file" name="category_image" id="category_image" id="category_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/category/<?php echo $data['category_image']; ?>" data-show-remove="false" />
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