<?php

session_start();

require 'vendor/autoload.php';

use myPHPnotes\Microsoft\Auth;

$tenant = 'common';
$client_id = 'YOUR_CLIENT_ID_HERE';
$client_secret = 'YOUR_CLIENT_SECRET_HERE';
$callback = 'http://localhost:8080/callback.php'; // Your callback URL
$scopes = ['User.Read', 'Files.ReadWrite.All', 'offline_access'];

$microsoft = new Auth($tenant, $client_id, $client_secret, $callback, $scopes);

header('location: ' . $microsoft->getAuthUrl());
