<?php
/* >_ Code by Vy Nghia */
if($_GET['user'] == null){
	$message = array(
		"messages" => array(
			array(
				"text" => "Không có dữ liệu để thực hiện tác vụ!"
			)
		),
		"redirect_to_blocks" => array(
			"EndChat"
		)
	);
	echo json_encode($message);
	header("Status: 200");
}