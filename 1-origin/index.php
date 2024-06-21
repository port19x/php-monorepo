<h1>What kind of language do you want?</h1>
<?php
$db = new SQLite3('basic.db');

echo "<h3>strong types</h3>";
$res = $db->query('SELECT name FROM langs WHERE strong_types;');
echo "<p>sqlite query: select name from langs where strong_types;</p>";
echo "<ul>";
while ($row = $res->fetchArray()) {
	echo "<li>$row[0]</li>";
}
echo "</ul>";

echo "<h3>weak types</h3>";
$res = $db->query('SELECT name FROM langs WHERE not strong_types;');
echo "<p>sqlite query: select name from langs where not strong_types;</p>";
echo "<ul>";
while ($row = $res->fetchArray()) {
	echo "<li>$row[0]</li>";
}
echo "</ul>";

echo "<h3>static types</h3>";
$res = $db->query('SELECT name FROM langs WHERE static_types;');
echo "<p>sqlite query: select name from langs where static_types;</p>";
echo "<ul>";
while ($row = $res->fetchArray()) {
	echo "<li>$row[0]</li>";
}
echo "</ul>";

echo "<h3>dynamic types</h3>";
$res = $db->query('SELECT name FROM langs WHERE not static_types;');
echo "<p>sqlite query: select name from langs where not static_types;</p>";
echo "<ul>";
while ($row = $res->fetchArray()) {
	echo "<li>$row[0]</li>";
}
echo "</ul>";


echo "<h2>sqlite version</h2>";

$ver = SQLite3::version();

echo $ver['versionString'] . "\n";
echo $ver['versionNumber'] . "\n";

var_dump($ver);
?>
