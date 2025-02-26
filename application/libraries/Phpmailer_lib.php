<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_lib {
    public function __construct() {
        require_once(APPPATH . 'third_party/src/Exception.php');
        require_once(APPPATH . 'third_party/src/PHPMailer.php');
        require_once(APPPATH . 'third_party/src/SMTP.php');
    }

    public function load() {
        $mail = new PHPMailer();
        return $mail;
    }
}