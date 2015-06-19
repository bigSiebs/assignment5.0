<?php
include "top.php";
?>

<div class="flexslider">
    <ul class="slides">
        <?php 
        // Array for slider slides
        $slides = array("one-slide1", "one-slide2", "one-slide3");
        
        // Print list code
        foreach ($slides as $slide) {
            print '<li>' . "\n";
            print '<img src="images/' . $slide . '.jpg" alt="" />' . "\n";
            print '</li>' . "\n";
        }
        ?>
    </ul>
</div>
<p class="credit-line">Slideshow images courtesy of <a href="http://bodhidharmaseyes.com/2011/08/19/late-august-in-the-old-north-end/" target="_blank">David Luekens</a>.</p>
<hr />
<article>
    <h2><i>The Old North End is...</i></h2>
    <p>...a neighborhood in Burlington, VT. Simply put, it's unlike any other part of Burlington, or even Vermont, and it's becoming more vibrant and unique by the day. The ONE, as it's known, is the most racially diverse area in the state, and it's a community-oriented area filled with tight-knit families and an active citizenry. More and more businesses, restaurants, and other organizations are moving into the area.</p>
    <p>This site, run by the nonprofit group Grow ONE, identifies the various ways in which Burlington residents and visitors can experience this unique neighborhood. Below, there are links to several pages that detail the reasons that you should visit the ONE as soon as possible! If you hover over the images, the images will change to indicate what pages they link to. If you're looking for a specific page, scroll up to the menu bar at the top of the page for more direct navigation.</p>
    <hr />
    <div class="image-grid">
        <?php
        $debug = false;
        if (isset($_GET["debug"])) {
            $debug = true;
        }

        // Open image-links CSV file

        $myFilename = "image-links";
        $fileExt = ".csv";
        $filename = $myFilename . $fileExt;

        if ($debug)
            print "\n\n<p>Filename is" . $filename;

        $file = fopen("data/" . $filename, 'r');

        //If the variable $file is empty, it will be false
        if ($file) {
            if ($debug) {
                print "<p>File opened.</p>\n";
                print "<p>Begin reading data into an array.</p>\n";
            }

            // Read headers
            $headers = fgetcsv($file);

            if ($debug) {
                print "<p>Finished reading headers.</p>\n";
                print "<p>My header array:<p><pre>";
                print_r($headers);
                print "</pre></p></p>";
            }

            // Read remaining data
            while (!feof($file)) {
                $records[] = fgetcsv($file);
            }

            // Close file
            fclose($file);

            if ($debug) {
                print "<p>Finished reading data. Filed close.</p>\n";
                print "<p>My data array:<p><pre>";
                print_r($records);
                print "</pre></p></p>";
            }
        } // ends if file was opened
        // Initalize variable for class numbers
        $classNumber = 1;

        // Display data
        print "<ol>";

        foreach ($records as $record) {
            if ($record[0] != "") {
                print "\n\t<li>";

                // Print out values
                print '<figure class="fig-med" id="image-' . strval($classNumber) . '">';

                print '<a href="' . $record[1] . '">';
                print '<img src="images/' . $record[2] . '.jpg"';
                // JavaScript for hover images
                print ' onmouseover="' . "this.src='images/" . $record[3] . ".jpg'" . '"';
                print ' onmouseout="' . "this.src='images/" . $record[2] . ".jpg'" . '"';
                print ' alt="" />';
                print '</a>';

                print '<figcaption>';
                print '</figcaption>';

                print '</figure>';

                print "\n\t</li>";

                $classNumber += 1;
            }
        }

        print '</ol>';

        if ($debug) {
            print "<p>End of processing.</p>\n";
        }
        ?>  
    </div>
    
    <?php
    print "\n\n\t" . '<p class="credit-line">Images from left to right, top to bottom courtesy of ';
    
    // variable to count iterations
    $count = 0;
    
    foreach ($records as $record) {
        print '<a href="' . $record[4] . '">' . $record[5] . '</a>';
        $count+= 1;
        
        if ($count == 6) {
            print '.';
        } elseif ($count == 5) {
            print ', and ';
        } else {
            print ', ';
        }
    }
    print '</p>';
    ?>
</article>

<?php
include "footer.php";
?>
