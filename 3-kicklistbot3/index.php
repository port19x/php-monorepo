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

<form method=post action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="clantag">Clan Tag:</label>
  <input id="clantag" name="clantag" type="text" placeholder="e.g. JGLJR8L">
  <input type="submit" value="Go">
</form>

<?php
if (!empty($_POST['clantag'])) {
  $clantag = $_POST['clantag'];
  $token = file_get_contents("token");
  $opts = array('http'=>array('method'=>"GET", 'header'=>"Authorization: Bearer $token"));
  $context = stream_context_create($opts);
  $bkindom = json_decode(file_get_contents("https://api.clashroyale.com/v1/clans/%23$clantag", false, $context));
  $bkindom2 = json_decode(file_get_contents("https://api.clashroyale.com/v1/clans/%23$clantag/currentriverrace", false, $context));

  $count = $bkindom->members;
  if ($count < 45) {
    echo "<p>you have <b style='color:red'>$count</b> members</p>";
  } else if ($count < 48) {
    echo "<p>you have <b style='color:yellow'>$count</b> members</p>";
  } else if ($count >48) {
    echo "<p>you have <b style='color:green'>$count</b> members</p>";
  }

  $db = new SQLite3('clan.db');
  $db->query('DELETE FROM members;');
  $members = $bkindom->memberList;
  foreach ($members as $member) {
    $db->query("INSERT INTO members VALUES ('$member->name', '$member->role', $member->donations, 11);");
  }
  $members = $bkindom2->clan->participants;
  foreach ($members as $member) {
    $db->query("UPDATE members SET decks = $member->decksUsed WHERE name = '$member->name';");
  }

  echo "<h3>0 Donations, 0 Wardecks</h3>";
  $res = $db->query("SELECT name FROM members WHERE decks = 0 AND donations = 0 AND role = 'member';");
  $a = "<p>";
  while ($row = $res->fetchArray()){
    $a .= "$row[0]<br>";
  }
  $a .= "</p>";
  echo $a;

  echo "<h3>0 Donations, X Wardecks</h3>";
  $res = $db->query("SELECT name FROM members WHERE decks > 0 AND donations = 0 AND role = 'member';");
  $a = "<p>";
  while ($row = $res->fetchArray()){
  $a .= "$row[0]<br>";
  }
  $a .= "</p>";
  echo $a;

  echo "<h3>X Donations, 0 Wardecks</h3>";
  $res = $db->query("SELECT name FROM members WHERE decks = 0 AND donations > 0 AND role = 'member';");
  $a = "<p>";
  while ($row = $res->fetchArray()){
    $a .= "$row[0]<br>";
  }
  $a .= "</p>";
  echo $a;
}
?>

</body>
</html>
