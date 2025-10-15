<?php
    ob_start(); 
    session_start();
?>

<?php

    error_reporting(0);
    include 'includes/config.php';
    include 'includes/strings.php';
	include 'includes/constant.php';
    
    $verify_qry    = "SELECT * FROM tbl_license ORDER BY id DESC LIMIT 1";
    $verify_result = mysqli_query($connect, $verify_qry);
    $verify_row   = mysqli_fetch_assoc($verify_result);
    $item_id    = $verify_row['item_id'];

    if(isset($_POST['btnLogin'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $currentTime = time() + 25200;
        $expired = 86400;

        $error = array();

        if(empty($username)) {
            $error['username'] = "*Username should be filled.";
        }

        if(empty($password)) {
            $error['password'] = "*Password should be filled.";
        }

        if(!empty($username) && !empty($password)) {
            $username = strtolower($username);
            $password = hash('sha256',$username.$password);
            $sql_query = "SELECT * FROM tbl_admin WHERE username = ? AND password = ?";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $stmt->store_result();
                $num = $stmt->num_rows;
                $stmt->close();
                if($num == 1) {
                    if ($item_id == $var_item_id) {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: dashboard.php");
                    } else {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: verify.php");
                    }

                } else {
                    $error['failed'] = "<center><div class='alert alert-warning'>Invalid Username or Password!</div></center>";
                }
            }

        }
    }
?>

<!doctype html>
<html lang="<?php if ($ENABLE_RTL_MODE == 'true') { echo 'ar'; } else { } ?>" dir="<?php if ($ENABLE_RTL_MODE == 'true') { echo 'rtl'; } else { } ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Login | Propeller - Admin Dashboard">
<meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">
<title><?php echo $app_name; ?></title>
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

<style type="text/css">
body {
     font-family: 'Poppins', sans-serif;
}
</style>

</head>

<body class="body-custom">

<div class="logincard2">
    <div class="pmd-card card-default pmd-z-depth dashboard">
        <div class="login-card">
            <form method="POST">  
                <div class="pmd-card-title card-header-border text-center">
                    <div class="loginlogo">
                        <img src="assets/images/ic-logo.png" width="100" height="100" alt="Logo">
                    </div>
                    <div class="lead"><?php echo $app_name; ?></div>
                </div>
                
                <div class="pmd-card-body">
                    <?php echo isset($error['failed']) ? $error['failed'] : '';?>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i></div>
                            <input type="text" name="username" class="form-control" id="exampleInputAmount" required>
                        </div>
                    </div>
                    
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i></div>
                            <input type="password" name="password" class="form-control" id="exampleInputAmount" required>
                        </div>
                    </div>
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <div class="form-group clearfix">
                    </div>
                    <button type="submit" name="btnLogin" class="btn pmd-ripple-effect btn-material btn-block">Login</button>
                    <br>
                    <br>
                    <span class="pmd-card-subtitle-text"><?php echo $app_copyright; ?></span>
            <h3 class="pmd-card-subtitle-text"><?php echo $app_version; ?></h3>
                    
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<!-- Scripts Starts -->
<script src="assets/js/jquery-1.12.2.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/propeller.min.js"></script>

</body>
</html>