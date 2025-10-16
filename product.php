<?php include 'includes/header.php'; ?>

<?php

	$query = "SELECT currency_code FROM tbl_settings, tbl_currency WHERE tbl_settings.currency_id = tbl_currency.currency_id";
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_assoc($result);

?>

	<?php
		
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			$keyword = sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if (empty($keyword)) {
			$sql_query = "SELECT p.product_id, p.product_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for, p.category_id, c.category_name FROM tbl_product p, tbl_category c WHERE p.category_id = c.category_id ORDER BY p.product_id DESC";
		} else {
			$sql_query = "SELECT p.product_id, p.product_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for, p.category_id, c.category_name FROM tbl_product p, tbl_category c WHERE p.category_id = c.category_id AND p.product_name LIKE ? ORDER BY p.product_id DESC";
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
					$data['product_id'],
					$data['product_name'],
					$data['product_price'],
					$data['product_status'],
					$data['product_image'],
					$data['product_description'],
					$data['serve_for'],
					$data['category_id'],
					$data['category_name']
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
			$sql_query = "SELECT p.product_id, p.product_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for, p.category_id, c.category_name FROM tbl_product p, tbl_category c WHERE p.category_id = c.category_id ORDER BY p.product_id DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT p.product_id, p.product_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for, p.category_id, c.category_name FROM tbl_product p ,tbl_category c WHERE p.category_id = c.category_id AND p.product_name LIKE ? ORDER BY p.product_id DESC LIMIT ?, ?";
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
				$data['product_id'],
				$data['product_name'],
				$data['product_price'],
				$data['product_status'],
				$data['product_image'],
				$data['product_description'],
				$data['serve_for'],
				$data['category_id'],
				$data['category_name']
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
		  <li class="active">Menu</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">MENU</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<table>
									<tr>
										<td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
										<td width="5%"></td>
										<td width="1%"><a href="product-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
									</tr>
								</table>
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
									<th>Product Name</th>
									<th>Image</th>
									<th>Category</th>
									<th>Price</th>
									<th>Status</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

						</table>
						<p align="center">Whoops, No Data Found!</p>

					</div>
				</div>
			</div>
			<?php doPages($offset, 'product.php', '', $total_records, $keyword); ?>
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
		  <li class="active">Menu</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">MENU</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<table>
									<tr>
										<td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
										<td width="5%"></td>
										<td width="1%"><a href="product-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
									</tr>
								</table>
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
									<th>Product Name</th>
									<th>Image</th>
									<th>Category</th>
									<th>Price</th>
									<th>Status</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

							<?php 
								while ($stmt_paging->fetch()) { ?>

							<tbody>
								<tr>
									<td><?php echo $data['product_name'];?></td>
									<td><img style="object-fit:cover;" src="upload/product/<?php echo $data['product_image']; ?>" width="64px" height="64px"/></td>
									<td><?php echo $data['category_name'];?></td>
									<td><?php echo $data['product_price'];?> <?php echo $row['currency_code']; ?></td>
									<td>
										<?php if ($data['product_status'] == '1') { ?>
										<span class="badge badge-success">AVAILABLE</span>
										<?php } else { ?>
										<span class="badge badge-error">SOLD OUT</span>
										<?php } ?>
									</td>
									<td>
                                        <a href="product-send.php?id=<?php echo $data['product_id'];?>">
                                            <i class="material-icons">notifications_active</i>
                                        </a>

									    <a href="product-edit.php?id=<?php echo $data['product_id'];?>">
									        <i class="material-icons">mode_edit</i>
									    </a>
									                        
									    <a href="product-delete.php?id=<?php echo $data['product_id'];?>" onclick="return confirm('Are you sure want to delete this product?')" >
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
			<?php doPages($offset, 'product.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } ?>

<?php include 'includes/footer.php'; ?>