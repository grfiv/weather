<?php
/** @file
 *
 * Make an api call to Dark Sky on behalf of
 * my little jQuery Mobile app
 */

# get lat/lon if request properly formatted; else send error
# ==========================================================
if (isset($_GET['lat']) &&  isset($_GET['lon'])) {
    $lat = (float) $_GET['lat'];
    $lon = (float) $_GET['lon'];
} else {
    header('Content-type: application/json');
    $json_msg = array();
    $json_msg['status']  = 'error';
    $json_msg['error']   = 'request incorrectly formatted';
    $json_msg['message'] = "correct format is weather_api.php?lat=37.8267&lon=-122.4233";
    echo json_encode($json_msg);
    exit;
}

# retrieve my api-keyed url from an undisclosed location
# ======================================================
include("inc/dark-sky-api-call.php");

# see where the request is coming from
# ====================================
$remote_ip_parts    = explode (".", $_SERVER['REMOTE_ADDR']);
$localhost_ip_parts = explode (".", LOCALHOST);
$isp_ip_parts       = explode (".", MY_ISP);
$vpn_east_ip_parts  = explode (".", VPN_EAST);
$cmct_boston_parts  = explode (".", COMCAST_BOSTON);

if (($remote_ip_parts[0] != $localhost_ip_parts[0]) &&
    ($remote_ip_parts[0] != $vpn_east_ip_parts[0]) &&
    ($remote_ip_parts[0] != $cmct_boston_parts[0]) &&
    ($remote_ip_parts[0] != $isp_ip_parts[0])) exit;

if (($remote_ip_parts[1] != $localhost_ip_parts[1]) &&
    ($remote_ip_parts[1] != $vpn_east_ip_parts[1]) &&
    ($remote_ip_parts[1] != $cmct_boston_parts[1]) &&
    ($remote_ip_parts[1] != $isp_ip_parts[1])) exit;

# build out the api call url
# ==========================
$url = DARK_SKY_API_CALL . $lat . "," . $lon;

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

