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

<form id="fastForm" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="clantag">Clan Tag:</label>
  <input id="clantag" name="clantag" type="text" placeholder="e.g. JGLJR8L">
  <input type="submit" value="Go" onmousedown="document.getElementById('fastForm').submit();">
</form>

<?php
//$now = microtime(true);

if (!empty($_GET['clantag'])) {
  $clantag = $_GET['clantag'];
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
  } else if ($count >= 48) {
    echo "<p>you have <b style='color:green'>$count</b> members</p>";
  }

  $dict = array();
  $members = $bkindom->memberList;
  $members2 = $bkindom2->clan->participants;
  foreach ($members as $member) {
    $dict[$member->name] = [ "don" => $member->donations, "war" => 0];
  }
  foreach ($members2 as $member) {
    //a member being in the war list, not in the donations list, indicates that the member has left
    if (array_key_exists($member->name, $dict)) {
      if (array_key_exists("don", $dict[$member->name])) {
        $dict[$member->name]["war"] = $member->decksUsed;
      }
    }
  }

  $donations_zero = array();
  $war_zero = array();
  $double_zero = array();

  foreach ($dict as $name => $member) {
    if ($member["don"] <= 0 and $member["war"] <= 0) {
      $double_zero[] = $name;
    } else if ($member["don"] <= 0) {
      $donations_zero[] = $name;
    } else if ($member["war"] <= 0) {
      $war_zero[] = $name;
    }
  }

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
