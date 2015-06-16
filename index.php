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

<article>
    <h2></h2>
    <p></p>
    
</article>

<?php
include "footer.php";
?>