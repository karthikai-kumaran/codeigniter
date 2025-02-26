<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('userModel');
        $this->load->library('Phpmailer_lib');
    }
    public function index()
    {
        $this->load->view('/components/imports');
        $this->load->view('components/header');
        $response['data'] = $this->userModel->showAllProducts();
        $this->load->view('ordering-page', $response);
    }

    public function saveOrder()
    {
        $customerName = $this->input->post('customerName');
        $customerEmail = $this->input->post('customerMailId');
        $totalAmount = $this->input->post('totalAmount');
        $products = $this->input->post('products');

        $orderItems = [];
        foreach ($products as $product) {
            if (isset($product['select'])) {
                $orderItems[] = [
                    'productId' => $product['productId'],
                    'itemName' => $product['itemName'],
                    'unitPrice' => $product['unitPrice'],
                    'quantity' => $product['quantity'],
                    'totalValue' => $product['unitPrice'] * $product['quantity']
                ];
            }
        }
        $orderData = [
            'customerName' => $customerName,
            'customerMailId' => $customerEmail,
            'totalAmount' => $totalAmount,
            'orderItems' => json_encode($orderItems),
            'createdAt' => date('Y-m-d H:i:s')

        ];
        $orderId = $this->userModel->insertOrder($orderData);
        $this->userModel->updateOrderStatus($orderItems);

        if ($orderId) {
            echo ("Order placed successfully. Order ID: " . $orderId);
            redirect(base_url('user'));
        } else {
            echo ("Failed to place order.");
            redirect(base_url('user'));
        }
    }
    public function showOrderHistory()
    {
        $this->load->view('/components/imports');
        $this->load->view('/components/header');
        $response['data'] = $this->userModel->showOrderHistory();
        $this->load->view('home', $response);
    }

    public function mailStarter(){
        $this->load->view('send_mail.php');
    }
    public function sendEmail(){
        $mail = $this->phpmailer_lib->load();

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'boxerkarthi7639@gmail.com';
            $mail->Password   = 'rsaw wclv jdqb wknz';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email settings
            $mail->setFrom('boxerkarthi7639@gmail.com', 'Karthi');
            $mail->addReplyTo('boxerkarthi7639@gmail.com', 'Karthi');
            $mail->addAddress('karthikaikumaran299@gmail.com'); // Recipient's email

            $mail->Subject = 'Test Email';
            $mail->isHTML(true);
            $mail->Body = '<p>This is a test email sent using PHPMailer in CodeIgniter 3</p>';

            // Send email
            if ($mail->send()) {
                echo 'Email has been sent successfully.';
            } else {
                echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $e->getMessage();
        }
    }
}
?>
