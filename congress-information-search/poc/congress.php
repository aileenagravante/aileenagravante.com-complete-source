<html>
<head>
  <title>Congress Information Search</title>
  <style>
    body {
      margin: 20px auto 0 auto;
      width: 800px;
      text-align: center;
    }
    form {
      display: block;
      margin: 0 auto 20px auto;
      width: 300px;
      border: 1px solid #000;
      padding: 2px;
      text-align: center;
    }
    form label,
    form select,
    form input,
    form button {
      display: inline-block;
      margin: 2px;
    }
    #form-labels {
      width: 140px;
      float: left;
    }
    #form-inputs {
      width: 160px;
      float: left;
    }
    #results > table {
      margin: auto;
      width: 100%;
    }
    #instructions {
      font-size: 12px;
      text-align: left;
      margin: 0 auto 20px auto;
      padding: 10px 20px;
      width: 460px;
      background: #FFFFE0;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding: 4px;
      text-align: center;
    }
    .view-details {
      border: 1px solid black;
      padding: 20px 50px;
    }
    .view-details img {
      margin: 0 0 20px 0;
    }
    .view-details table,
    .view-details td{
      border: none;
    }
    .view-details > table {
      margin: auto;
    }
    .view-details > table td:nth-of-type(2) {
      padding: 0 0 0 50px;
    }
    .view-details td {
      text-align: left;
      line-height: 12px;
    }
    #legislator-table td:first-of-type {
      text-align: left;
      padding: 0 0 0 50px;
    }
  </style>
</head>
<body>

  <?php
    // Define global PHP variables
    $label = "Keyword*";
    if(!empty($_GET)) {
      define("DOMAIN", "http://congress.api.sunlightfoundation.com/");
      define("APIKEY", "&apikey=c3e70c8d5e044cf6a5df7c7497d11cda");

      $database = $_GET['database'];
      $chamber = $_GET['chamber'];
      $keyword = $_GET['keyword'];
      $submit = $_GET['submit'];

      if($database == "legislators") {
        $label = "State/Representative*";
      }
      else if($database == "committees") {
        $label = "Committee ID*";
      }
      else if($database == "bills") {
        $label = "Bill ID*";
      }
      else if($database == "amendments") {
        $label = "Amendment ID*";
      }

      $stateMapping = array();
      $stateMapping =
      [
        "alabama" => "AL",
        "alaska" => "AK",
        "arizona" => "AZ",
        "arkansas" => "AR",
        "california" => "CA",
        "colorado" => "CO",
        "connecticut" => "CT",
        "delaware" => "DE",
        "district of columbia" => "DC",
        "florida" => "FL",
        "georgia" => "GA",
        "hawaii" => "HI",
        "idaho" => "ID",
        "illinois" => "IL",
        "indiana" => "IN",
        "iowa" => "IA",
        "kansas" => "KS",
        "kentucky" => "KY",
        "louisiana" => "LA",
        "maine" => "ME",
        "maryland" => "MD",
        "massachusetts" => "MA",
        "michigan" => "MI",
        "minnesota" => "MN",
        "mississippi" => "MS",
        "missouri" => "MO",
        "montana" => "MT",
        "nebraska" => "NE",
        "nevada" => "NV",
        "new hampshire" => "NH",
        "new jersey" => "NJ",
        "new mexico" => "NM",
        "new york" => "NY",
        "north carolina" => "NC",
        "north dakota" => "ND",
        "ohio" => "OH",
        "oklahoma" => "OK",
        "oregon" => "OR",
        "pennsylvania" => "PA",
        "rhode island" => "RI",
        "south carolina" => "SC",
        "south dakota" => "SD",
        "tennessee" => "TN",
        "texas" => "TX",
        "utah" => "UT",
        "vermont" => "VT",
        "virginia" => "VA",
        "washington" => "WA",
        "west virginia" => "WV",
        "wisconsin" => "WI",
        "wyoming" => "WY"
      ];
    }
  ?>

  <h2>Congress Information Search</h2>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    <div id="form-labels">
      <label for="database" id="database-label">Congress Database</label><br/>
      <label for="chamber" id="chamber-label">Chamber</label><br/>
      <label for="keyword" id="keyword-label"><?php echo $label?></label>
    </div>
    <div id="form-inputs">
      <select name="database" id="database-select" onchange="updateKeywordLabel()">
        <option value="noselection" id="database-default" <?php if (!isset($database) || (isset($database) && $database=="noselection")) echo "selected"; ?>>Select your option</option>
        <option value="legislators" <?php if (isset($database) && $database=="legislators") echo "selected"; ?>>Legislators</option>
        <option value="committees" <?php if (isset($database) && $database=="committees") echo "selected"; ?>>Committees</option>
        <option value="bills" <?php if (isset($database) && $database=="bills") echo "selected"; ?>>Bills</option>
        <option value="amendments" <?php if (isset($database) && $database=="amendments") echo "selected"; ?>>Amendments</option>
      </select>
      <br/>

      <input type="radio" name="chamber" value="senate" id="chamber-default" <?php if (!isset($chamber) || (isset($chamber) && $chamber=="senate")) echo "checked"; ?>>Senate</input>
      <input type="radio" name="chamber" value="house" <?php if (isset($chamber) && $chamber=="house") echo "checked"; ?>>House</input>
      <br/>

      <input type="text" name="keyword" id="keyword-input" value="<?php if (isset($keyword)) echo $keyword?>">
      <br/>

      <input type="submit" name="submit" value="Search" onclick="return checkForm()">
      <button type="button" onclick="return clearForm()">Clear</button>
      <br/>
    </div>
    <a href="http://sunlightfoundation.com/" target="_blank">Powered by Sunlight Foundation</a>
  </form>
  <div id="instructions">
    <p><b><u>Instructions</u></b>:
      <ol>
        <li>Select a <b>Congress Database</b></li>
        <li>Select a <b>Chamber</b></li>
        <li>Depending on the selected <b>Congress Database</b>, input a <b>Keyword</b>:</li>
        <ul>
          <li>For <b>Legislators</b>, input a full or partial name</li>
          <li>For <b>Committees</b>, input a full Committee ID (ex: SSGA18)</li>
          <li>For <b>Bills</b>, input a full Bill ID (ex: hr5929-114)</li>
          <li>For <b>Amendements</b>, input a full Amendment ID (ex: samdt188-115)</li>
        </ul>
        <li>Click <b>Search</b></li>
      </ol>
    </p>
  </div>

  <script type="text/javascript">
    // Dynamically update the keyword label based on the what's chosen in the database-select drop-down
    function updateKeywordLabel() {
      var selectElement = document.getElementById('database-select');
      var selectedOption = selectElement.options[selectElement.selectedIndex].value;
      var newKeywordLabel = "Keyword*";

      if(selectedOption == "legislators") {
        newKeywordLabel = "State/Representative*";
      }
      else if(selectedOption == "committees") {
        newKeywordLabel = "Committee ID*";
      }
      else if(selectedOption == "bills") {
        newKeywordLabel = "Bill ID*";
      }
      else if(selectedOption == "amendments") {
        newKeywordLabel = "Amendment ID*";
      }
      document.getElementById('keyword-label').innerHTML = newKeywordLabel;
    }

    // When 'Search' is clicked, check the input to the form.
    // If fields are empty, indicate which fields are empty in an alert message.
    function checkForm() {
      var errMsg = "Please enter the following missing information: ";
      var showMsg = false;

      var selectElement = document.getElementById('database-select');
      var selectedOption = selectElement.options[selectElement.selectedIndex].value;

      if(selectedOption == "noselection") {
        showMsg = true;
        errMsg += "Congress Database. ";
      }
      if(document.getElementById('keyword-input').value == "") {
        showMsg = true;
        errMsg += "Keyword. ";
      }
      if(showMsg) {
        alert(errMsg);
        return false;
      }
    }

    // When 'Clear' button is clicked, clear any input already in the form,
    //    clear any results being displayed, and reset all form values to their
    //    default.
    function clearForm() {
      document.getElementById('database-default').selected = true;
      document.getElementById('chamber-default').checked = true;
      document.getElementById('keyword-label').innerHTML = "Keyword*";
      document.getElementById('keyword-input').value = "";
      document.getElementById('results').innerHTML = "";
      return false;
    }
  </script>

  <?php
    // For each if/else if block, the flow is similar to that of the if block when $database == "legislators"
    //    (Refer to comments in the legislators block for all subsequent else/if blocks)
    if (isset($submit)) {
      // Trim leading and trailing whitespace so that $keyword can properly
      //    be used by the API
      $keyword = trim($keyword);

      $output = "";
      $noResults =  "The API returned zero results for the request.";

      $query = "";
      $url = "";
      $results = "";

      $details = array();

      if($database == "legislators") {
        $query = get_legislator_query($keyword);
        $url = build_rest_url("legislators?chamber=$chamber&$query");
        $results = get_json_array($url);

        // ** Commented lines below are used for testing purposes only **
        // $testurl ='http://congress.api.sunlightfoundation.com/legislators?query=barbara&apikey=c3e70c8d5e044cf6a5df7c7497d11cda';
        // $results = get_json_array($testurl);

        if(count($results['results']) == 0) {
          $output = $noResults;
        }
        else {
          $rows = "";

          foreach ($results['results'] as $key => $value) {
            // Set the variables for each attribute that we will need to either display when 'Search' or 'View Details' is clicked
            $bioguideID = get_value($results['results'][$key], 'bioguide_id');

            $name = (get_value($results['results'][$key], 'first_name') . " ");
            $name .= get_value($results['results'][$key], 'last_name');

            // Needed to properly display names with accent marks for search results
            $nameDecoded = utf8_decode($name);

            $state = get_value($results['results'][$key], 'state_name');
            $legislatorChamber = get_value($results['results'][$key], 'chamber');

            // For our 'View Details' link, we set the 'data-type' to 'legislator' and 'data-id' to the bioguide ID,
            //    and upon clicking we call the Javascript function 'viewDetails(event)'.
            // The Javascript function then checks from which element the event was called and gets the 'data-type' and 'data-id'
            //    of that element and displays details accordingly (described in 'viewDetails(event)' function)
            $viewDetails = "";
            $viewDetails = ("<a data-type=\"legislator\" data-id=\"$bioguideID\" href=\"#\" onclick=\"return viewDetails(event);\">View Details</a>");

            $fullName = (get_value($results['results'][$key], 'title') . " " . $name);
            $termEnd = get_value($results['results'][$key], 'term_end');
            $website = get_value($results['results'][$key], 'website');
            $office = get_value($results['results'][$key], 'office');
            $facebook = get_value($results['results'][$key], 'facebook_id');
            $twitter = get_value($results['results'][$key], 'twitter_id');

            // Build table to display results (the results returned after clicking 'Search' button)
            $data = "";

            $data .= build_table_data($nameDecoded);
            $data .= build_table_data($state);
            $data .= build_table_data($legislatorChamber);
            $data .= build_table_data($viewDetails);

            $rows .= build_table_row($data);

            // For the current legislator, add to the details array what will be used in Javascript
            //    when 'View Details' for that legislator is clicked.
            // This array is given to the Javascript function to display appropriate 'View Details' results.
            $details[$bioguideID] = array("bioguide_id" => $bioguideID);
            $details[$bioguideID] += array("name" => $name);
            $details[$bioguideID] += array("full_name" => $fullName);
            $details[$bioguideID] += array("term_end" => $termEnd);
            $details[$bioguideID] += array("website" => $website);
            $details[$bioguideID] += array("office" => $office);
            $details[$bioguideID] += array("facebook" => $facebook);
            $details[$bioguideID] += array("twitter" => $twitter);
          }
          // Finally, build the HTML header row and add the HTML <table> tags around the resulting rows
          $output = build_final_table("legislator-table", array("Name", "State", "Chamber", "Details"), $rows);
        }
      }

      else if($database == "committees") {
        // If spaces exist spaces with '+' to be useable in the API request
        $keyword = str_replace(" ", "+", $keyword);

        // As per API, Committee IDs are always uppercase
        $keyword = strtoupper($keyword);
        $url = build_rest_url("committees?committee_id=$keyword&chamber=$chamber");
        $results = get_json_array($url);

        // ** Commented lines below are used for testing purposes only **
        // $testurl = "http://congress.api.sunlightfoundation.com/committees?apikey=c3e70c8d5e044cf6a5df7c7497d11cda";
        // $results = get_json_array($testurl);

        if(count($results['results']) == 0) {
          $output = $noResults;
        }

        else {
          $rows = "";

          foreach ($results['results'] as $key => $value) {
            $committeID = get_value($results['results'][$key], 'committee_id');
            $name = get_value($results['results'][$key], 'name');
            $committeeChamber = get_value($results['results'][$key], 'chamber');

            $data = "";

            $data .= build_table_data($committeID);
            $data .= build_table_data($name);
            $data .= build_table_data($committeeChamber);

            $rows .= build_table_row($data);
          }

          $output = build_final_table("committees-table", array("Committee ID","Committee Name", "Chamber"), $rows);
        }
      }

      else if($database == "bills") {
        // If spaces exist spaces with '+' to be useable in the API request
        $keyword = str_replace(" ", "+", $keyword);

        // As per API, Bill IDs are always lowercase
        $keyword = strtolower($keyword);
        $url = build_rest_url("bills?bill_id=$keyword&chamber=$chamber");
        $results = get_json_array($url);

        // ** Commented lines below are used for testing purposes only **
        // $testurl ='http://congress.api.sunlightfoundation.com/bills?apikey=c3e70c8d5e044cf6a5df7c7497d11cda';
        // $results = get_json_array($testurl);

        if(count($results['results']) == 0) {
          $output = $noResults;
        }
        else {
          $rows = "";

          foreach ($results['results'] as $key => $value) {
            $billID = get_value($results['results'][$key], 'bill_id');
            $shortTitle = get_value($results['results'][$key], 'short_title');
            $billChamber = get_value($results['results'][$key], 'chamber');

            $viewDetails = "";
            $viewDetails = ("<a data-type=\"bill\" data-id=\"$billID\" href=\"#\" onclick=\"return viewDetails(event);\">View Details</a>");

            $sponsor = (get_value($results['results'][$key]['sponsor'], 'title') . " ");
            $sponsor .= (get_value($results['results'][$key]['sponsor'], 'first_name') . " ");
            $sponsor .= get_value($results['results'][$key]['sponsor'], 'last_name');

            $introducedOn = get_value($results['results'][$key], 'introduced_on');

            $lastAction = (get_value($results['results'][$key]['last_version'], 'version_name') . ", ");
            $lastAction .= get_value($results['results'][$key], 'last_action_at');

            $billURL = get_value($results['results'][$key]['last_version']['urls'], 'pdf');

            $data = "";

            $data .= build_table_data($billID);
            $data .= build_table_data($shortTitle);
            $data .= build_table_data($billChamber);
            $data .= build_table_data($viewDetails);

            $rows .= build_table_row($data);

            $details[$billID] = array("bill_id" => $billID);
            $details[$billID] += array("short_title" => $shortTitle);
            $details[$billID] += array("sponsor" => $sponsor);
            $details[$billID] += array("introduced_on" => $introducedOn);
            $details[$billID] += array("last_action" => $lastAction);
            $details[$billID] += array("url" => $billURL);
          }
          $output = build_final_table("bills-table", array("Bill ID", "Short Title", "Chamber", "Details"), $rows);
        }
      }

      else if($database == "amendments") {
        // If spaces exist spaces with '+' to be useable in the API request
        $keyword = str_replace(" ", "+", $keyword);

        // As per API, Amendment IDs are always lowercase
        $keyword = strtolower($keyword);
        $url = build_rest_url("amendments?amendment_id=$keyword&chamber=$chamber");
        $results = get_json_array($url);

        // ** Commented lines below are used for testing purposes only **
        // $testurl = 'http://congress.api.sunlightfoundation.com/amendments?&apikey=c3e70c8d5e044cf6a5df7c7497d11cda';
        // $results = get_json_array($testurl);

        if(count($results['results']) == 0) {
          $output = $noResults;
        }

        else {
          $rows = "";

          foreach ($results['results'] as $key => $value) {
            $amendmentID = get_value($results['results'][$key], 'amendment_id');
            $type = get_value($results['results'][$key], 'amendment_type');
            $amendmentChamber = get_value($results['results'][$key], 'chamber');
            $introducedOn = get_value($results['results'][$key], 'introduced_on');

            $data = "";

            $data .= build_table_data($amendmentID);
            $data .= build_table_data($type);
            $data .= build_table_data($amendmentChamber);
            $data .= build_table_data($introducedOn);

            $rows .= build_table_row($data);
          }

          $output = build_final_table("amendments-table", array("Amendment ID","Amendment Type", "Chamber", "Introduced on"), $rows);
        }
      }

      // Finally, add HTML <div> tags, ID attribute, and echo the output
      echo build_div("results", "", $output);
    }

    // Helper function to build restful web service URL
    function build_rest_url($apistring) {
      return DOMAIN . $apistring . APIKEY;
    }

    // Helper function to parse response from API
    function get_json_array($url) {
      // Get contents of the API response into a string
      $json = file_get_contents($url);

      // Make sure the string is UTF-8 encoded since json_decode() only works on such strings
      // $json = utf8_decode($json);

      // Return JSON string into an array variable
      // We set the second parameter to "true" to convert
      //    returned string into an associative array
      return json_decode($json, true);
    }

    // Checks if the key exists, if so return the value.
    // Otherwise return a blank string (this is used to avoid 'undefined
    //    variable' error when the key does not exisit in the array)
    function get_value($array, $key) {
      if(isset($array[$key])) {
        return $array[$key];
      }
      else {
        return "";
      }
    }

    function get_legislator_query($inputKeyword) {
      global $stateMapping;
      $firstName = "";
      $lastName = "";
      $queryString = "";
      $inputKeyword = strtolower($inputKeyword);

      // First, we check if the input keyword has a mapping to an state code in our stateMapping array.
      if(array_key_exists($inputKeyword, $stateMapping)) {
        return ("state=$stateMapping[$inputKeyword]");
      }
      // If the input keyword does not have a mapping, we replace any whitespace with a single whitepace,
      //    then we split the input keyword into individual strings.
      //    (This is in case the input keyword consists of first + last name)
      else {
        $inputKeyword = preg_replace('/\s+/', ' ', $inputKeyword);
        $inputArray = explode(" ", $inputKeyword);
        // If the resulting array is of size 2, it means the input keyword was first + last name.
        //    Therefore, we use the first item in the array as our first name query value
        //    and the second item as our last name query value.
        //    (Note: When searching by first + last name, the API requires the first letter of
        //    the name be capitalized - this is why we use ucwords())
        if(count($inputArray) == 2) {
          $firstName = ucwords($inputArray[0]);
          $lastName = ucwords($inputArray[1]);

          // Takes care of the case where last name starts with "Mc" in which case the third letter
          //    in the last name is always capitalized (based on the API).
          if(substr($lastName, 0, 2) == "Mc") {
            $lastName[2] = strtoupper($lastName[2]);
          }

          return ("first_name=$firstName&last_name=$lastName");
        }
        // If the resulting array is not of size 2, it means that the input keyword must be a blanket
        //    keyword search on all parts of the name.
        // Notice, we replace spaces with '+' once we get to this point, this is to prevent
        //    an error if more than 2 strings are input as keyword.
        //    (Note: Using "query" in the API searches for the keyword in all available name attributes)
        else {
          $inputKeyword = str_replace(" ", "+", $inputKeyword);
          return ("query=$inputKeyword");
        }
      }
    }

    // Helper functions to build HTML table elements
    function build_table_header($array) {
      $output = "<tr>";
      foreach ($array as $key => $value) {
        $output .= ("<th>" . $value . "</th>");
      }
      return $output .= "</tr>";
    }

    function build_table_row($tr) {
      return ("<tr>" . $tr . "</tr>");
    }

    function build_table_data($td) {
      return ("<td>" . $td . "</td>");
    }

    function add_table_tags($id, $content) {
      return ("<table id=\"$id\">" . $content . "</table>");
    }

    // We include $id as a paramerter here so we can include an id attribute
    //    for the resulting table.
    function build_final_table($id, $headerFields, $rows) {
      $header = build_table_header($headerFields);
      return add_table_tags($id, ($header . $rows));
    }

    // Helper function to build HTML div
    function build_div($id, $class, $content) {
      return ("<div id=\"$id\" class=\"$class\">$content</div>");
    }

    // Helper function to build HTML a href tag
    function build_href_tag($url, $linktext) {
      return ("<a href=\"$url\" target=\"_blank\">$linktext</a>");
    }
  ?>
  <pre>
      <?php
        // ** Commented lines below are used for testing purposes only **
        // print_r($results);
      ?>
  </pre>

  <script type="text/javascript">
    // The viewDetails(event) function is driven by  the 'data-id' and 'data-type' attributes of
    //    the event that triggered this function.
    function viewDetails(event) {
      var type = "";
      var id = "";
      var results = "";
      var facebook = "";
      var twitter = "";

      // Get the 'data-type' and 'data-id' attributes of the event ('View Details' link)
      //    that called this function.
      var type = event.target.getAttribute("data-type");
      var id = event.target.getAttribute("data-id");

      // Get the $details array which was built by the PHP if it is set.
      //    (Note: we need to first check if the $details array is set otherwise when page
      //    is first loaded before 'Search' is invoked - this will cause 'undefined function' error)
      var details = <?php if(isset($details)) echo json_encode($details); else echo "new Array()"; ?>;

      if(type == "bill") {
        results += buildTableRow(buildTableData("Bill ID") + buildTableData(details[id]['bill_id']));
        results += buildTableRow(buildTableData("Bill Title") + buildTableData(details[id]['short_title']));
        results += buildTableRow(buildTableData("Sponsor") + buildTableData(details[id]['sponsor']));
        results += buildTableRow(buildTableData("Introduced On") + buildTableData(details[id]['introduced_on']));
        results += buildTableRow(buildTableData("Last action with date") + buildTableData(details[id]['last_action']));

        // If the short_title of the bill exists, use it as the hyperlink text for the pdf URL
        if(details[id]['short_title'] != "") {
          results += buildTableRow(buildTableData("Bill URL") + buildTableData(buildHrefTag(details[id]['url'], details[id]['short_title'])));
        }
        // Otherwise, use the bill ID as the hyperlink text
        else {
          results += buildTableRow(buildTableData("Bill URL") + buildTableData(buildHrefTag(details[id]['url'], details[id]['bill_id'])));
        }

        // Add HTML <table> tags around the result
        results = addTableTags(results);

        // Wrap the results in an HTML <div> and add the 'view-details' class
        results = buildDiv("", "view-details", results);
      }

      else if(type == "legislator") {
        results += (buildImgTag("https://theunitedstates.io/images/congress/225x275/" + details[id]['bioguide_id'] + ".jpg"));
        results += buildTableRow(buildTableData("Full Name") + buildTableData(details[id]['full_name']));
        results += buildTableRow(buildTableData("Term Ends on") + buildTableData(details[id]['term_end']));
        results += buildTableRow(buildTableData("Website") + buildTableData(buildHrefTag(details[id]['website'], details[id]['website'])));
        results += buildTableRow(buildTableData('Office') + buildTableData(details[id]['office']));

        // If this legislator has a Facebook and/or Twitter, create the HTML <a> tag for the Facebook link
        //    and Twitter links and use 'name' as the hyperlink text.
        if(details[id]['facebook'] != "") {
          facebook = buildHrefTag(("https://www.facebook.com/" + details[id]['facebook']), details[id]['name']);
        }
        // Otherwise, return 'NA'
        else {
          facebook = 'NA';
        }

        if(details[id]['twitter'] != "") {
          twitter = buildHrefTag(("https://twitter.com/" + details[id]['twitter']), details[id]['name']);
        }
        else {
          twitter = 'NA';
        }

        results += (buildTableRow(buildTableData("Facebook") + buildTableData(facebook)));
        results += (buildTableRow(buildTableData("Twitter") + buildTableData(twitter)));

        results = addTableTags(results);
        results = buildDiv("", "view-details", results);
      }

      // Change the 'results' <div> to contain the 'View Details' results built above
      document.getElementById('results').innerHTML = results;
      return false;
    }

    // Helper functions to build HTML elements
    function buildTableData(td) {
      return ("<td>" + td + "</td>");
    }

    function buildTableRow(tr) {
      return ("<tr>" + tr + "</tr>");
    }

    function addTableTags(tableContent){
      return ("<table>" + tableContent + "</table>");
    }

    function buildHrefTag(url, linkText) {
      return ("<a href=\"" + url + "\" target=\"_blank\">" + linkText + "</a>");
    }

    function buildImgTag(source) {
      return ("<img src=\"" + source + "\">");
    }

    function buildDiv(divID, divClass, divContent) {
      return ("<div id=\"" + divID + "\" class=\"" + divClass + "\">" + divContent + "</div>");
    }
  </script>
  <noscript>
</body>
</html>
