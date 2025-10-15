<?php

	include_once ('../includes/config.php');
 	$connect->set_charset('utf8');

    $sql_query    	= "SELECT * FROM tbl_admin ORDER BY id DESC LIMIT 1";
    $user_result	= mysqli_query($connect, $sql_query);
    $user_row	    = mysqli_fetch_assoc($user_result);
    $admin_email  	= $user_row['email'];	
	
	if (isset($_GET['category_id'])) {
		$query = "SELECT p.product_id, p.product_name, p.category_id, n.category_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for FROM tbl_category n, tbl_product p WHERE n.category_id = p.category_id AND n.category_id ='".$_GET['category_id']."' ORDER BY p.product_id DESC";			
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set[] = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));		
			
	} else if (isset($_GET['get_home'])) {

		$product_sql = "SELECT p.product_id, p.product_name, p.category_id, n.category_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for FROM tbl_category n, tbl_product p WHERE n.category_id = p.category_id ORDER BY p.product_id DESC";
		$product_result = mysqli_query($connect, $product_sql);

		$products = array();
	    $total_products = mysqli_num_rows($product_result);
	    if($total_products >= 1) {
	      	while ($product = mysqli_fetch_array($product_result, MYSQLI_ASSOC)) {
	        $products[] = $product;
	      }
	    }

	    $category_sql = "SELECT * FROM tbl_category ORDER BY category_id DESC";
		$category_result = mysqli_query($connect, $category_sql);

		$categories = array();
	    $total_category = mysqli_num_rows($category_result);
	    if($total_category >= 1) {
	      	while ($category = mysqli_fetch_array($category_result, MYSQLI_ASSOC)) {
	        $categories[] = $category;
	      }
	    }

	    $shipping_sql = "SELECT * FROM tbl_shipping ORDER BY shipping_id ASC";
		$shipping_result = mysqli_query($connect, $shipping_sql);

		$shippings = array();
	    $total_shippings = mysqli_num_rows($shipping_result);
	    if($total_shippings >= 1) {
	      	while ($shipping = mysqli_fetch_array($shipping_result, MYSQLI_ASSOC)) {
	        $shippings[] = $shipping;
	      }
	    }

	    $slider_sql = "SELECT * FROM tbl_slider ORDER BY slider_id DESC";
		$slider_result = mysqli_query($connect, $slider_sql);

		$sliders = array();
	    $total_sliders = mysqli_num_rows($slider_result);
	    if($total_sliders >= 1) {
	      	while ($slider = mysqli_fetch_array($slider_result, MYSQLI_ASSOC)) {
	        $sliders[] = $slider;
	      }
	    }

	    $response = [
            "sliders" => $sliders,
            "categories" => $categories,
            "products" => $products,
            "shippings" => $shippings
        ];

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($response));
			
	} else if (isset($_GET['get_category'])) {
		$query = "SELECT DISTINCT c.category_id, c.category_name, c.category_image, COUNT(DISTINCT p.product_id) as product_count FROM tbl_category c LEFT JOIN tbl_product p ON c.category_id = p.category_id GROUP BY c.category_id ORDER BY c.category_id DESC";			
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set[] = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));

	} else if (isset($_GET['get_posts'])) {
		$query = "SELECT * FROM tbl_post ORDER BY post_id DESC";			
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set[] = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));

	} else if (isset($_GET['get_shipping'])) {

		$query = "SELECT * FROM tbl_shipping ORDER BY shipping_id ASC";
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set['result'][] = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));
			
	} else if (isset($_GET['post_order'])) {

		$code 		 = $_POST['code'];
		$name 		 = $_POST['name'];
		$email 		 = $_POST['email'];
		$phone 		 = $_POST['phone'];
		$address 	 = $_POST['address'];
		$date_time 	 = $_POST['date_time'];
		$shipping 	 = $_POST['shipping'];
		$order_list  = $_POST['order_list'];
		$order_total = $_POST['order_total'];
		$comment 	 = $_POST['comment'];
		$player_id 	 = $_POST['player_id'];
		$date 		 = $_POST['date'];
		$server_url  = $_POST['server_url'];
		 
		$query = "INSERT INTO tbl_order (code, name, email, phone, address, date_time, shipping, order_list, order_total, comment, player_id) VALUES ('$code', '$name', '$email', '$phone', '$address', '$date_time', '$shipping', '$order_list', '$order_total', '$comment', '$player_id')";
		 
		if (mysqli_query($connect, $query)) {
			include_once ('php-mail.php');
			echo 'Data Inserted Successfully';
		} else {
			echo 'Try Again';
		}
		mysqli_close($connect);		

	} else if (isset($_GET['get_help'])) {

		$query = "SELECT * FROM tbl_help ORDER BY id DESC";
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set[] = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));
			
	} else if (isset($_GET['product_id'])) {
		$query = "SELECT p.product_id, p.product_name, p.category_id, n.category_name, p.product_price, p.product_status, p.product_image, p.product_description, p.serve_for FROM tbl_category n, tbl_product p WHERE n.category_id = p.category_id AND p.product_id ='".$_GET['product_id']."'";
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	        $set = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));

	} else if (isset($_GET['get_settings'])) {

		$query = "SELECT s.*, c.* FROM tbl_settings s, tbl_currency c WHERE s.currency_id = c.currency_id AND s.id = 1";
		$resouter = mysqli_query($connect, $query);

		$set = array();
	    $total_records = mysqli_num_rows($resouter);
	    if($total_records >= 1) {
	      	while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)) {
	        $set = $link;
	      }
	    }

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($set));
			
	} else if (isset($_GET['get_config'])) {
		
	    $setting_sql = "SELECT s.*, c.* FROM tbl_settings s, tbl_currency c WHERE s.currency_id = c.currency_id AND s.id = 1";
		$setting_result = mysqli_query($connect, $setting_sql);

		$settings = array();
	    $total_setting = mysqli_num_rows($setting_result);
	    if($total_setting >= 1) {
	      	while ($setting = mysqli_fetch_array($setting_result, MYSQLI_ASSOC)) {
	        $settings[] = $setting;
	      }
	    }

	    $license_sql = "SELECT item_id, item_name, buyer, license_type, purchase_date FROM tbl_license LIMIT 1";
		$license_result = mysqli_query($connect, $license_sql);

		$licenses = array();
	    $total_license = mysqli_num_rows($license_result);
	    if($total_license >= 1) {
	      	while ($license = mysqli_fetch_array($license_result, MYSQLI_ASSOC)) {
	        $licenses[] = $license;
	      }
	    }

	    $response = [
            "settings" => $settings,
            "license" => $licenses
        ];

	    header('Content-Type: application/json; charset=utf-8');
	    echo $val = str_replace('\\/', '/', json_encode($response));		
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo "no method found!";
	}
	 
?>