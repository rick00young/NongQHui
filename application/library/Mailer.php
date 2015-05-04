<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/5/4
 * Time: 上午11:09
 */

Yaf_loader::import(APPLICATION_PATH . '/contrib/PHPMailer-5.2.9/class.phpmailer.php');
Yaf_loader::import(APPLICATION_PATH . '/contrib/PHPMailer-5.2.9/PHPMailerAutoload.php');

class Mailer {

    public static function sendMailFromService($sendtos, $title, $bodyHtml, $attachArray){
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 2;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cli_mail';                 // SMTP username
        $mail->Password = 'Iwi11ct0';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 587;                                    // TCP port to connect to

        $mail->From = 'cli_mail@163.com';
        $mail->FromName = '农曲星';

        $mail->CharSet = "UTF-8";   // 这里指定字符集！
        $mail->Encoding = "base64";

        self::addAddress($mail, $sendtos);
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        self::addAttachment($mail, $attachArray);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $title;
        $mail->Body    = $bodyHtml;
        $mail->AltBody = 'text/html';

        if(!$mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            SeasLog::error('Mailer Error: ' . $mail->ErrorInfo);
            return false;
        } else {
            return true;
        }

    }

    public static function addAddress(&$mail, $sendtos){
        $emailArray = explode(';', $sendtos);
        foreach ($emailArray as $email){
            $email = trim($email);
            list($user, $tmp) = explode('@', $email);
            $mail->AddAddress($email,$user);  // 收件人邮箱和姓名
        }
    }

    public static function addAttachment($mail, $attachArray){
        foreach ($attachArray as $attachPath){
            $mail->AddAttachment($attachPath); // attachment 附件
        }
    }
}


