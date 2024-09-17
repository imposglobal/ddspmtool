<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Function to get the OAuth access token
 *
 * @return string The access token or an error message
 */
function getAccessToken() {
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'https://marketing.k99bs.com/oauth/v2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => '4_kszm8z5f3eo00s84wogkcs8c4gggooow00gsckc8kc04wsggg',
        'client_secret' => '513t8hjf4nk8gc8wk0sko0kswwwoowkkkcsggsk8okgos8o000'
    ]));

    // Set headers
    $headers = [
        'Content-Type: application/x-www-form-urlencoded'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $result = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Decode JSON response
    $responseData = json_decode($result, true);

    // Close cURL session
    curl_close($ch);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        // Return access token
        return $responseData['access_token'] ?? 'No access token found';
    } else {
        return 'JSON decode error: ' . json_last_error_msg();
    }
}

/**
 * Function to create a contact in Mautic with tags
 *
 * @param string $accessToken The OAuth access token
 * @param array $contactData The contact data to be sent
 * @return string The response from Mautic or an error message
 */
function createContact($accessToken, $contactData) {
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'https://marketing.k99bs.com/api/contacts/new');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));
    
    // Set headers
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $result = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Close cURL session
    curl_close($ch);

    // Return response
    return $result;
}

// Get the access token
$accessToken = getAccessToken();

// Check if the access token was retrieved successfully
if (strpos($accessToken, 'Error:') === false && strpos($accessToken, 'JSON decode error:') === false) {
    // Define the contact data including tags
    $contactData = [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '123-456-7890',
        'tags' => ['Doodlo'] // Tags to be added
    ];

    // Create the contact
    $response = createContact($accessToken, $contactData);

    // Output the response
    echo 'Response: ' . $response;
} else {
    // Output the error
    echo $accessToken;
}
?>
