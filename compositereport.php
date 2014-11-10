
<?php
// Import $steamkey from config
include("CONFIG");

// Get user id from POST request and sanitize
$userid_raw = $_POST["steam_userid"];
$userid = htmlspecialchars($userid_raw);

// Call Steam API for user. Stop if user doesn't exist
if(!($json_user_games_raw = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $steamkey . "&steamid=" . $userid . "&format=json"))) {
    echo 'Oops! No steam user found for this id!<br>';
    echo 'ID: ' . $userid . '<br>';
    echo '<a href="home.html">Return to Home Page</a>';
    exit(0);
}

// Decode and loop through all appids
$json_user_games = json_decode($json_user_games_raw);
$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($json_user_games), RecursiveIteratorIterator::SELF_FIRST);

$game_count = 0;

foreach ($jsonIterator as $key => $val)
{
	if($key === "appid") {
	    $appid_array[$game_count] = $val;
	    $game_count = $game_count + 1;
	}
}

// For each app id (game), call Steam API and determine if it is Linux compatible
$linux_count = 0;
$mac_count = 0;
$windowsonly_count = 0;

foreach ($appid_array as $appid)
{
    $json_gameinfo = json_decode(file_get_contents("http://store.steampowered.com/api/appdetails?appids=" . $appid));
    // If app id does not exist, skip it
    if(! $json_gameinfo->$appid->success) continue;
    $name = $json_gameinfo->$appid->data->name;
    $isLinux = $json_gameinfo->$appid->data->platforms->linux;
    $isMac = $json_gameinfo->$appid->data->platforms->mac;
    $isWindows = $json_gameinfo->$appid->data->platforms->windows;

    if($isLinux)
    {
	$linux_list[$linux_count] = $name;
	$linux_appid[$linux_count] = $appid;
	++ $linux_count;
    }
    if($isMac)
    {
	$mac_list[$mac_count] = $name;
	$mac_appid[$mac_count] = $appid;
	++ $mac_count;
    }
    if(!($isLinux || $isMac) && $isWindows)
    {
	$windowsonly_list[$windowsonly_count] = $name;
	$windowsonly_appid[$windowsonly_count] = $appid;
	++ $windowsonly_count;
    }
}

?>

<html>
<head>
<title> Compatibility Report </title>
</head>
<body>

<br>
Your Linux Compatible Games:
<br><br>

<?php
// Print a table of Linux compatible games and results
echo '<table border=1 cellpadding=2 cellspacing=2><tr><th> App ID </th><th> Game </th></tr>';

$count = 0;
foreach ($linux_list as $linux_game)
{
     echo '<tr><td>' . $linux_appid[$count] . '</td><td>' . $linux_game . '</td></tr>';
     ++ $count;
}

echo '</table>';

?>

<br><br>
Your Mac Compatible Games:
<br><br>

<?php
// Print a table of Linux incompatible games and results
echo '<table border=1 cellpadding=2 cellspacing=2><tr><th> App ID </th><th> Game </th></tr>';

$count = 0;
foreach ($mac_list as $mac_game)
{
     echo '<tr><td>' . $mac_appid[$count] . '</td><td>' . $mac_game . '</td></tr>';
     ++ $count;
}

echo '</table>';

?>


<br><br>
Your Windows-Only Games:
<br><br>

<?php
// Print a table of Linux incompatible games and results
echo '<table border=1 cellpadding=2 cellspacing=2><tr><th> App ID </th><th> Game </th></tr>';

$count = 0;
foreach ($windowsonly_list as $windowsonly_game)
{
     echo '<tr><td>' . $windowsonly_appid[$count] . '</td><td>' . $windowsonly_game . '</td></tr>';
     ++ $count;
}

echo '</table>';
?>

<br><br>
</body>
</html>


