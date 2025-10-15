<?php include 'session.php'; ?>
<?php include 'config.php'; ?>
<?php include 'strings.php'; ?>
<?php include 'functions.php'; ?>
<?php include 'constant.php'; ?>

<?php

	error_reporting(0);

    $verify_qry    = "SELECT * FROM tbl_license ORDER BY id DESC LIMIT 1";
    $verify_result = mysqli_query($connect, $verify_qry);
    $verify_row   = mysqli_fetch_assoc($verify_result);
    $item_id    = $verify_row['item_id'];

    if ($item_id != $var_item_id) {
        $error =<<<EOF
        <script>
        alert('Please Verify your Purchase Code to Continue Using Admin Panel');
        window.location = 'verify.php';
        </script>
EOF;
        echo $error;
    }

?>


<?php

    $username = $_SESSION['user'];
    $sql_query = "SELECT id, username, email FROM tbl_admin WHERE username = ?";

    $data = array();
            
    $stmt = $connect->stmt_init();
    if($stmt->prepare($sql_query)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result(
            $data['id'],
            $data['username'],
            $data['email']
            );
        $stmt->fetch();
        $stmt->close();
    }

	// pending order
	$sql_pending = "SELECT COUNT(*) as num FROM tbl_order WHERE status = '0' ";
	$total_pending = mysqli_query($connect, $sql_pending);
	$total_pending = mysqli_fetch_array($total_pending);
	$total_pending = $total_pending['num'];

	$sql_pending_result = "SELECT * FROM tbl_order WHERE status = '0' ORDER BY id DESC LIMIT 5";
	$result = mysqli_query($connect, $sql_pending_result);   
            
?>

<!doctype html>
<html lang="<?php if ($ENABLE_RTL_MODE == 'true') { echo 'ar'; } else { } ?>" dir="<?php if ($ENABLE_RTL_MODE == 'true') { echo 'rtl'; } else { } ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Propeller Admin Dashboard">
<meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">

<title><?php echo $app_name; ?></title>
<meta name="description" content="Admin is a material design and bootstrap based responsive dashboard template created mainly for admin and backend applications."/>

<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png">

<!-- Google icon -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<!-- Bootstrap & propeller css -->
<?php if ($ENABLE_RTL_MODE == 'true') { ?>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-rtl.css">
	<link rel="stylesheet" type="text/css" href="assets/css/propeller-rtl.css">
	<link rel="stylesheet" type="text/css" href="assets/themes/css/propeller-theme-rtl.css" />
	<link rel="stylesheet" type="text/css" href="assets/themes/css/propeller-admin-rtl.css">
<?php } else { ?>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/propeller.css">
	<link rel="stylesheet" type="text/css" href="assets/themes/css/propeller-theme.css" />
	<link rel="stylesheet" type="text/css" href="assets/themes/css/propeller-admin.css">
<?php } ?>

<!-- Dropify -->
<link rel="stylesheet" type="text/css" href="assets/css/dropify.css">

<!-- Select2 css-->
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/css/select2-bootstrap.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/css/pmd-select2.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/pagination/css/pagination.css" />

<style type="text/css">
body {
     font-family: 'Poppins', sans-serif;
}
.poppins {
     font-family: 'Poppins', sans-serif;
}
</style>

</head>

<body>
<!-- Header Starts -->
<!--Start Nav bar -->
<nav class="navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth">

	<div class="container-fluid">
		<div class="pmd-navbar-right-icon pull-right navigation">
			<!-- Notifications -->
            <div class="dropdown notification icons pmd-dropdown">
			
				<a href="javascript:void(0)" title="Notification" class="dropdown-toggle pmd-ripple-effect"  data-toggle="dropdown" role="button" aria-expanded="true">
					<div data-badge="<?php echo $total_pending; ?>" class="material-icons md-light pmd-sm pmd-badge  pmd-badge-overlap">assignment</div>
				</a>
			
				<div class="dropdown-menu dropdown-menu-right pmd-card pmd-card-default pmd-z-depth" role="menu">
					<!-- Card header -->
					<div class="pmd-card-title">
						<div class="media-body media-middle">
							<a href="#" class="pull-right"><?php echo $total_pending; ?> Pending Order</a>
							<h3 class="pmd-card-title-text">Pending Order</h3>
						</div>
					</div>
					
					<!-- Notifications list -->
					<ul class="list-group pmd-list-avatar pmd-card-list">

					<?php if ($total_pending == '0') { ?>
						<li class="list-group-item">
							<p class="notification-blank">
								<span class="dic dic-notifications-none"></span> 
								<span>You don't have pending order</span>
							</p>
						</li>

					<?php } else { ?>

					<?php while($row = mysqli_fetch_array($result)) { ?>						
						<li class="list-group-item unread">
							<a href="order-detail.php?id=<?php echo $row['id'];?>">
								<div class="media-body">
									<span class="list-group-item-heading">
										<span><?php echo $row['name'];?></span>
									</span>
									<span class="list-group-item-text"><?php echo $row['order_total'];?></span>
									<span class="list-group-item-text"><?php echo $row['date_time'];?></span>
								</div>
							</a>
						</li>
					<?php } ?>
						<li class="list-group-item unread">
							<a href="order.php">
								<div class="media-body">
									<div align="center">View All</div>
								</div>
							</a>
						</li>

					<?php } ?>
						
					</ul><!-- End notifications list -->

				</div>
				
				
            </div> <!-- End notifications -->
		</div>
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a href="javascript:void(0);" data-target="basicSidebar" data-placement="left" data-position="slidepush" is-open="true" is-open-width="1200" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle"><i class="material-icons md-light">menu</i></a>	
		  <a href="dashboard.php" class="navbar-brand">
		  	<?php echo $app_name; ?>
		  </a>
		</div>
	</div>

</nav><!--End Nav bar -->
<!-- Header Ends -->

<!-- Sidebar Starts -->
<div class="pmd-sidebar-overlay"></div>

<!-- Left sidebar -->
<aside id="basicSidebar" class="pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons" role="navigation">
	<ul class="nav pmd-sidebar-nav">
		<?php $page = $_SERVER['REQUEST_URI']; ?>
		<!-- User info -->
		<li class="dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg">
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" aria-expandedhref="javascript:void(0);">
				<div class="media-left">
					<img src="assets/themes/images/user-icon.png" alt="New User">
				</div>
				<div class="media-body media-middle">Hello, <?php echo $data['username'] ?></div>
				<div class="media-right media-middle"><i class="dic-more-vert dic"></i></div>
			</a>
			<ul class="dropdown-menu">
				<li><a href="admin-edit.php?id=<?php echo $data['id']; ?>"><div class="poppins">Profile</div></a></li>
				<li><a href="logout.php"><div class="poppins">Logout</div></a></a></li>
			</ul>
		</li><!-- End user info -->
		
		<li class="<?php if (strpos($page, 'dashboard') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="dashboard.php">	
				<i class="media-left media-middle material-icons">dashboard</i>
				<span class="media-body">Dashboard</span>
			</a> 
		</li>

		<li class="<?php if (strpos($page, 'order') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="order.php">	
				<i class="media-left media-middle material-icons">content_paste</i>
				<span class="media-body">Order List</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'slider') !== false) { echo 'sidebar-active'; } ?>">
			<a href="slider.php">	
				<i class="media-left media-middle material-icons">burst_mode</i>
				<span class="media-body sidebar-text-color">Slider</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'category') !== false) { echo 'sidebar-active'; } ?>">
			<a href="category.php">	
				<i class="media-left media-middle material-icons">dns</i>
				<span class="media-body sidebar-text-color">Category</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'product') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="product.php">	
				<i class="media-left media-middle material-icons">restaurant</i>
				<span class="media-body">Menu</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'post') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="post.php">	
				<i class="media-left media-middle material-icons">library_books</i>
				<span class="media-body">Post</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'notification') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="notification.php">	
				<i class="media-left media-middle material-icons">notifications</i>
				<span class="media-body">Notification</span>
			</a>
		</li>

		<li class="<?php if (strpos($page, 'help') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="help.php">	
				<i class="media-left media-middle material-icons">live_help</i>
				<span class="media-body">Help</span>
			</a>
		</li>		

		<li> 
			<a class="pmd-ripple-effect" href="settings.php">	
				<i class="media-left media-middle material-icons">settings</i>
				<span class="media-body">Settings</span>
			</a> 
		</li>

		<li class="<?php if (strpos($page, 'admin') !== false) { echo 'sidebar-active'; } ?>">
			<a class="pmd-ripple-effect" href="admin.php">	
				<i class="media-left media-middle material-icons">people</i>
				<span class="media-body">Admin</span>
			</a>
		</li>

		<li> 
			<a class="pmd-ripple-effect" href="license.php">	
				<i class="media-left media-middle material-icons">vpn_key</i>
				<span class="media-body">License</span>
			</a> 
		</li>

		<li> 
			<a class="pmd-ripple-effect" href="logout.php">	
				<i class="media-left media-middle material-icons">power_settings_new</i>
				<span class="media-body">Logout</span>
			</a> 
		</li>
		
	</ul>
</aside><!-- End Left sidebar -->
<!-- Sidebar Ends -->  