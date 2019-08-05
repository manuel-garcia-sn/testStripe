<?php

require '../vendor/autoload.php';

session_start();

unset($_SESSION['stripeToken']);
unset($_SESSION['customer']);

header('Location: http://localhost:8080/');
exit;