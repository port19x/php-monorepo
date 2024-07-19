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
  table, td, th {
    margin-left: auto;
    margin-right: auto;
    border: 1px solid;
    border-collapse: collapse;
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
    $dict[$member->name] = ["name" => $member->name, "don" => $member->donations, "war" => 0];
  }
  foreach ($members2 as $member) {
    //a member being in the war list, not in the donations list, indicates that the member has left
    if (array_key_exists($member->name, $dict)) {
      if (array_key_exists("don", $dict[$member->name])) {
        $dict[$member->name]["war"] = $member->decksUsed;
      }
    }
  }

  function cmp($a, $b) {
    $x =  $a["war"] <=> $b["war"];
    if ($x == 0) {
      $x =  $a["don"] <=> $b["don"];
    }
    return $x;
  }
  usort($dict, 'cmp');

  $a =  "<table>";
  $a .= "<tr><th>WarDecks</th><th>Donations</th><th>Name</th></tr>";
  foreach ($dict as $name => $member){
    $a .= "<tr><td>";
    $a .= $member["war"];
    $a .= "</td><td>";
    $a .= $member["don"];
    $a .= "</td><td>";
    $a .= $member["name"];
    $a .= "</td>";
  }
  $a .= "</table>";
  echo $a;
  //echo microtime(true) - $now;
}
?>

</body>
</html>
