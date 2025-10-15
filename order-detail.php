<?php include 'includes/header.php'; ?>
<style type="text/css">
	.my-pre {
		font-size: 16px;
		font-family: 'poppins', sans-serif;
		border: 0; 
		background-color: transparent;
		padding: 0px;
	}
</style>
<?php 
	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}

	$result = $connect->query("SELECT * FROM tbl_order WHERE id = '$ID'");
    $data = $result->fetch_assoc();

	$order_list = explode(',', $data['order_list']);
			
?>

<?php

  $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $oneSignalAppId = $settings_row['onesignal_app_id']; 
  $oneSignalRestApiKey = $settings_row['onesignal_rest_api_key'];

?>

<?php

	$uniqueId = rand(1000, 9999);
	$playerId = $data['player_id'];
	$name  = $data['name'];
	$code  = $data['code'];

	if (isset($_POST['submit_order'])) {

	    $fields = [
	        "app_id" => $oneSignalAppId,
	        'include_player_ids' => array($playerId),
	        "data" => [
	            "foo" => "bar",
	            "link" => "",
	            "post_id" => "1010101010",
	            "unique_id" => $uniqueId,
	        ],
	        "headings" => ["en" => "Hi $name,"],
	        "contents" => ["en" => "Your order id : $code has been processed"],
	        "big_picture" => $bigImage,
	        "url" => "",
	    ];

	    $fields = json_encode($fields);
	    print "\nJSON sent:\n";
	    print $fields;

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        "Content-Type: application/json; charset=utf-8",
	        "Authorization: Basic " . $oneSignalRestApiKey,
	    ]);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	    $response = curl_exec($ch);
	    curl_close($ch);	
 
		$data = array('status'  => 1);
		$update = Update('tbl_order', $data, "WHERE id = '".$_GET['id']."'");
		if ($update > 0) {
			redirect("order-detail.php?id=$ID", "The order has been processed...");
		}

	}


	if (isset($_POST['cancel_order'])) {

        $fields = [
	        "app_id" => $oneSignalAppId,
	        'include_player_ids' => array($playerId),
	        "data" => [
	            "foo" => "bar",
	            "link" => "",
	            "post_id" => "1010101010",
	            "unique_id" => $uniqueId,
	        ],
	        "headings" => ["en" => "Hi $name,"],
	        "contents" => ["en" => "Your order id : $code has been canceled"],
	        "big_picture" => $bigImage,
	        "url" => "",
	    ];

	    $fields = json_encode($fields);
	    print "\nJSON sent:\n";
	    print $fields;

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        "Content-Type: application/json; charset=utf-8",
	        "Authorization: Basic " . $oneSignalRestApiKey,
	    ]);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	    $response = curl_exec($ch);
	    curl_close($ch);
 
		$data = array('status'  => 2);	
		$update = Update('tbl_order', $data, "WHERE id = '".$_GET['id']."'");
		if ($update > 0) {
		    redirect("order-detail.php?id=$ID", "The order has been canceled...");
		}

	}

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li><a href="order.php">Order</a></li>
		  <li class="active">Detail</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 
			
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="lead">ORDER DETAIL</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 right">
							<div align="right">
								
								<?php if ($data['status'] == '0') { ?> 
								<form id="validationForm" method="post">
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCEL" onclick="cancelClicked(event)"/>

									<input type="submit" name="submit_order" class="btn pmd-ripple-effect btn-material" value="PROCESS" onclick="processClicked(event)" />
								</form>
								<?php } else if ($data['status'] == '1') { ?> 
								<form id="validationForm" method="post">
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCEL" onclick="cancelClicked(event)"/>
								</form>
								<?php } else if ($data['status'] == '2') { ?>
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCELED" disabled/>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<tbody>

								<tr>
									<td>Name</td>
									<td>:</td>
									<td><?php echo $data['name']; ?></td>
								</tr>

								<tr>
									<td width="15%">Code</td>
									<td width="1%">:</td>
									<td><?php echo $data['code']; ?></td>
								</tr>

								<tr>
									<td>Status</td>
									<td>:</td>
									<td>
										<?php if ($data['status'] == '1') { ?>
										<span class="badge badge-success">PROCESSED</span>
										<?php } else if ($data['status'] == '2') { ?>
										<span class="badge">CANCELED</span>
										<?php } else { ?>
										<span class="badge badge-error">PENDING</span>
										<?php } ?>										
									</td>
								</tr>

								<tr>
									<td>Email</td>
									<td>:</td>
									<td><?php echo $data['email']; ?></td>
								</tr>

								<tr>
									<td>Phone</td>
									<td>:</td>
									<td><?php echo $data['phone']; ?></td>
								</tr>

								<tr>
									<td>Address</td>
									<td>:</td>
									<td><?php echo $data['address']; ?></td>
								</tr>

								<tr>
									<td>Shipping</td>
									<td>:</td>
									<td><?php echo $data['shipping']; ?></td>
								</tr>
								<tr>
									<td>Date</td>
									<td>:</td>
									<td><?php echo $data['date_time']; ?></td>
								</tr>
								
								<tr>
									<td>Order List</td>
									<td>:</td>
									<td><pre class="my-pre"><?php echo $data['order_list']; ?></pre></td>
								</tr>

								<?php if ($data['comment'] != '') { ?>
								<tr>
									<td>Comment</td>
									<td>:</td>
									<td><?php echo $data['comment']; ?></td>
								</tr>
								<?php } ?>

							</tbody>
						</table>

					</div>
				</div>
			</div> <!-- section content end -->
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<script>
	function processClicked(e) {
	    if(!confirm("The order status can't be changed after processed unless you cancel it."))e.preventDefault();
	}
</script>

<script>
	function cancelClicked(e) {
	    if(!confirm("Are you sure want to cancel order from <?php echo $data['name']; ?>"))e.preventDefault();
	}
</script>

<?php include 'includes/footer.php'; ?>