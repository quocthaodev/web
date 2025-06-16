<?php
require_once __DIR__ . '/vendor/autoload.php'; // Nếu dùng Composer

$clientID = '152846567457-lfaqagasj7f56vhm8npppgjp235le4fl.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/website/login_google.php'; // Đúng với Redirect URI đã khai báo trên Google Console

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

session_start();

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // Lấy thông tin user
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // Đăng nhập hoặc tạo tài khoản mới trong DB nếu chưa có
    $_SESSION['user'] = $userInfo->email; // Hoặc $userInfo->name
    header('Location: index.php');
    exit();
} else {
    // Chuyển hướng sang Google để đăng nhập
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}