<h1>FizzBuzz</h1>

<p>FizzBuzz is a classic coding puzzle.<br>Here is my solution using PHP.</p>

<h2>Rules</h2>
<p>
Take the numbers 1 to N and iterate over it.<br>
By default you just output the number.<br>
If the number is divisible by 3, replace it with fizz.<br>
If the number is divisible by 5, replace it with buzz.<br>
If it's divisible by both 3 and 5, replace it with fizzbuzz.<br>
</p>


<h2>Try it out!</h2>
<form method=post action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input name=name type="text">
  <input type="submit">
</form>

<?php
if (!empty($_POST['name'])) {
  $a = (is_numeric($_POST['name']) ? (int)$_POST['name'] : $_POST['name']);
  if ($a == "ligma") {
    echo "<b>balls lmao!</b>";
  } else {
    if (gettype($a) == "integer") {
      if ($a < 1000) {
        echo "<ol>";
        for ($i = 1; $i <= $a; $i++) {
          if ($i % 3 == 0 and $i % 5 == 0) {
	    echo "<li>fizzbuzz</li>";
	  } elseif ($i % 3 == 0) {
	    echo "<li>fizz</li>";
	  } elseif ($i % 5 == 0) {
	    echo "<li>buzz</li>";
	  } else {
	    echo "<li>$i</li>";
	  }
        }
        echo "</ol>";
      } else {
	echo "<b>To save bandwidth, please stay below 1000</b>";
      } } else {
      echo "<i>pls gimme int</i>";
    }
  }
}
?>
