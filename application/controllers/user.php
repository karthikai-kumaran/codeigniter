<?php


class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('userModel');
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
}
