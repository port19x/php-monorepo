<!DOCTYPE html>
<html lang="en">

<head>
  <title>CRKicklists</title>
  <meta charset="utf-8">
  <meta http-equiv="Content-Language" content="en">
  <meta name="description" content="Clash Royale kicklist generator for ambitious clans">
  <meta name="keywords" content="Clash Royale, stats, analytics, decks, esports, API, chests, RoyaleAPI, statistics, meta, best, cards, pro">
  <meta name="author" content="port19">
  <meta name="generator" content="php">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
</head>

<style>
body {
  text-align: center;
}
</style>

<body>
<h1>CRKicklists</h1>

<form id="fastForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="clantag">Clan Tag:</label>
  <input id="clantag" name="clantag" type="text" placeholder="e.g. JGLJR8L">
  <input type="submit" value="Go" onmousedown="document.getElementById('fastForm').submit();">
</form>

<?php
//$now = microtime(true);

if (!empty($_POST['clantag'])) {
  $clantag = $_POST['clantag'];
  $token = file_get_contents("token");
  $crl = curl_init();
  curl_setopt($crl, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token"));
  curl_setopt($crl, CURLOPT_USERAGENT, "CRKicklists v0.0.3");
  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($crl, CURLOPT_URL, "https://api.clashroyale.com/v1/clans/%23$clantag");
  $bkindom = json_decode(curl_exec($crl));
  //echo microtime(true) - $now;
  curl_setopt($crl, CURLOPT_URL, "https://api.clashroyale.com/v1/clans/%23$clantag/currentriverrace");
  $bkindom2 = json_decode(curl_exec($crl));
  //echo microtime(true) - $now;
  curl_close($crl);

  $count = $bkindom->members;
  if ($count < 45) {
    echo "<p>you have <b style='color:red'>$count</b> members</p>";
  } else if ($count < 48) {
    echo "<p>you have <b style='color:yellow'>$count</b> members</p>";
  } else if ($count >48) {
    echo "<p>you have <b style='color:green'>$count</b> members</p>";
  }

  $members = $bkindom->memberList;
  $donations_zero = array();
  for ($i = 0; $i < count($members); $i++) {
    if ($members[$i]->donations <= 0) {
      $donations_zero[] = $members[$i]->name;
    }
  }

  $members2 = $bkindom2->clan->participants;
  $war_zero = array();
  for ($i = 0; $i < count($members2); $i++) {
    if ($members2[$i]->decksUsed <= 0) {
      $war_zero[] = $members2[$i]->name;
    }
  }

  $double_zero = array_values(array_intersect($donations_zero, $war_zero));
  $donations_zero = array_values(array_diff($donations_zero, $double_zero));
  $war_zero = array_values(array_diff($war_zero, $double_zero));

  echo "<h3>0 Donations, 0 Wardecks</h3>";
  $a = "<p>";
  foreach ($double_zero as $row){
    $a .= "$row<br>";
  }
  $a .= "</p>";
  echo $a;

  echo "<h3>0 Donations, X Wardecks</h3>";
  $a = "<p>";
  foreach ($donations_zero as $row){
    $a .= "$row<br>";
  }
  $a .= "</p>";
  echo $a;

  echo "<h3>X Donations, 0 Wardecks</h3>";
  $a = "<p>";
  foreach ($war_zero as $row){
    $a .= "$row<br>";
  }
  $a .= "</p>";
  echo $a;
  //echo microtime(true) - $now;
}
?>

</body>
</html>
