<?php
  if (isset($_SERVER['HTTP_ORIGIN'])) {
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if($http_origin == "https://aileenagravante.azurewebsites.net/" || $http_origin == "https://www.aileenagravante.azurewebsites.net/"
    || $http_origin == "http://aileenagravante.azurewebsites.net/" || || $http_origin == "http://www.aileenagravante.azurewebsites.net/") {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: X-Requested-With");
  }

  // This is the path to the JSON files
  define("DOMAIN", "https://aileenagravante.azurewebsites.net/congress-information-search/spa/data/");

  // These are the constants used when using the Sunlight Foundation API
  // define("SUN_DOMAIN", "http://congress.api.sunlightfoundation.com/");
  // define("SUN_APIKEY", "&apikey=c3e70c8d5e044cf6a5df7c7497d11cda");

  // These are the constants used when using the ProPublica Congress API
  define("PRO_DOMAIN", "https://api.propublica.org/congress/v1/");
  define("PRO_APIKEY", "X-API-Key: q0awPn8mXlqkiNTGAUMYgPSpN7c84raaoKKgkT9c");

  $database = "";
  $keyword = "";

  if(!empty($_GET)) {
    if(isset($_GET['database'])) {
      $database = $_GET['database'];
    }
    if(isset($_GET['keyword'])) {
      $keyword = $_GET['keyword'];
    }
  }

  if(isset($database) && isset($keyword)) {
    if($database == "legislators" && $keyword == "all") {
      // This is the querystring used when using the Sunlight Foundation API
      // echo file_get_contents(build_rest_url("legislators?fields=bioguide_id,party,last_name,first_name,chamber,district,state_name,state,title,phone,term_start,term_end,office,fax,birthday,twitter_id,facebook_id,website,oc_email&per_page=all"));
      echo file_get_contents(DOMAIN . "congress_legislators_all.json");
    }
    elseif($database == "bills") {
      if($keyword == "active") {
        // This is the querystring used when using the Sunlight Foundation API
        // echo file_get_contents(build_rest_url("bills?history.active=true&last_version.urls.pdf__exists=true&order=introduced_on&per_page=50"));
        echo file_get_contents(DOMAIN . "congress_bills_active.json");
      }
      else if($keyword == "new") {
        // This is the querystring used when using the Sunlight Foundation API
        // echo file_get_contents(build_rest_url("bills?history.active=false&last_version.urls.pdf__exists=true&order=introduced_on&per_page=50"));
        echo file_get_contents(DOMAIN . "congress_bills_new.json");
      }
      else {
        // This is the querystring used when using the Sunlight Foundation API
        // echo file_get_contents(build_rest_url("bills?per_page=5&sponsor.bioguide_id=" . $keyword));
        echo build_request("members/" . $keyword . "/bills/cosponsored.json");
      }
    }
    elseif($database == "committees") {
      if($keyword == "all") {
        // This is the querystring used when using the Sunlight Foundation API
        // echo file_get_contents(build_rest_url("committees?per_page=all"));
        echo file_get_contents(DOMAIN . "congress_committees_all.json");
      }
      else {
        // This is the querystring used when using the Sunlight Foundation API
        // echo file_get_contents(build_rest_url("committees?per_page=5&member_ids=" . $keyword));
      }
    }
  }

  // Helper function to build restful web service URL
  //  when using Sunlight Foundation API (where we use the API key defined in the querystring to authenticate)
  // function build_rest_url($apistring) {
  //   return SUN_DOMAIN . $apistring . SUN_APIKEY;
  // }

  // Helper function to build restful web service request
  //  when using ProPublica Congress API (where we use the API key defined in a custom header to authenticate)
  function build_request($request) {
    $context = stream_context_create(array(
      'http' => array(
          'method' => 'GET',
          'header' => PRO_APIKEY
      )
    ));
    return file_get_contents(PRO_DOMAIN . $request, false, $context);
  }
?>
