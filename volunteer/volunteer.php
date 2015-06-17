<?php
include '../top.php';
?>

<article id='main'>
    <h2>Get Involved!</h2>
    <p>There are many volunteer opportunities in the Old North End. No matter how you'd like to spend your time volunteering or who you'd like to help in your efforts, there's likely some organization in the area that offers an opportunity that will appeal to you. </p>
    <p>To name one, there's the Chittenden Emergency Food Shelf. The ONE's Food Shelf is the largest direct-service emergency food provider in the state. They're partnered with many other local organizations, and they're always looking for volunteers to help with day-to-day operations. For instance, you could help unload food donations when they're received, stock the distribution shelves, or prep and serve food in the soup kitchen. And those are only a few of the things that they're looking to have their volunteers to do. Check out their <a href="http://www.feedingchittenden.org/volunteer/" target="_blank">website</a> to see other opportunities.</p>
    <p>And again, that's only one place you might lend a hand. There are several others, some of which are identified in the table below. This, of course, is only a partial list. If you know of other places looking for volunteers, head over the to the <a href=../contact/contact.php>Contact</a> page and let us know!</p>
    <table>
        <caption>Volunteer Opportunities in the Old North End, Burlington, VT</caption>
        <?php
        
        $debug = false;
        if (isset($_GET["debug"])) {
            $debug = true;
        }
        
        $myFilename = "opportunities";
        $fileExt = ".csv";
        $filename = $myFilename . $fileExt;
        
        if ($debug) print "\n\n<p>Filename is " . $filename . ".</p>\n";
        
        $file = fopen('data/' . $filename, 'r');
        
        if ($file) {
            if ($debug) { 
                print "<p>File opened.</p>\n";
                print "<p>Begin reading data into an array.</p>\n";
            }
            
            // Read headings into array
            $headers = fgetcsv($file);
            
            if ($debug) {
                print "<p>Finished reading headings.</p>\n";
                print "<p>My heading array:<p><pre>";
                print_r($headers);
                print "</pre></p></p>";
            }
            
            // Read data using while loop
            while (!feof($file)) {
                $records[] = fgetcsv($file);
            }
            
            // Close file
            fclose($file);
            
            if ($debug) {
                print "<p>Finished reading data.</p>\n";
                print "<My data arrays:<p><pre>";
                print_r($records);
                print "</pre></p></p>";
            }    
        } // Ends if file opened
        
        // Print headings as <tr>, <th>
        print "\n\t<tr>";
        foreach ($headers as $header) {
            print "\n\t\t" . '<th scope="col">' . $header . "</th>";
        }
        print "\n\t</tr>";
        
        // Print table body
        foreach ($records as $record) {
            if ($record[0] != "") {
                print "\n\t<tr>";
                print "\n\t\t" . '<th scope="row">' . $record[0] . "</th>";
                print "\n\t\t<td>" . $record[1] . "</td>";
                print "\n\t\t<td>" . '<a href="' . $record[2] . '" target="_blank">Website</a></td>';
                print "\n\t</tr>";
            }
        }
        ?>
    </table>
</article>

<aside>
    
</aside>

<?php
include '../footer.php';
?>
