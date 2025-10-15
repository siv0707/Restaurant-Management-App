<?php

function Insert($table, $data) {

    include 'includes/config.php';
    $fields = array_keys( $data );  
    $values = array_map(array($connect, 'real_escape_string'), array_values($data) );
    
    $sql = "INSERT INTO $table (".implode(",",$fields).") VALUES ('".implode("','", $values )."')";
    mysqli_query($connect, $sql);
    
}

function Delete($table_name, $where_clause = '') {

    include 'includes/config.php';
    $whereSQL = '';
    if(!empty($where_clause)) {
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
            $whereSQL = " WHERE ".$where_clause;
        } else {
            $whereSQL = " ".trim($where_clause);
        }
    }
    $sql = "DELETE FROM ".$table_name.$whereSQL;
    return mysqli_query($connect, $sql);

}

    // Update Data, Where clause is left optional
function Update($table_name, $form_data, $where_clause='') {

    include 'includes/config.php';
        // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause)) {
            // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else {
            $whereSQL = " ".trim($where_clause);
        }
    }
        // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

        // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value) {
       $sets[] = "`".$column."` = '".$value."'";
   }
   $sql .= implode(', ', $sets);

        // append the where statement
   $sql .= $whereSQL;
   
        // run and return the query result
   return mysqli_query($connect, $sql);
}

function clean($data) {
    include 'includes/config.php';
    $data = mysqli_real_escape_string($connect, $data);
    return $data; 
}    

function redirect($location, $message) {
	$_SESSION["msg"] = $message;
    header("Location:".$location);
    exit();
}

function get_random_string($valid_chars, $length) {
	$random_string = "";
	$num_valid_chars = strlen($valid_chars);
	for ($i = 0; $i < $length; $i++) {
		$random_pick = mt_rand(1, $num_valid_chars);
		$random_char = $valid_chars[$random_pick-1];
		$random_string .= $random_char;
	}

	return $random_string;
}

function sanitize($string) {
	include 'includes/config.php';
	$string = mysqli_escape_string($connect, trim(strip_tags(stripslashes($string))));
	return $string;
}

function check_integer($which) {
	if(isset($_GET[$which])){
		if (intval($_GET[$which])>0) {
			return intval($_GET[$which]);
		} else {
			return false;
		}
	}
	return false;
}

function get_current_page() {
	if(($var=check_integer('page'))) {
		return $var;
	} else {
		return 1;
	}
}

function get_license() {
	$license = "f783194a-9853-4b6f-bd6e-ce799ce6f2ea";
	return $license;
}

function doPages($page_size, $thepage, $query_string, $total=0, $keyword) {
	$index_limit = 10;
	$query = '';

	if( strlen($query_string) > 0) {
		$query = "&amp;".$query_string;
	}

	$current = get_current_page();

	$total_pages = ceil($total / $page_size);
	$start = max($current - intval($index_limit / 2), 1);
	$end = $start + $index_limit - 1;

	echo '<div class="body pull-right">';
	echo '<ul class="pagination">';

	if ($current == 1) {
		echo '<li class="disabled"><a>Prev</a></li>';
	} else {
		$i = $current - 1;
		echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'">Prev</a></li>';
	}
	if ($start > 1) {
		$i = 1;
		echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
	}

	for ($i = $start; $i <= $end && $i <= $total_pages; $i++) {
		if ($i == $current) {
			echo '<li class="active"><a>'.$i.'</a></li>';
		} else {
			echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
		}
	}

	if ($total_pages > $end) {
		$i = $total_pages;
		echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
	}

	if ($current < $total_pages) {
		$i = $current + 1;
		echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'">Next</a></li>';
	} else {
		echo '<li class="disabled"><a>Next</a></li>';
	}

	echo '</ul>';
  
	if ($total != 0) {
		echo '<br><div class="pull-right">( total '.$total.' )</div></div>';
	} else {
		echo '</div>';
	};

}

function reArrayFiles(&$file_post) {
	$file_ary = array();
	$file_count = count($file_post['name']);
	$file_keys = array_keys($file_post);
	for ($i=0; $i<$file_count; $i++) {
		foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $file_post[$key][$i];
		}
	}
	return $file_ary;
}

function oneSignalPush(
    $uniqueId,
    $title,
    $message,
    $bigImage,
    $link,
    $postId,
    $oneSignalAppId,
    $oneSignalRestApiKey,
    $redirect
) {
    $content = ["en" => $message];

    $fields = [
        "app_id" => $oneSignalAppId,
        "included_segments" => ["All"],
        "data" => [
            "foo" => "bar",
            "link" => $link,
            "post_id" => $postId,
            "unique_id" => $uniqueId,
        ],
        "headings" => ["en" => $title],
        "contents" => $content,
        "big_picture" => $bigImage,
        "url" => $link,
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

    $_SESSION["msg"] = "Push notification sent...";
    header($redirect);
    exit();
}

function singleOneSignalPush(
    $uniqueId,
    $title,
    $message,
    $bigImage,
    $link,
    $postId,
    $oneSignalAppId,
    $oneSignalRestApiKey,
    $redirect,
    $userId,
    $alert
) {
    $content = ["en" => $message];

    $fields = [
        "app_id" => $oneSignalAppId,
        'include_player_ids' => array($userId),
        "data" => [
            "foo" => "bar",
            "link" => $link,
            "post_id" => $postId,
            "unique_id" => $uniqueId,
        ],
        "headings" => ["en" => "Hi $title,"],
        "contents" => "Your order id : $content has been processed",
        "big_picture" => $bigImage,
        "url" => $link,
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

    $_SESSION["msg"] = $alert;
    header($redirect);
    exit();
}

?>