<?php include 'includes/header.php'; ?>

<?php

	$error = false;

	if (isset($_POST['submit'])) {

        $username = clean($_POST['username']);
		$full_name = clean($_POST['full_name']);
		$password = clean($_POST['password']);
		$repassword = clean($_POST['repassword']);
		$email = clean($_POST['email']);
        $role = clean($_POST['role']);

		if (strlen($username) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($password)) {
			$error[] = 'Password can not be empty!';
		}

        if (empty($full_name)) {
            $error[] = 'Full name can not be empty!';
        }

		if ($password != $repassword) {
			$error[] = 'Password does not match!';
		}

		$password = hash('sha256',$username.$password);

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!'; 
		}

		if (!$error) {

			$sql = "SELECT * FROM tbl_admin WHERE (username = '$username' OR email = '$email');";
            $result = mysqli_query($connect, $sql);
            if (mysqli_num_rows($result) > 0) {

            	$row = mysqli_fetch_assoc($result);

            	if ($username == $row['username']) {
                	$error[] = 'Username already exists!';
            	} 

            	if ($email == $row['email']) {
                	$error[] = 'Email already exists!';
            	}

	        } else {

				$sql = "INSERT INTO tbl_admin (username, password, email, full_name, user_role) VALUES (?, ?, ?, ?, ?)";

				$insert = $connect->prepare($sql);
				$insert->bind_param('sssss', $username, $password, $email, $full_name, $role);
				$insert->execute();

				redirect("admin.php", "Admin added successfully...");
			}
		}
	}

?>

<div id="content" class="pmd-content content-area dashboard">
	<div class="container-fluid full-width-container">
		<h1></h1>
			
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li><a href="admin.php">Admin</a></li>
		  <li class="active">Add</li>
		</ol>
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
							<div class="lead">ADD ADMIN</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Submit</button></p>
                        </div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<?php echo $error ? '<div class="alert alert-warning">'. implode('<br>', $error) . '</div>' : '';?>
                            <?php include 'includes/alert.php'; ?>
                        </div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="username" class="control-label">
									Username
								</label>
								<input type="text" name="username" id="username" class="form-control" required>
							</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="full_name" class="control-label">
									Full Name
								</label>
								<input type="text" name="full_name" id="full_name" class="form-control" required>
							</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="email" class="control-label">
									Email
								</label>
								<input type="text" name="email" id="email" class="form-control" required>
							</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="password" class="control-label">
									Password
								</label>
								<input type="password" name="password" id="password" class="form-control" required>
							</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="repassword" class="control-label">
									Re Password
								</label>
								<input type="password" name="repassword" id="repassword" class="form-control" required>
							</div>
						</div>
					</div>

					<input type="hidden" name="role" id="role" value="100" />

				</div>
			</div>
			</form>
		</div>
			
	</div>

</div>

<?php include 'includes/footer.php'; ?>