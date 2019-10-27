<?php

function get_sf_auth_data() {
    $post_data = array(
        'grant_type'   => 'password',
        'client_id'    => 'xxxxxxxxxxxxxxxxxxxxxx', //My client id (xxx... for this example)
        'client_secret' => '111111111111', // My client secret (111... for this example)
        'username'     => 'my_user_name',
        'password'     => 'my_user_password'
    );

    $headers = array(
        'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'
    );

    $curl = curl_init('https://login.salesforce.com/services/oauth2/token');

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

    $response = curl_exec($curl);
    curl_close($curl);

    // Retrieve and parse response body
    $sf_access_data = json_decode($response, true);

    echo '*********' . $sf_response_data['instance_url'] . '********';
    echo '*********' . $sf_response_data['access_token'] . '********';

    return $sf_access_data;
}

function get_user_sfid($sf_access_data, $user_id_number){
    $sql = "SELECT Id, Name
            FROM Account
            WHERE ID__c = '$user_id_number'";

    $url = $sf_access_data['instance_url'] . '/rfid/tags';

    $headers = array(
        "Authorization: OAuth " . $sf_access_data['access_token']
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $json_response = curl_exec($curl);
    curl_close($curl);

    var_dump($json_response); // This prints out the response where i got the error

    $response = json_decode($json_response);

    return $response['id'];
}

?>