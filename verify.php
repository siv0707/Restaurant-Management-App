<?php include 'session.php'; ?>
<?php include 'includes/strings.php'; ?>
<?php include 'includes/config.php'; ?>
<?php include 'includes/functions.php'; ?>
<?php include 'includes/constant.php'; ?>

<?php
error_reporting(0);
$licenseResult = $connect->query("SELECT * FROM tbl_license ORDER BY id DESC LIMIT 1");
$licenseRow = $licenseResult->fetch_assoc();
$itemId = $licenseRow["item_id"];
if ($itemId == $var_item_id) {
    header("location:dashboard.php");
    exit();
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

</head>

<body class="body-custom">

<?php

    $error = false;
	error_reporting(0);
	// or error_reporting(E_ALL & ~E_NOTICE); to show errors but not notices
	ini_set("display_errors", 0); 

    if (isset($_POST['submit'])) {

        $data = array(
            'purchase_code' => $_POST['edt_purchase_code'],
            'item_id'       => $_POST['edt_item_id'],
            'item_name'     => $_POST['edt_item_name'],
            'buyer'         => $_POST['edt_buyer'],
            'license_type'  => $_POST['edt_license'],
            'purchase_date' => $_POST['edt_purchase_date']
            );

        $qry = Insert('tbl_license', $data);

        $succes =<<<EOF
            <script>
                alert('Thank you..');
                window.location = 'dashboard.php';
            </script>
EOF;
        echo $succes;

    }

    if (isset($_POST['verify'])) {

        $product_code = $_POST['item_purchase_code'];

        $url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
        $curl = curl_init($url);

        $header = array();
        $header[] = 'Authorization: Bearer '.$var_personal_token;
        $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
        $header[] = 'timeout: 20';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

        $envatoRes = curl_exec($curl);
        curl_close($curl);
        $envatoRes = json_decode($envatoRes);

        if (isset($envatoRes->item->name)) {
            $data = array(
                'item_id' => $envatoRes->item->id,
                'item_name' => $envatoRes->item->name,
                'buyer' => $envatoRes->buyer,
                'license' => $envatoRes->license,
                'purchase_date' => $envatoRes->sold_at,
                'purchase_count' => $envatoRes->purchase_count
            );

            if ($data['item_id'] != $var_item_id) {
                  $result['msg'] = '<div class="alert alert-danger">Whoops! The purchase code provided is for a different item!</div>';
            } else {
                $result['msg'] = '<div class="alert alert-success">License Found!</div>';
                $result['start'] = '<br><table class="table table-hover">';
                $result['purchase_code'] = '<tr><td>Purchase Code</td><td>:</td><td>'.$product_code.'</td>';
                $result['item_id'] = '<tr><td>Item ID</td><td>:</td><td>'.$data['item_id'].'</td>';
                $result['item_name'] = '<tr><td>Item Name</td><td>:</td><td>'.$data['item_name'].'</td>';
                $result['buyer'] = '<tr><td>Buyer</td><td>:</td><td>'.$data['buyer'].'</td>';
                $result['license'] = '<tr><td>License</td><td>:</td><td>'.$data['license'].'</td>';
                $result['purchase_date'] = '<tr><td>Purchase Date</td><td>:</td><td>'.$data['purchase_date'].'</td>';
                $result['purchase_count'] = '<tr><td>Purchase Count</td><td>:</td><td>'.$data['purchase_count'].'</td>';
                $result['end'] = '</table>';

                $result['edt_purchase_code'] = $product_code;
                $result['edt_item_id'] = $data['item_id'];
                $result['edt_item_name'] = $data['item_name'];
                $result['edt_buyer'] = $data['buyer'];
                $result['edt_license'] = $data['license'];
                $result['edt_purchase_date'] = $data['purchase_date'];
                $result['show_button'] = '<button type="submit" name="submit" class="btn pmd-ripple-effect btn-material btn-block">SUBMIT</button>';
            }
        } else { 
            $result['msg'] = '<div class="alert alert-danger">Whoops! Invalid purchase code!</div>';
        }

    }      

?>


<div class="verifycard">
    <div class="pmd-card card-default pmd-z-depth dashboard">
        <div class="login-card">
            <form method="POST">
                <br>
                <div class="pmd-card-title card-header-border text-center">
                    <div class="lead"><img src="assets/images/ic_envato.png" width="24" height="24"> Please Verify your Purchase Code to Continue Using Admin Panel.</div>
                </div>
                
                <div class="pmd-card-body">
                    <?php echo isset($result['msg']) ? $result['msg'] : '';?>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="item_purchase_code" class="control-label pmd-input-group-label">Your Item Purchase Code</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">vpn_key</i></div>
                            <input type="text" name="item_purchase_code" class="form-control" id="item_purchase_code" required>
                        </div>
                    </div>
                    
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <div class="form-group clearfix">
                    </div>
                    <button type="submit" name="verify" class="btn pmd-ripple-effect btn-material btn-block">Verify</button>
                </div>
                
            </form>
			
			<div class="pmd-card-body">
			<?php echo isset($result['start']) ? $result['start'] : ''; ?>
                                    <?php echo isset($result['purchase_code']) ? $result['purchase_code'] : ''; ?>
                                    <?php echo isset($result['item_id']) ? $result['item_id'] : ''; ?>
                                    <?php echo isset($result['item_name']) ? $result['item_name'] : ''; ?>
                                    <?php echo isset($result['buyer']) ? $result['buyer'] : ''; ?>
                                    <?php echo isset($result['license']) ? $result['license'] : ''; ?>
                                    <?php echo isset($result['purchase_date']) ? $result['purchase_date'] : ''; ?>
                                    <?php echo isset($result['end']) ? $result['end'] : ''; ?>

                                    <form method="post" id="form_validation">
                                        <input type="hidden" name="edt_purchase_code" value="<?php echo isset($result['edt_purchase_code']) ? $result['edt_purchase_code'] : ''; ?>">
                                        <input type="hidden" name="edt_item_id" value="<?php echo isset($result['edt_item_id']) ? $result['edt_item_id'] : ''; ?>">
                                        <input type="hidden" name="edt_item_name" value="<?php echo isset($result['edt_item_name']) ? $result['edt_item_name'] : ''; ?>">
                                        <input type="hidden" name="edt_buyer" value="<?php echo isset($result['edt_buyer']) ? $result['edt_buyer'] : ''; ?>">
                                        <input type="hidden" name="edt_license" value="<?php echo isset($result['edt_license']) ? $result['edt_license'] : ''; ?>">
                                        <input type="hidden" name="edt_purchase_date" value="<?php echo isset($result['edt_purchase_date']) ? $result['edt_purchase_date'] : ''; ?>">

                                        <?php echo isset($result['show_button']) ? $result['show_button'] : ''; ?>
                                    </form>
			
			<br><br>
                    <center><h3 class="pmd-card-subtitle-text">
                        <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">Where Is My Purchase Code?</a><br>
                        <a href="https://codecanyon.net/item/your-restaurant-app/18273140" target="_blank">Don't Have Purchase Code? I Want to Purchase it first.</a>
                    </h3></center>
				</div>
        </div>
        
    </div>
</div>

<!-- Scripts Starts -->
<script src="assets/js/jquery-1.12.2.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/propeller.min.js"></script>

</body>
</html>