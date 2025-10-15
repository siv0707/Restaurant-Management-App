<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        Delete('tbl_shipping','shipping_id = '.$id.'');
        redirect("settings.php", "Service deleted successfully...");
    }

    if (isset($_POST['submit'])) {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '1'";
        $result = mysqli_query($connect, $sql_query);
        $row =  mysqli_fetch_assoc($result);

        $data = array(
            'currency_id' => $_POST['currency_id'],
            'tax' => $_POST['tax']
        );

        $update_setting = Update('tbl_settings', $data, "WHERE id = '1'");

        if ($update_setting > 0) {
        	redirect("settings.php", "Changes saved...");
        }
    }

    if (isset($_POST['submit_currency'])) {

        $data = array(
            'currency_code' => $_POST['currency_code'],
            'currency_name' => $_POST['currency_name']
        );      

        $qry = Insert('tbl_currency', $data);                                    
                      
        redirect("settings.php", "Currency added successfully...");

    }    

    if (isset($_POST['submit_shipping'])) {

        $data = array(
            'shipping_name' => $_POST['shipping_name']
        );      

        $qry = Insert('tbl_shipping', $data);                                    
                      
        redirect("settings.php", "Service added successfully...");

    }

    if(isset($_POST['submit_notification'])) {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '1'";
        $result = mysqli_query($connect, $sql_query);
        $row =  mysqli_fetch_assoc($result);

        $data = array(
            'onesignal_app_id' => $_POST['onesignal_app_id'],
            'onesignal_rest_api_key' => $_POST['onesignal_rest_api_key']
        );

        $update_setting = Update('tbl_settings', $data, "WHERE id = '1'");

        if ($update_setting > 0) {
            redirect("settings.php", "Changes saved...");
        }
    }

    if(isset($_POST['submit_settings'])) {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '1'";
        $result = mysqli_query($connect, $sql_query);
        $row =  mysqli_fetch_assoc($result);

        $data = array(
            'map_location' => $_POST['map_location'],
            'privacy_policy' => $_POST['privacy_policy'],
            'terms_conditions' => $_POST['terms_conditions']
        );

        $update_setting = Update('tbl_settings', $data, "WHERE id = '1'");

        if ($update_setting > 0) {
        	redirect("settings.php", "Changes saved...");
        }
    }


    $sql_query_config = "SELECT * FROM tbl_settings where id = '1'";
    $config_result = mysqli_query($connect, $sql_query_config);
    $config_row = mysqli_fetch_assoc($config_result);

    $sql_query_currency = "SELECT * FROM tbl_currency ORDER BY currency_code ASC";
    $currency_result = mysqli_query($connect, $sql_query_currency);

    $sql_query_shipping = "SELECT * FROM tbl_shipping ORDER BY shipping_id ASC";
    $shipping_result = mysqli_query($connect, $sql_query_shipping);

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
		<h1></h1>

		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
			<li><a href="dashboard.php">Dashboard</a></li>
			<li class="active">Settings</li>
		</ol>
		<!--breadcrum end-->

		<div class="section"> 
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">
					<form id="validationForm" method="post" enctype="multipart/form-data">

						<div class="group-fields clearfix row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="lead">CURRENCY & TAX</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                            <?php include 'includes/alert.php'; ?>
	                        </div>
						</div>

						<div class="group-fields clearfix row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

								<table>
									<tr>
										<td>
											<div class="form-group pmd-textfield">
												<label>Currency</label>
												<select style="width: 100%;" class="select-with-search form-control pmd-select2" name="currency_id" id="currency_id">
													<?php    
													while($currency_row = mysqli_fetch_array($currency_result)) {
														$select = '';
														if ($currency_row['currency_id'] == $config_row['currency_id']) {
															$select = "selected";  
														}   
														?>
														<option value="<?php echo $currency_row['currency_id'];?>" <?php echo $select; ?>><?php echo $currency_row['currency_code'].' - '.$currency_row['currency_name']; ?></option>
														<?php }?>
													</select>
												</div>
											</td>
											<td width="1%">
												<button data-target="#currency-dialog" data-toggle="modal" class="btn pmd-btn-flat pmd-ripple-effect btn-primary" type="button">ADD NEW</button>
											</td>
										</tr>
									</table>
								</div>

							</div>

							<div class="group-fields clearfix row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group pmd-textfield">
										<label for="tax" class="control-label">
											Tax ( 0 - 100% )
										</label>
										<input type="number" name="tax" id="tax" class="form-control" value="<?php echo $config_row['tax']; ?>" required>
									</div>
								</div>
							</div>

							<div class="group-fields clearfix row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p align="right">
										<button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Update</button>
									</p>
								</div>
							</div>                  

						</form>

						<br>
						<div class="group-fields clearfix row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="lead">SERVICE</div>
							</div>
						</div>

						<div class="group-fields clearfix row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group pmd-textfield">
									<?php
									while ($data = mysqli_fetch_array($shipping_result)) {
										?>

										<div class="pmd-chip pmd-chip-no-icon"><?php echo $data['shipping_name'];?>
											<a class="pmd-chip-action" href="settings.php?id=<?php echo $data['shipping_id'];?>" onclick="return confirm('Are you sure want to delete this service?')">
												<i class="material-icons">close</i>
											</a>
										</div>
										<?php } ?>

										<button data-target="#shipping-dialog" data-toggle="modal" class="btn pmd-btn-flat pmd-ripple-effect btn-primary" type="button">ADD NEW</button>

										<div tabindex="-1" class="modal fade" id="shipping-dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header pmd-modal-bordered">
														<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
														<h2 class="pmd-card-title-text">Add Service</h2>
													</div>
													<form id="validationForm" method="post">            
														<div class="modal-body">
															<div class="form-group pmd-textfield pmd-textfield-floating-label">
																<label class="control-label">Service Name</label>
																<input type="text" name="shipping_name" id="shipping_name" class="form-control" required>
															</div>
														</div>
														<div class="pmd-modal-action">
															<div align="right">
																<button data-dismiss="modal" class="btn pmd-ripple-effect btn-default" type="button">Cancel</button>
																<button class="btn pmd-ripple-effect btn-material" type="submit" name="submit_shipping">Submit</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>

							<br>
							<form id="validationForm" method="post" enctype="multipart/form-data">

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="lead">NOTIFICATION</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group pmd-textfield">
											<label for="app_fcm_key" class="control-label">
												OneSignal APP ID
											</label>
											<input type="text" name="onesignal_app_id" id="onesignal_app_id" class="form-control" value="<?php echo $config_row['onesignal_app_id']; ?>" required>
										</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group pmd-textfield">
											<label for="app_fcm_key" class="control-label">
												OneSignal Rest API Key
											</label>
											<input type="text" name="onesignal_rest_api_key" id="onesignal_rest_api_key" class="form-control" value="<?php echo $config_row['onesignal_rest_api_key']; ?>" required>
										</div>
									</div>
								</div>                  

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<p align="right">
											<button type="submit" class="btn pmd-ripple-effect btn-material" name="submit_notification">Update</button>
										</p>
									</div>
								</div>                  
							</form>

							<br>
							<form id="validationForm" method="post" enctype="multipart/form-data">

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="lead">OTHER</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group pmd-textfield">
											<label for="map_location" class="control-label">
												Map Location
											</label>
											<input type="text" name="map_location" id="map_location" class="form-control" value="<?php echo $config_row['map_location']; ?>" required>
										</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group pmd-textfield">
											<label class="control-label">Privacy Policy</label>
											<textarea required class="form-control" name="privacy_policy"><?php echo $config_row['privacy_policy'];?></textarea>
											<script>                             
												CKEDITOR.replace('privacy_policy');
												<?php if ($ENABLE_RTL_MODE == 'true') { ?>
													CKEDITOR.config.contentsLangDirection = 'rtl';
												<?php } ?>
											</script>   
										</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group pmd-textfield">
											<label class="control-label">Terms & Conditions</label>
											<textarea required class="form-control" name="terms_conditions"><?php echo $config_row['terms_conditions'];?></textarea>
											<script>                             
												CKEDITOR.replace('terms_conditions');
												<?php if ($ENABLE_RTL_MODE == 'true') { ?>
													CKEDITOR.config.contentsLangDirection = 'rtl';
												<?php } ?>
											</script>   
										</div>
									</div>
								</div>

								<div class="group-fields clearfix row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<p align="right">
											<button type="submit" class="btn pmd-ripple-effect btn-material" name="submit_settings">Update</button>
										</p>
									</div>
								</div>                  
							</form>

						</div>
					</div>


					<div tabindex="-1" class="modal fade" id="currency-dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header pmd-modal-bordered">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h2 class="pmd-card-title-text">Add Currency</h2>
								</div>
								<form id="validationForm" method="post">            
									<div class="modal-body">
										<div class="form-group pmd-textfield">
											<label class="control-label">Currency Code</label>
											<input type="text" name="currency_code" id="currency_code" class="form-control" placeholder="USD" required>
										</div>

										<div class="form-group pmd-textfield">
											<label class="control-label">Currency Name</label>
											<input type="text" name="currency_name" id="currency_name" class="form-control" placeholder="US Dollar" required>
										</div>
									</div>
									<div class="pmd-modal-action">
										<div align="right">
											<button data-dismiss="modal" class="btn pmd-ripple-effect btn-default" type="button">Cancel</button>
											<button class="btn pmd-ripple-effect btn-material" type="submit" name="submit_currency">Submit</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>

			</div>

		</div>


<?php include 'includes/footer.php'; ?>