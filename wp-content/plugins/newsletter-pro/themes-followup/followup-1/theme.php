<?php
// This theme is used to compose follow up emails creating a unique design where
// single follow up messages are inserted (in place of the required {message} tag).

// The newsletter variable gives access to some important variables, like the
//
// $newsletter->user
//
// which contains the current email receiver.
// You can add specific texts based on user data, like sex, checking the specific
// field: $newsletter->user->sex it assumes the values m, f, n.

global $newsletter;

// The labels variable is created by newsletter before calling this theme including
// the en_US.php file and merging it with language specific file according to
// the constant WPLANG defined on wp-config.php.

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html40/strict.dtd">
<html>
<head></head>
<body>

<?php echo $labels['start']; ?>

{message}

<?php echo $labels['end']; ?>

</body>
</html>