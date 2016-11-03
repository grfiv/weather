<?php
/** @file
 *
 * Make an api call to Google geocoder on behalf of
 * my little jQuery Mobile app
 *
 * usage:
 * geocoder_api.php?address=5+Pier+7,+Charlestown,+MA
 */

# get lat/lon if request properly formatted; else send error
# ==========================================================
if (isset($_GET['address'])) {
    $address = preg_replace('!\s+!', '+', $_GET['address']);
} else {
    header('Content-type: application/json');
    $json_msg = array();
    $json_msg['status']  = 'error';
    $json_msg['error']   = 'request incorrectly formatted';
    $json_msg['message'] = "correct format is geocoder_api.php?address=5+Pier+7,+Charlestown,+MA";
    echo json_encode($json_msg);
    exit;
}

# retrieve my api-keyed url from an undisclosed location
# ======================================================
include("inc/google-api-call.php");

# build out the api call url
# ==========================
$url = GOOGLE_API_CALL . 'address=' . $address . GOOGLE_API_KEY;

/**
 * Download a URL’s Content Using PHP cURL
 *
 * citation: https://davidwalsh.name/curl-download
 *
 * @param {string} the api url
 *
 * @return {string} the api response in JSON format
 */
function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
$returned_content = get_data($url);

# pass along the JSON to whoever called us
# ----------------------------------------
header('Content-type: application/json');
echo $returned_content;

?>

