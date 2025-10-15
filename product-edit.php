<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

	if (isset($_GET['id'])) {
 		$qry 	= "SELECT * FROM tbl_product WHERE product_id ='".$_GET['id']."'";
		$result = mysqli_query($connect, $qry);
		$row 	= mysqli_fetch_assoc($result);
 	}

	if(isset($_POST['submit'])) {

		if ($_FILES['product_image']['name'] != '') {
			unlink('upload/product/'.$_POST['old_image']);
			$product_image = time().'_'.$_FILES['product_image']['name'];
			$pic2 = $_FILES['product_image']['tmp_name'];
   			$tpath2 = 'upload/product/'.$product_image;
			copy($pic2, $tpath2);
		} else {
			$product_image = $_POST['old_image'];
		}

		$product_id = $_POST['product_id'];
 
		$data = array(											 

			'product_name'  		=> $_POST['product_name'],
			'product_price'  		=> $_POST['product_price'],
			'product_status'  		=> $_POST['product_status'],
			'product_image' 		=> $product_image,
            'product_description'	=> $_POST['product_description'],
            'serve_for'				=> $_POST['serve_for'],
            'category_id'  			=> $_POST['category_id']

		);	

		$update = Update('tbl_product', $data, "WHERE product_id = '$product_id'");

		if ($update > 0) {
			redirect("product-edit.php?id=$product_id", "Changes saved...");
		}

	}

 	$sql_query = "SELECT * FROM tbl_category ORDER BY category_name ASC";
	$ringtone_qry_cat = mysqli_query($connect, $sql_query);

	$sql_currency = "SELECT currency_code FROM tbl_settings, tbl_currency WHERE tbl_settings.currency_id = tbl_currency.currency_id";
	$sql_result = mysqli_query($connect, $sql_currency);
	$row_currency = mysqli_fetch_assoc($sql_result);

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
		  <li class="active">Edit</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">EDIT MENU</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Update</button></p>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<?php include 'includes/alert.php'; ?>
						</div>
					</div>

					<div class="group-fields clearfix row">

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="product_name" class="control-label">Menu Name *</label>
								<input type="text" name="product_name" id="product_name" class="form-control" placeholder="Menu Name" value="<?php echo $row['product_name'];?>" required>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="product_price" class="control-label">Price ( <?php echo $row_currency["currency_code"]; ?> ) *</label>
								<input type="text" name="product_price" id="product_price" class="form-control" placeholder="50" value="<?php echo $row['product_price'];?>" required>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="serve_for" class="control-label">Serve for (People) *</label>
								<input type="number" name="serve_for" id="serve_for" class="form-control" placeholder="0" value="<?php echo $row['serve_for'];?>" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Category *</label>
								<select style="width: 100%;" class="select-with-search form-control pmd-select2" name="category_id" id="category_id">
									<?php															 
										while($r_c_row = mysqli_fetch_array($ringtone_qry_cat)) {
										$sel = '';
										if ($r_c_row['category_id'] == $row['category_id']) {
											$sel = "selected";	
										}	
									?>
									<option value="<?php echo $r_c_row['category_id'];?>" <?php echo $sel; ?>><?php echo $r_c_row['category_name'];?></option>
									<?php }?>
								</select>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Status *</label>
								<select style="width: 100%;" class="select-simple form-control pmd-select2" name="product_status">
									<option <?php if($row['product_status'] == '1') { echo 'selected';} ?> value="1">Available</option>
									<option <?php if($row['product_status'] == '0') {echo 'selected';} ?> value="0">Sold Out</option>
								</select>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="regular1" class="control-label">Image ( jpg / png ) *</label>
								<input type="file" name="product_image" id="product_image" id="product_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/product/<?php echo $row['product_image'];?>" data-show-remove="false" />
							</div>
						</div>						

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label class="control-label">Description *</label>
  								<textarea required class="form-control" name="product_description"><?php echo $row['product_description'];?></textarea>
  								<script>                             
									CKEDITOR.replace( 'product_description' );
									<?php if ($ENABLE_RTL_MODE == 'true') { ?>
										CKEDITOR.config.contentsLangDirection = 'rtl';
									<?php } ?>
								</script>	
							</div>
						</div>

						<input type="hidden" name="old_image" value="<?php echo $row['product_image'];?>">
						<input type="hidden" name="product_id" value="<?php echo $row['product_id'];?>">					

					</div>

				</div>

			</div> <!-- section content end -->  
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area

<?php include 'includes/footer.php'; ?>