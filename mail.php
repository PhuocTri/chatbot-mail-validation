<?php
/* >_ Code by Vy Nghia */
require 'server/config.php';
require 'lib/class.smtp.php';
require 'lib/class.phpmailer.php';
if(isset($_GET['user'])){
	$user = $_GET['user'];
	
	function RandomCode($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomCode = null;
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}
	$SendCode = RandomCode();
	
	$userData = mysql_query("SELECT * FROM `user` WHERE `user`='$user'");
	while ($usr = mysql_fetch_array($userData)) {
		$username = $usr['name'];
		$usermail = $usr['mail'];
		$usercode = $usr['code'];
	}
	
	$codeData = mysql_query("SELECT * FROM `code` WHERE `code`='$usercode'");
	while ($code = mysql_fetch_array($codeData)) {
		$codestr = $code['code'];
		$codevrf = $code['verified'];
	}
		
	if(!$username){
		$message = array(
			"messages" => array(
				array(
						"attachment" => array(
							"type" => "template",
							"payload" => array(
								"template_type" => "button",
								"text" => "Tài khoản không tồn tại?!", 
								"buttons" => array(
									array(
										"type" => "show_block",
										"block_name" => "SendMailUser",
										"title" => "Thử lại"
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
		if(isset($_GET['action']) && ! empty ($_GET['action'])){
			if($codevrf == 0){
			$action = strtolower($_GET['action']);
			switch ($action){
				case 'send':
					if(isset($SendCode) && $username !== null){
						if(!$usercode){
						mysql_query("UPDATE `user` SET `code`= '$SendCode' WHERE `user`='$user'");
						} else {
							$SendCode = $usercode;
						}
						if(!$codestr){
						mysql_query("INSERT INTO `code`(`id`, `user`, `code`, `verified`) VALUES ('','$user','$SendCode','')");
						}
						
						#----------Mail Config----------#
						$SenderName = 'System Mail';
						$MailServer = 'smtp.gmail.com';
						$usermailer = 'example@gmail.com';
						$passmailer = '****************';
						#-------------------------------#
						
						$mail				= new PHPMailer();
						$mail->IsSMTP();             
						$mail->CharSet  	= 'utf-8';
						$mail->SMTPDebug  	= 0;
						$mail->SMTPAuth   	= true;
						$mail->SMTPSecure 	= 'ssl';
						$mail->Host       	= $MailServer;
						$mail->Port       	= 465;
						
						$mail->Username   	= $usermailer;
						$mail->Password		= $passmailer;
						$mail->SetFrom($usermailer, $SenderName);
						$mail->AddReplyTo	= false;
						
						$mail->Subject		= 'Verification Code';
						$mail->MsgHTML		('Hi '.$username.',<br><br>Your is Code: <b>'.$SendCode.'</b>');
						
						$mail->AddAddress($usermail, $username);
						
						if(!$mail->Send()) {
							$message = array(
								"messages" => array(
									array(
										"text" => "Có lỗi đã xảy ra trong quá trình xử lý email của bạn!"
									),
								)
							);
							echo json_encode($message);
							header("Status: 200");
						} else {
							$message = array(
								"messages" => array(
									array(
											"attachment" => array(
												"type" => "template",
												"payload" => array(
													"template_type" => "button",
													"text" => "Mã xác nhận đã được gửi tới $usermail!\n\nNếu bạn đã nhận được mã hãy nhấn nút Xác nhận hoặc nếu không hãy nhấn Gửi lại mã!!", 
													"buttons" => array(
														array(
															"type" => "show_block",
															"block_name" => "Validation",
															"title" => "Xác nhận"
														),
														array(
															"type" => "show_block",
															"block_name" => "TrySend",
															"title" => "Gửi lại mã"
														)
													)
												)
											)
										)
								)
							);
							echo json_encode($message);
							header("Status: 200");
						}
					}
				break;
				
				case 'try':
					if(isset($SendCode) && $username !== null){
						if(!$usercode){
							mysql_query("UPDATE `user` SET `code`= '$SendCode' WHERE `user`='$user'");
						}
						
						$SenderName = 'Linh Ka';
						$MailServer = 'smtp.strato.de';
						$usermailer = 'no-reply@linhka.com';
						$passmailer = '1151985611Nghia';
						
						$mail				= new PHPMailer();
						$mail->IsSMTP();             
						$mail->CharSet  	= 'utf-8';
						$mail->SMTPDebug  	= 0;
						$mail->SMTPAuth   	= true;
						$mail->SMTPSecure 	= 'ssl';
						$mail->Host       	= $MailServer;
						$mail->Port       	= 465;
						
						$mail->Username   	= $usermailer;
						$mail->Password		= $passmailer;
						$mail->SetFrom($usermailer, $SenderName);
						$mail->AddReplyTo	= false;
						
						$mail->Subject		= 'Verification Code';
						$mail->MsgHTML		('Hi '.$username.',<br><br>Your is Code: <b>'.$usercode.'</b>');
						
						$mail->AddAddress($usermail, $username);
						
						if(!$mail->Send()) {
							$message = array(
								"messages" => array(
									array(
										"text" => "Có lỗi đã xảy ra trong quá trình xử lý email của bạn!"
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
											"attachment" => array(
												"type" => "template",
												"payload" => array(
													"template_type" => "button",
													"text" => "Mã xác nhận đã được gửi tới $usermail!\n\nNếu bạn đã nhận được mã hãy nhấn nút Xác nhận hoặc nếu không hãy nhấn Gửi lại mã!!", 
													"buttons" => array(
														array(
															"type" => "show_block",
															"block_name" => "Validation",
															"title" => "Xác nhận"
														),
														array(
															"type" => "show_block",
															"block_name" => "TrySend",
															"title" => "Gửi lại mã"
														)
													)
												)
											)
										)
								)
							);
							echo json_encode($message);
							header("Status: 200");
						}
					}
				break;
		}
		} else {
				$message = array(
					"messages" => array(
						array(
							"text" => "Hi $username! Bạn đã xác nhận!"
						),
					)
				);
				echo json_encode($message);
				header("Status: 200");
			}
		}
	}
}