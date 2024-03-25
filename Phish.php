<?php
$count_file = 'user_count.txt';
$user_record_file = 'user_records.csv';

// Function to read the count from the file
function readCount($file) {
    if (file_exists($file)) {
        $count = file_get_contents($file);
        return intval($count);
    } else {
        return 0;
    }
}

// Function to write the count to the file
function writeCount($file, $count) {
    file_put_contents($file, $count);
}

// Function to increment the count and return the new count
function incrementCount($file) {
    $count = readCount($file);
    $count++;
    writeCount($file, $count);
    return $count;
}

// Function to append user record to CSV file
function appendUserRecord($file, $data) {
    $handle = fopen($file, 'a');
    fputcsv($handle, $data);
    fclose($handle);
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];

function getOS() { 
    global $user_agent;
    $os_platform  = "Unknown OS Platform";
    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {
    global $user_agent;
    $browser        = "Unknown Browser";
    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

function getDeviceName() {
    global $user_agent;
    $device_name = "Unknown Device";
    // Example regex pattern for detecting iPhone model names
    if (preg_match('/iPhone\s+([^\s;]+)/i', $user_agent, $matches)) {
        $device_name = $matches[0];
    }
    // Add more patterns for other devices as needed
    return $device_name;
}

$user_os        = getOS();
$user_browser   = getBrowser();
$device_name    = getDeviceName();

// Increment the count of users
$user_count = incrementCount($count_file);

// Append user record to CSV file
$user_record = array($user_os, $user_browser, $device_name);
appendUserRecord($user_record_file, $user_record);

// Redirect to thank you page after registering
header('Location: thank_you.html');
exit;
?>
