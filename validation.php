<?php
/* >_ Code by Vy Nghia */
require 'server/config.php';
if(isset($_GET['code'])){
	$user = $_GET['user'];
	$code = $_GET['code'];
	
	$CodeUserData = mysql_query("SELECT * FROM `user` WHERE `user`='$user' AND `code`='$code'");
	while ($usr = mysql_fetch_array($CodeUserData)) {
		$username = $usr['user'];
	}
	
	if(isset($user)){
		if(!$username){
			$message = array(
				"messages" => array(
					array(
							"attachment" => array(
								"type" => "template",
								"payload" => array(
									"template_type" => "button",
									"text" => "[Xác nhận thất bại] Mã bạn nhập không hợp lệ hoặc không tồn tại?!", 
									"buttons" => array(
										array(
											"type" => "show_block",
											"block_name" => "Validation",
											"title" => "Thử lại"
										),
										array(
											"type" => "show_block",
											"block_name" => "TrySend",
											"title" => "Gửi lại mã"
										),
										array(
											"type" => "show_block",
											"block_name" => "EndChat",
											"title" => "Kết thúc xác nhận"
										)
									)
								)
							)
						)
				)
			);
			echo json_encode($message);
			header("Status: 200");
		} else {
			if(isset($username)){
				mysql_query("UPDATE `code` SET `verified`= '1' WHERE `user`='$user'");
				$message = array(
					"messages" => array(
						array(
							"text" => "Xác minh thành công!"
						)
					),
					"redirect_to_blocks" => array(
							"EndChat"
					)
				);
				echo json_encode($message);
				header("Status: 200");
			} else {
				$message = array(
					"messages" => array(
						array(
							"text" => "Đã xảy ra lỗi không xác định!"
						)
					),
					"redirect_to_blocks" => array(
						"EndChat"
					)
				);
				echo json_encode($message);
				header("Status: 200");
			}
		}
	}
} else {
	$message = array(
        "messages" => array(
            array(
                "text" => "Không có dữ liệu để thực hiện tác vụ!?"
            ),
        )
    );
    echo json_encode($message);
    header("Status: 200");
}