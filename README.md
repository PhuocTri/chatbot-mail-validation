# chatbot-mail-validation
Gửi mã xác nhận Mail thông qua Chatbot

# Cách sử dụng
Bước 1: import file SQL vào phpmyadmin và vào config.php để cấu hình lại máy chủ mySQL

Bước 2: chỉnh sửa file mail.php dòng 72
SenderName = "Tên hiển thị khi gửi thư" (VD: Vy Nghia)
MailServer = "STMP Mail Server của email bạn" (đối với Gmail là smtp.gmail.com)
usermailer = "tài khoản email STMP server của bạn" (VD: nghiaisgay@gmail.com)
passmailer = "mật khẩu email STMP serer của bạn" (1)(2)

(1) Đối với STMP của Gmail bạn cần phải đăng nhập bằng Mã (mật khẩu) ứng dụng!
(2) Tham khảo STMP và mã (mật khẩu) ứng dụng của Gmail tại: https://thachpham.com/wordpress/wordpress-tutorials/smtp-gmail-wordpress.html

Bước 3: vào chatfuel.com và tạo 1 Group Blocks (tên gì cũng được miễn bạn nhận ra) và tạo các block sao đây!

SendMailUser - TrySend - Validation - EndChat

*Cấu hình các Block
- [SendMailUser]
+ User Input: (nhập giá trị user)
MESSAGE TO USER: Nhập tên tài khoản của bạn:
SAVE ANSWER TO ATTRIBUTE: {{user}}

+ Typing 0.4s (chờ 0.4s)

+ JSON API: (gửi giá trị user tới server, kiểm tra và gửi thư)
URL: http://domain.com/mail.php?user={{user}}&action=send

- [TrySend]

+ JSON API: (kiểm tra giá trị user có tồn tại hay không)
URL: http://domain.com/check.php?user={{user}}

+ Typing 0.4s (chờ 0.4s)

+ JSON API: (gửi lại mã tạo trước đó)
URL: http://domain.com/mail.php?user={{user}}&action=try

- [Validation]
+ JSON API: (kiểm tra giá trị user có tồn tại hay không)
URL: http://domain.com/check.php?user={{user}}

+ User Input: (nhập giá trị code)
MESSAGE TO USER: Nhập mã gửi đến Email của bạn:
SAVE ANSWER TO ATTRIBUTE: {{code}}

+ Typing 0.4s (chờ 0.4s)

+ JSON API: (kiểm tra giá trị user và code có trùng khớp với CSDL không)
URL: http://domain.com/validation.php?user={{user}}&code={{code}}

- [EndChat] 
+ Set up user attribute:
(1) USER ATTRIBUTE: {{user}}
VALUE: NOT SET
(2) USER ATTRIBUTE: {{code}}
VALUE: NOT SET
=> Đặt các giá trị về null (để người dùng không sử dụng lại các nút có sẵn ở trên)
