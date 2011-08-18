<?php
// This theme is used to show subscription messages to users along the various
// subscription and unsubscription steps.
// This theme is used to show feed by mail and follow up unsubscription messages too.
//
// The theme is used ONLY IF, on main configutation, you have NOT set a specific
// WordPress page to be used to show messages.
//
// The theme MUST contain the {message} place holder.

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html40/strict.dtd">
<html>
    <head>
        <style type="text/css">
            body {
                font-family: verdana;
            }
        </style>
    </head>

    <body style="background-color: #333">
        <div style="margin: 10px auto 0 auto; width: 500px; border: 1px solid #000; background-color: #fff; padding: 10px">
            <h3><?php echo get_option('blogname'); ?></h3>
            {message}
        </div>
    </body>
</html>