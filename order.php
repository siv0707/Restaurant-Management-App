<?php include 'includes/header.php'; ?>

<?php 

    if (isset($_GET['id'])) {
        Delete('tbl_order','id='.$_GET['id'].'');
        redirect("order.php", "Order deleted successfully...");
    }

?>
	<?php
		
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			$keyword =sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if (empty($keyword)) {
			$sql_query = "SELECT * FROM tbl_order ORDER BY id DESC";
		} else {
			$sql_query = "SELECT * FROM tbl_order WHERE name LIKE ? ORDER BY id DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if (!empty($keyword)) {
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result( 
					$data['id'],
					$data['code'],
					$data['name'],
					$data['email'],
					$data['phone'],
					$data['address'],
					$data['shipping'],
					$data['date_time'],
					$data['order_list'],
					$data['order_total'],
					$data['comment'],
					$data['status'],
					$data['player_id']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page) {
			$from 	= ($page * $offset) - $offset;
		} else {
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if (empty($keyword)) {
			$sql_query = "SELECT * FROM tbl_order ORDER BY id DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT * FROM tbl_order WHERE name LIKE ? ORDER BY id DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if ($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if (empty($keyword)) {
				$stmt_paging ->bind_param('ss', $from, $offset);
			} else {
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result(
				$data['id'],
				$data['code'],
				$data['name'],
				$data['email'],
				$data['phone'],
				$data['address'],
				$data['shipping'],
				$data['date_time'],
				$data['order_list'],
				$data['order_total'],
				$data['comment'],
				$data['status'],
				$data['player_id']
			);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if ($total_records_paging == 0) {
	
	?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Order</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">MANAGE ORDER</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<input type="text" name="keyword" class="form-control" placeholder="Search...">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<thead>
								<tr>
									<th>Name</th>
									<th>Code</th>
									<th>Total</th>
									<th>Date</th>
									<th>Status</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

						</table>
						<br>
						<p align="center">whoops, no order yet!</p>
						<br>
					</div>
				</div>
			</div> <!-- section content end -->  
			<?php doPages($offset, 'order.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } else { $row_number = $from + 1; ?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Order</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">ORDER</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<input type="text" name="keyword" class="form-control" placeholder="Search...">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<thead>
								<tr>
									<th>Name</th>
									<th>Code</th>
									<th>Total</th>
									<th>Date</th>
									<th>Status</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

							<?php 
								while ($stmt_paging->fetch()) { ?>

							<tbody>
								<tr>
									<td><?php echo $data['name'];?></td>
									<td><?php echo $data['code'];?></td>
									<td><?php echo $data['order_total'];?></td>
									<td><?php echo $data['date_time'];?></td>
									<td>
										<?php if ($data['status'] == '1') { ?>
										<span class="badge badge-success">PROCESSED</span>
										<?php } else if ($data['status'] == '2') { ?>
										<span class="badge">&nbsp;CANCELED&nbsp;</span>
										<?php } else { ?>
										<span class="badge badge-error">&nbsp;&nbsp;&nbsp;PENDING&nbsp;&nbsp;&nbsp;</span>
										<?php } ?>
									</td>
									<td>
									    <a href="order-detail.php?id=<?php echo $data['id'];?>">
									        <i class="material-icons">open_in_new</i>
									    </a>
									                        
									    <a href="order.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to permanently delete this order?')" >
									                <i class="material-icons">delete</i>
									    </a>
									</td>									
								</tr>
							</tbody>

							<?php } ?>

						</table>

					</div>
				</div>
			</div> <!-- section content end -->  
			<?php doPages($offset, 'order.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } ?>

<?php include 'includes/footer.php'; ?>