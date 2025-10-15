<?php include 'includes/header.php'; ?>

<?php

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        $sql = "SELECT * FROM tbl_post WHERE post_id = '$id'";
        $result = mysqli_query($connect, $sql);
        $image = mysqli_fetch_assoc($result);

        if ($image['post_image'] != "") {
            unlink('upload/post/'.$image['post_image']);
        }

        Delete('tbl_post','post_id = '.$id.'');

        redirect("post.php", "Post deleted successfully...");
    }

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
            $sql_query = "SELECT * FROM tbl_post ORDER BY post_id DESC";
        } else {
            $sql_query = "SELECT * FROM tbl_post WHERE post_title LIKE ? ORDER BY post_id DESC";
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
                $data['post_id'],
                $data['post_title'],
                $data['post_image'],
                $data['post_description'],
                $data['post_date']
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
            $from   = ($page * $offset) - $offset;
        } else {
            //if nothing was given in page request, lets load the first page
            $from = 0;  
        }   
        
        if (empty($keyword)) {
            $sql_query = "SELECT * FROM tbl_post ORDER BY post_id DESC LIMIT ?, ?";
        } else {
            $sql_query = "SELECT * FROM tbl_post WHERE post_title LIKE ? ORDER BY post_id DESC LIMIT ?, ?";
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
                $data['post_id'],
                $data['post_title'],
                $data['post_image'],
                $data['post_description'],
                $data['post_date']
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
		  <li class="active">Posts</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">POSTS</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
                            <div class="form-group pmd-textfield">
                                <table>
                                    <tr>
                                        <td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
                                        <td width="5%"></td>
                                        <td width="1%"><a href="post-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
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
									<th>Title</th>
									<th>Image</th>
                                    <th>Description</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

						</table>
						<p align="center">Whoops, No Data Found!</p>

					</div>
				</div>
			</div> <!-- section content end -->  
			<?php doPages($offset, 'post.php', '', $total_records, $keyword); ?>
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
		  <li class="active">Notification</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">NOTIFICATION</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
                            <div class="form-group pmd-textfield">
                                <table>
                                    <tr>
                                        <td><input type="text" name="keyword" class="form-control" placeholder="Search..."></td>
                                        <td width="5%"></td>
                                        <td width="1%"><a href="post-add.php" class="btn pmd-ripple-effect btn-material btn-block">Add New</a></td>
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
									<th>Title</th>
                                    <th>Image</th>
									<th width="15%">Action</th>
								</tr>
							</thead>

							<?php 
								while ($stmt_paging->fetch()) { ?>

							<tbody>
								<tr>
									<td><?php echo $data['post_title'];?></td>
									<td><img style="object-fit:cover;" src="upload/post/<?php echo $data['post_image']; ?>" width="72px" height="48px"/></td>
                                    
									<td>

									    <a href="post-edit.php?id=<?php echo $data['post_id'];?>">
									        <i class="material-icons">mode_edit</i>
									    </a>
									                        
									    <a href="post.php?id=<?php echo $data['post_id'];?>" onclick="return confirm('Are you sure want to delete this post?')" >
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
			<?php doPages($offset, 'post.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } ?>

<?php include 'includes/footer.php'; ?>