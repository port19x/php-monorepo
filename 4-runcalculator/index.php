<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Language" content="en">
        <meta name="author" content="port19">
        <meta name="generator" content="php">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Port19s No BS Running Calculator</title>
        <meta name="description" content="Fuck The SEO Grifters">
    </head>

    <style>
     th, td       { border: 1px solid; }
     thead, th    { border-bottom-style: double; }
     table        { border-collapse: collapse; }
    </style>

    <body>
        <p><i>You may leave empty what you don't need</i></p>

        <form id="maxhr" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="age"><i>(Max HR)</i> Age:</label>
            <input id="age" name="age" type="text" placeholder="e.g. 23">
            <br>
            <label for="rhr"><i>(Zones)</i> Resting Heart Rate:</label>
            <input id="rhr" name="rhr" type="text" placeholder="e.g. 56">
            <br>
            <label for="mhr"><i>(Zones)</i> Custom Max HR:</label>
            <input id="mhr" name="mhr" type="text" placeholder="e.g. 188">
            <br>
            <label for="km"><i>(Conversion)</i>Distance in KM:</label>
            <input id="km" name="km" type="text" placeholder="e.g. 10">
            <br>
            <label for="mi"><i>(Conversion)</i>Distance in Miles:</label>
            <input id="mi" name="mi" type="text" placeholder="e.g. 10">
            <br>
            <label for="mkm"><i>(Conversion)</i>Pace in Min/KM:</label>
            <input id="mkm" name="mkm" type="text" placeholder="e.g. 10">
            <br>
            <label for="mmi"><i>(Conversion)</i>Pace in Min/Mile:</label>
            <input id="mmi" name="mmi" type="text" placeholder="e.g. 10">
            <br>
            <input type="submit" value="Go" onmousedown="document.getElementById('maxhr').submit();">
        </form>

        <?php
        error_reporting(E_ALL & ~E_DEPRECATED);
        if (!empty($_GET['age'])) {
            echo "<h2>Max Heart Rate</h2>";
            echo "<p>These formulas are certainly better than 220-age lol.<br>";
            echo "See also: https://en.wikipedia.org/wiki/Heart_rate#Maximum_heart_rate</p>";
            echo "<h3>Tanaka, Monahan & Seals: Max HR = 208 - (0.7 * age)</h2>";
            $maxhr = 208 - (0.7 * $_GET['age']);
            echo "<b>Your Max HR: $maxhr</b>";
            echo "<h3>Oakland non-linear formula: Max HR = 192 - (0.007 × age²)</h2>";
            $maxhr = 192 - 0.007 * $_GET['age'] * $_GET['age'];
            echo "<b>Your Max HR: $maxhr</b>";
            echo "<p>Nothing beats your observed Max HR at a serious 5k race tho. Use custom Max HR for that.</p>";
        }
        if (!empty($_GET['rhr']) && (!empty($_GET['age']) || !empty($_GET['mhr']))) {
            echo "<h2>HR Zones (Karvonen Formula)</h2>";
            echo "<p>This formula is more accurate than a purely max-hr based one, because it takes your resting heart rate into account.</p>";
            if (!empty($_GET['mhr'])) {
                $maxhr = $_GET['mhr'];
            }
            $rhr = $_GET['rhr'];
            $hrr = $maxhr - $rhr;
            $z1 = floor($hrr * 0.5 + $rhr);
            $z2 = floor($hrr * 0.6 + $rhr);
            $z3 = floor($hrr * 0.7 + $rhr);
            $z4 = floor($hrr * 0.8 + $rhr);
            $z5 = floor($hrr * 0.9 + $rhr);
            $maxhr = floor($maxhr);
            echo "<table><thead><tr><th>Zone</th><th>Lower Bound</th><th>Upper Bound</th></tr></thead><tbody>";
            echo "<tr><td>1</td><td>$z1</td><td>$z2</td></tr>";
            echo "<tr><td>2</td><td>$z2</td><td>$z3</td></tr>";
            echo "<tr><td>3</td><td>$z3</td><td>$z4</td></tr>";
            echo "<tr><td>4</td><td>$z4</td><td>$z5</td></tr>";
            echo "<tr><td>5</td><td>$z5</td><td>$maxhr</td></tr>";
            echo "</tbody></table>";
        }
        if (!empty($_GET['mi']) || !empty($_GET['km']) || !empty($_GET['mkm']) || !empty($_GET['mmi'])) {
            echo "<h2>Conversions</h2>";
        }
        if (!empty($_GET['mi']) || !empty($_GET['mmi'])) {
            echo "<h3>Freedom units to metric: 1 Mile = 1.609 Kilometers</h3>";
            if (!empty($_GET['mi'])) {
                $mii = $_GET['mi'];
                $kmo = $mii * 1.609;
                echo "<b>$mii Miles = $kmo Kilometers</b>";
            }
            if (!empty($_GET['mmi'])) {
                $mmi = $_GET['mmi'];
                $kmo = $mmi / 1.609;
                $fivek = $kmo * 5;
                $tenk = $kmo * 10;
                $hmh = floor(($kmo * 21.0975) / 60);
                $hmm = floor(($kmo * 21.0975) % 60);
                $marah = floor(($kmo * 42.195) / 60);
                $maram = floor(($kmo * 42.195) % 60);
                echo "<b>$mmi Min/Mile = $kmo Min/KM</b>";
                echo "<br>";
                echo "<ul><li>$fivek Minute 5k</li><li>$tenk Minute 10k</li>";
                echo "<li>$hmh Hour and $hmm Minute Half Marathon</li>";
                echo "<li>$marah Hour and $maram Minute Marathon</li></ul>";
            }
        }
        if (!empty($_GET['km']) || !empty($_GET['mkm'])) {
            echo "<h3>Metric to Freedom Units: 1 Kilometer = 0.621 Miles</h3>";
            if (!empty($_GET['km'])) {
                $kmi = $_GET['km'];
                $mio = $kmi * 0.621;
                echo "<b>$kmi Kilometers = $mio Miles</b>";
            }
            if (!empty($_GET['mkm'])) {
                $mkm = $_GET['mkm'];
                $mio = $mkm / 0.621;
                $fivek = $mkm * 5;
                $tenk = $mkm * 10;
                $hmh = floor(($mkm * 21.0975) / 60);
                $hmm = floor(($mkm * 21.0975) % 60);
                $marah = floor(($mkm * 42.195) / 60);
                $maram = floor(($mkm * 42.195) % 60);
                echo "<b>$mkm Min/KM = $mio MIn/Mile</b>";
                echo "<br>";
                echo "<ul><li>$fivek Minute 5k</li><li>$tenk Minute 10k</li>";
                echo "<li>$hmh Hour and $hmm Minute Half Marathon</li>";
                echo "<li>$marah Hour and $maram Minute Marathon</li></ul>";
            }
        }
        ?>
    </body>
</html>
