<?php include 'includes/header.php'; ?>

	<?php
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			// check value of keyword variable
			$keyword = sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if (empty($keyword)) {
			$sql_query = "SELECT category_id, category_name, category_image FROM tbl_category ORDER BY category_id DESC";
		} else {
			$sql_query = "SELECT category_id, category_name, category_image FROM tbl_category WHERE category_name LIKE ? ORDER BY category_id DESC";
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
					$data['category_id'],
					$data['category_name'],
					$data['category_image']
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
			$sql_query = "SELECT category_id, category_name, category_image FROM tbl_category ORDER BY category_id DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT category_id, category_name, category_image FROM tbl_category WHERE category_name LIKE ? ORDER BY category_id DESC LIMIT ?, ?";
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
				$data['category_id'],
				$data['category_name'],
				$data['category_image']
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
		  <li class="active">Category</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">MANAGE CATEGORY</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<table>
									<tr>
										<td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
										<td width="5%"></td>
										<td width="1%"><a href="category-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
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
									<th>Category Name</th>
									<th>Category Image</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

						</table>
						<p align="center">Whoops, No Data Found!</p>

					</div>
				</div>
			</div> <!-- section content end -->  
			<?php doPages($offset, 'category.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } else { $row_number = $from + 1; ?>

<!--content area start-->
<div id="content" class="pmd-content inner-page content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Category</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">MANAGE CATEGORY</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<table>
									<tr>
										<td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
										<td width="5%"></td>
										<td width="1%"><a href="category-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
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
									<th>Category Name</th>
									<th>Category Image</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

							<?php 
								while ($stmt_paging->fetch()) { ?>

							<tbody>
								<tr>
									<td><?php echo $data['category_name'];?></td>
									<td><img style="object-fit:cover;" src="upload/category/<?php echo $data['category_image']; ?>" width="48px" height="48px"/></td>
									<td>
									    <a href="category-edit.php?id=<?php echo $data['category_id'];?>">
									        <i class="material-icons">mode_edit</i>
									    </a>
									                        
									    <a href="category-delete.php?id=<?php echo $data['category_id'];?>" onclick="return confirm('Are you sure want to delete this category?')" >
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
			<?php doPages($offset, 'category.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } ?>

<?php include 'includes/footer.php'; ?>