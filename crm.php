<?php
define('WP_USE_THEMES', false);
require('./wp-load.php');

function create_calculator_data_table_if_exists() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'calculator_data';
    
    // Check if the table already exists
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int NOT NULL AUTO_INCREMENT,
            first_name varchar(128),
            last_name varchar(128),
            zip varchar(16) NULL,
            phone varchar(32) NULL,
            email varchar(128) NULL,
            timeframe varchar(64) NULL,
            notes text NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($sql);
        
        if(empty($result)) {
            // echo "Table created successfully or already exists.<br>";
            return true;
        } else {
            // echo "Table creation result: <pre>" . print_r($result, true) . "</pre>";
            return false;
        }
    } else {
        // echo "Table $table_name already exists.<br>";
        // Update table to add timeframe column if it doesn't exist
        update_table_add_timeframe();
        return true;
    }
}

function update_table_add_timeframe() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'calculator_data';
    
    // Check if timeframe column exists
    $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'timeframe'");
    
    if (empty($column_exists)) {
        // Add timeframe column
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN timeframe varchar(64) NULL AFTER email");
    }
}

function clean_notes_data($notes) {
    // Log original notes for debugging
    // echo "Original notes length: " . strlen($notes) . "<br>";
    
    // First sanitize with WordPress function
    $notes = sanitize_textarea_field($notes);
    
    // Remove any problematic characters that might cause database issues
    $notes = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $notes); // Remove control characters
    $notes = mb_convert_encoding($notes, 'UTF-8', 'UTF-8'); // Ensure valid UTF-8
    
    // Clean up whitespace but preserve formatting
    $notes = preg_replace('/\r\n|\r/', "\n", $notes); // Normalize line endings
    $notes = preg_replace('/[ \t]+/', ' ', $notes); // Normalize spaces and tabs
    $notes = trim($notes); // Remove leading/trailing whitespace
    
    // Ensure notes don't exceed text column limit (65,535 characters)
    if (strlen($notes) > 65000) {
        // echo "Warning: Notes too long, truncating to 65,000 characters<br>";
        $notes = substr($notes, 0, 65000);
    }
    
    // echo "Cleaned notes length: " . strlen($notes) . "<br>";
    
    return $notes;
}
// create_calculator_data_table_if_exists();

// Debug: Check table structure
function debug_table_structure() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'calculator_data';
    
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
    // echo "Table exists: " . ($table_exists ? 'Yes' : 'No') . "<br>";
    
    if($table_exists) {
        $columns = $wpdb->get_results("DESCRIBE $table_name");
        // echo "Table structure:<br>";
        // echo "<pre>";
        // foreach($columns as $column) {
        //     echo $column->Field . " - " . $column->Type . " - " . $column->Null . " - " . $column->Key . " - " . $column->Default . "<br>";
        // }
        // echo "</pre>";
    }
}

debug_table_structure();


function loadZipCodes($filename) {
    $zipCodes = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $zipCodes[] = $data[0]; // Assuming each line has one ZIP code
        }
        fclose($handle);
    }
    return $zipCodes;
}

$square_footage = isset($_COOKIE['square_footage']) ? $_COOKIE['square_footage'] : '';
$home_type = isset($_COOKIE['home_type']) ? $_COOKIE['home_type'] : '';
$story = isset($_COOKIE['story']) ? $_COOKIE['story'] : '';
$pitch = isset($_COOKIE['pitch']) ? $_COOKIE['pitch'] : '';
$roof_type = isset($_COOKIE['roof_type']) ? $_COOKIE['roof_type'] : '';
$zip_code = isset($_COOKIE['zip_code']) ? $_COOKIE['zip_code'] : '';
$first_name = isset($_COOKIE['first_name']) ? $_COOKIE['first_name'] : '';
$last_name = isset($_COOKIE['last_name']) ? $_COOKIE['last_name'] : '';
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$phone_number = isset($_COOKIE['phone_number']) ? preg_replace('/\D+/', '', $_COOKIE['phone_number']) : '';
$final_price = isset($_COOKIE['final_price']) ? $_COOKIE['final_price'] : '';
$low_price = isset($_COOKIE['low_price']) ? $_COOKIE['low_price'] : '';
$high_price = isset($_COOKIE['high_price']) ? $_COOKIE['high_price'] : '';
$trustedform_cert_url = isset($_COOKIE['trustedform_cert_url']) ? $_COOKIE['trustedform_cert_url'] : '';

// setcookie("zip_code", "");
// setcookie("first_name", "");
// setcookie("last_name", "");
// setcookie("email", "");
// setcookie("phone_number", "");
// setcookie("final_price", "");
// unset($_COOKIE['zip_code']);
// unset($_COOKIE['first_name']);
// unset($_COOKIE['last_name']);
// unset($_COOKIE['email']);
// unset($_COOKIE['phone_number']);
// unset($_COOKIE['final_price']);

$notes = <<<EOT
Roofing project details:
Sq footage: $square_footage
Home Type: $home_type
Material: $roof_type
Pitch Type: $pitch
Stories: $story
Estimated Total: $final_price
Trusted Certificate URL: $trustedform_cert_url
EOT;

/**
 * Filter to avoid spam leads
 * If square footage is less than 500 or greater than 8000, ignore the lead
 */
function isSpamLead($square_footage) {
    $sqft = intval($square_footage);
    if ($sqft < 500 || $sqft > 8000) {
        return true;
    }
    return false;
}

// Check for spam leads before processing
if (!empty($square_footage) && isSpamLead($square_footage)) {
    // Ignore spam lead - exit without sending data
    header('Location: /thank-you/');
    exit();
}

if(!empty($zip_code) && !empty($first_name) && !empty($last_name) && !empty($phone_number) && !empty($email) && !empty($final_price)){
    $zipCodes = loadZipCodes('zip_codes.csv');
    if(in_array( $zip_code, $zipCodes)){
        $curl = curl_init();
        
        global $wpdb;

        $table_name = $wpdb->prefix . 'calculator_data';

        // Validate required fields
        if(empty($first_name) || empty($last_name) || empty($email) || empty($phone_number)) {
            echo "Error: Required fields are missing.<br>";
            echo "First Name: " . ($first_name ?: 'Missing') . "<br>";
            echo "Last Name: " . ($last_name ?: 'Missing') . "<br>";
            echo "Email: " . ($email ?: 'Missing') . "<br>";
            echo "Phone: " . ($phone_number ?: 'Missing') . "<br>";
            die();
        }

        $first_name = sanitize_text_field($first_name);
        $last_name = sanitize_text_field($last_name);
        $zip = sanitize_text_field($zip_code);
        $phone = sanitize_text_field($phone_number);
        $email = sanitize_email($email);
        $notes = clean_notes_data($notes);

        // Try to insert data
        $result = $wpdb->insert(
            $table_name,
            array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'zip' => $zip,
                'phone' => $phone,
                'email' => $email,
                'notes' => $notes
            )
        );
        
        if($result !== false && $wpdb->insert_id) {
            //  echo 'Data successfully inserted! ID: ' . $wpdb->insert_id . '<br>';
        } else {
            //  echo 'Failed to insert data. Error: ' . $wpdb->last_error . '<br>';
            //  echo 'Last Query: ' . $wpdb->last_query . '<br>';
            
            // Try to create table if it doesn't exist
            if(create_calculator_data_table_if_exists()) {
                // Try insert again
                $result = $wpdb->insert(
                    $table_name,
                    array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'zip' => $zip,
                        'phone' => $phone,
                        'email' => $email,
                        'notes' => $notes
                    )
                );
                
                if($result !== false && $wpdb->insert_id) {
                    //  echo 'Data successfully inserted after table creation! ID: ' . $wpdb->insert_id . '<br>';
                } else {
                    //  echo 'Still failed after table creation. Error: ' . $wpdb->last_error . '<br>';
                }
            } else {
                //  echo 'Failed to create table.<br>';
            }
        }


        // Send to Lead Conduit / Lead Perfection for supported home types only.
        $shouldSendToLeadConduit = ($home_type !== "Manufactured / Mobile Home");
        if ($shouldSendToLeadConduit) {
            $postData = http_build_query([
                'firstname' => $first_name,
                'lastname' => $last_name,
                'zip' => $zip_code,
                'phone1' => $phone_number,
                'email' => $email,
                'srs_id' => '1816',
                'notes' => $notes,
                'sender' => 'RoofCostsDotNetDirect',
                'productid' => "Roof",
                'trustedform_cert_url' => $trustedform_cert_url
            ]);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.leadconduit.com/flows/684198bbf6391f0c24db713a/sources/6842f4c980611fb482cdf0cb/submit',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: ASPSESSIONIDAWQSCQQD=IDMDHKFCNAAFKCCAFLMKDPPF'
                ),
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                echo "Error";
                // echo 'Error: ' . curl_error($curl);
            }

            curl_close($curl);
        } else {
            // Close curl handle if not sending to API
            curl_close($curl);
        }
    }
}
header('Location: /thank-you/');