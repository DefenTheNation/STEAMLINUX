
<?php

include("CONFIG");

$userid_raw = $_POST["steam_userid"];
$userid = htmlspecialchars($userid_raw);

if(!($json_user_games_raw = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $steamkey . "&steamid=" . $userid . "&format=json"))) {
    echo 'Oops! No steam user found for this id!<br>';
    echo 'ID: ' . $userid . '<br>';
    echo '<a href="home.html">Return to Home Page</a>';
}
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

$linux_count = 0;
$nonlinux_count = 0;

foreach ($appid_array as $appid)
{
    $json_gameinfo = json_decode(file_get_contents("http://store.steampowered.com/api/appdetails?appids=" . $appid));     
    $name = $json_gameinfo->$appid->data->name;
    $isLinux = $json_gameinfo->$appid->data->platforms->linux;

    if($isLinux)
    {
	$linux_list[$linux_count] = $name;
	++ $linux_count;
    }
    else
    {
	$nonlinux_list[$nonlinux_count] = $name;
	++ $nonlinux_count;
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

echo '<table border=1 cellpadding=2 cellspacing=2><tr><th> Count </th><th> Game </th></tr>';

$count = 0;
foreach ($linux_list as $linux_game)
{
     echo '<tr><td>' . $count . '</td><td>' . $linux_game . '</td></tr>';
     ++ $count;
}

echo '</table>';

?>

<br><br>
Your Linux INcompatible Games:
<br><br>

<?php

echo '<table border=1 cellpadding=2 cellspacing=2><tr><th> Count </th><th> Game </th></tr>';

$count = 0;
foreach ($nonlinux_list as $nonlinux_game)
{
     echo '<tr><td>' . $count . '</td><td>' . $nonlinux_game . '</td></tr>';
     ++ $count;
}

echo '</table>';

?>


<br><br>
</body>
</html>


