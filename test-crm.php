<?php
define('WP_USE_THEMES', false);
require('./wp-load.php');

// Test data
$_COOKIE['square_footage'] = '1500';
$_COOKIE['home_type'] = 'Single-Family Home';
$_COOKIE['story'] = 'Two Stories';
$_COOKIE['pitch'] = 'Low Pitch';
$_COOKIE['roof_type'] = 'Asphalt Shingle Roof';
$_COOKIE['zip_code'] = '12345';
$_COOKIE['first_name'] = 'John';
$_COOKIE['last_name'] = 'Doe';
$_COOKIE['email'] = 'john.doe@example.com';
$_COOKIE['phone_number'] = '5551234567';
$_COOKIE['final_price'] = '25,000';
$_COOKIE['low_price'] = '20,000';
$_COOKIE['high_price'] = '30,000';
$_COOKIE['trustedform_cert_url'] = 'test_url';

echo "<h1>Testing CRM Functionality</h1>";

// Include the CRM logic
include('crm.php');
?>
