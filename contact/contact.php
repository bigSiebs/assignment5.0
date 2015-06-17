<?php
include '../top.php';

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1: Initalize variables
//
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// 
// SECTION 1a: Variable for classroom purposes to help find errors
//

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1b: Securtity
//

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1c: Form variables
//

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1d: Form error flags
//

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1e: Misc. variables
//

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 2: Process for when the form is submitted
//

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2a: Security
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2b: Sanitize data
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2c: Validation
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2d: Process form - passed validation
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2e: Save data
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2f: Create message
    //

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2g: Mail to user
    //

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 3: Display form
//
?>


?>

<article id="main">
    <h2>Form</h2>
    
    <?php
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 3a
    //
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 3b: Error messages
    //
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 3c: HTML form: Display HTML form
    // Action is to this same page. $phpSelf is defined in top.php
    //
    ?>
    
    <form action="<?php print $phpSelf; ?>"
          method="post"
          id="frmRegister">
          
        <fieldset class="wrapper">
            <legend>Help Us Out!</legend>
            <p>Take a moment to fill out the following form. Your information is very valuable to us as we strive to continue the growth of the Old North End.</p>
            
            <fieldset class="wrapperTwo">
                <legend>Provide the your information</legend>
                
                <fieldset class="contact">
                    <legend>Contact Info</legend>
                    <label for="txtEmail" class="required">Email
                        <input type="text" id="txtEmail" name="txtEmail"
                               value=""
                               tabindex="100" maxlength="45" placeholder="Enter a valid email address"
                               onfocus="this.select()"
                               autofocus>
                    </label>
                </fieldset> <!-- end contact -->
            </fieldset> <!-- end wrapperTwo -->
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
            </fieldset> <!-- end buttons -->
        </fieldset> <!-- end wrapper -->
    </form> <!-- end form -->
</article>

<aside>

</aside>

<?php
include '../footer.php';
?>
