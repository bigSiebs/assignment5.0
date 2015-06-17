<?php
include '../top.php';
?>

<article id="main">
    <h2>Grab a Bite!</h2>
    
    <?php
    $debug = false;
    if (isset($_GET["debug"])) {
        $debug = true;
    }

    $myFilename = "restaurant-profiles";
    $fileExt = ".csv";
    $filename = $myFilename . $fileExt;

    if ($debug)
        print "\n\n<p>Filename is " . $filename . ".</p>";

    $file = fopen("data/" . $filename, 'r');

    if ($file) {
        if ($debug) {
            print "<p>File opened.</p>\n";
            print "<p>Being reading data into an array.</p>\n";
        }

        // Read file headers
        $headers = fgetcsv($file);

        if ($debug) {
            print "<p>Finished reading headers.</p>\n";
            print "<p>My header array:<p><pre>";
            print_r($headers);
            print "</pre></p></p>";
        }

        // Read data
        while (!feof($file)) {
            $records[] = fgetcsv($file);
        }

        // Close file
        fclose($file);

        if ($debug) {
            print "<p>Finished reading data. Filed closed.</p>\n";
            print "<p>My data array:<p><pre>";
            print_r($records);
            print "</pre></p></p>";
        }
    } // Ends if file opened statement

    foreach ($records as $section) {
        if ($section != "") {
            print '<section class="profile">';
            print "\n\t<h3>" . $section[0] . "</h3>\n";
            print '<figure class="fig-med">';
            print '<img src="images/' . $section[1] . '.jpg" alt="" />' . "\n";
            print '<figcaption>Image credit: ';
            print '<a href="' . $section[2] . '" target="_blank">' . $section[3] . '</a>.<br />' . "\n";
            print '<address>' . $section[4] . "<br />\n";
            print $section[5] . "<br />\n";
            print $section[6] . "<br />\n";
            print $section[8] . "<br />\n";
            print '<a href="' . $section[9] . '" target="_blank">Yelp</a>';
            print "</address></figcaption></figure>\n";
            print '<p>From their <a href="' . $section[7] . '" target="_blank">website</a>: ' . $section[10] . "</p>\n";
            print "</section>";
        }
    }
        ?>

</article>

<aside>
    
</aside>

<?php
include '../footer.php';
?>
