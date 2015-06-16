<script  type= "text/javascript"  src= "//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script>
<script  src= "js/jquery.flexslider.js" ></script>
<script  type= "text/javascript" >
    var flexsliderStylesLocation = "css/flexslider.css";
    $('<link rel="stylesheet" type="text/css" href="' + flexsliderStylesLocation + '" >').appendTo("head");
    $(window).load(function () {
        $('.flexslider').flexslider({
            animation: "fade",
            slideshowSpeed: 3000,
            animationSpeed: 1000
        });
    });
</script>