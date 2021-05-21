<?php

function pre($p) {
    echo '<pre>';
    print_r($p);
    echo '</pre>';
}

function site_url($p) {
    return SITEURL . '/' . $p;
}

function jtoa($p) {
    return json_decode($p);
}

function testConnection($apiKey) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://my.gridpane.com/oauth/api/v1/site",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$apiKey
    ),
  ));

  $response = curl_exec($curl);
  if (!$response)
  {
    return curl_error($curl);
  }
  curl_close($curl);
  return jtoa($response);
}

function get_site_list() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://my.gridpane.com/oauth/api/v1/site?apikey=".TOKEN,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".TOKEN
      ),
    ));

    $response = curl_exec($curl);

    
    curl_close($curl);
    return jtoa($response);
}

function get_system_user($uname) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://my.gridpane.com/oauth/api/v1/system-user/'.$uname,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);
    return $response;
}

function add_system_user($user) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://my.gridpane.com/oauth/api/v1/system-user',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $user,
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);
    return $response;
}

function create_site($data) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://my.gridpane.com/oauth/api/v1/site",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer ".$data['apiKey']
        ),
    ));
    $gridpane_responce = curl_exec($curl);
    return $gridpane_responce;
}

function enable_disable_account($site_d, $data) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://my.gridpane.com/oauth/api/v1/site/$site_d",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer ".TOKEN
        ),
    ));
    $gridpane_responce = curl_exec($curl);
    return $gridpane_responce;
}

function delete_site($site_id) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://my.gridpane.com/oauth/api/v1/site/$site_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        // CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer ".TOKEN
        ),
    ));
    $gridpane_responce = curl_exec($curl);
    return $gridpane_responce;
}

function delete_system_user($id) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://my.gridpane.com/oauth/api/v1/system-user/'.$id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      // CURLOPT_POSTFIELDS => $user,
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);
    if(!empty($response->error)) {
      delete_system_user($id);
    }
    else {
      return $response;
    }
}
