<?php

$level = $this->options_main['editor'] ? 7 : 10;

add_menu_page('Newsletter Pro', 'Newsletter Pro', $level, 'newsletter-pro/intro.php', '', '');
add_submenu_page('newsletter-pro/intro.php', 'User Guide', 'User Guide', $level, 'newsletter-pro/intro.php');

add_submenu_page('newsletter-pro/intro.php', 'Main Configuration', 'Main Configuration', $level, 'newsletter-pro/main.php');
add_submenu_page('newsletter-pro/intro.php', 'Subscription Process', 'Subscription Process', $level, 'newsletter-pro/options.php');
add_submenu_page('newsletter-pro/intro.php', 'Subscription Form', 'Subscription Form', $level, 'newsletter-pro/profile.php');

add_submenu_page('newsletter-pro/intro.php', 'Emails', 'Emails', $level, 'newsletter-pro/emails.php');
add_submenu_page('newsletter-pro/emails.php', 'Email Edit', 'Email Edit', $level, 'newsletter-pro/emails-edit.php');
add_submenu_page('newsletter-pro/emails.php', 'Email Statistics', 'Email Stats', $level, 'newsletter-pro/emails-stats.php');

add_submenu_page('newsletter-pro/intro.php', 'Subscribers', 'Subscribers', $level, 'newsletter-pro/users.php');
add_submenu_page('newsletter-pro/users.php', 'Subscribers Edit', 'Subscribers Edit', $level, 'newsletter-pro/users-edit.php');
add_submenu_page('newsletter-pro/users.php', 'Subscribers Statistics', 'Subscribers Statistics', $level, 'newsletter-pro/users-stats.php');

add_submenu_page('newsletter-pro/intro.php', 'Feed by mail', 'Feed by mail', $level, 'newsletter-pro/feed.php');
add_submenu_page('newsletter-pro/feed.php', 'Feed Email Edit', 'Feed Email Edit', $level, 'newsletter-pro/feed-edit.php');

add_submenu_page('newsletter-pro/intro.php', 'Follow Up', 'Follow Up', $level, 'newsletter-pro/followup.php');
add_submenu_page('newsletter-pro/intro.php', 'Import/Export', 'Import/Export', $level, 'newsletter-pro/import.php');
add_submenu_page('newsletter-pro/intro.php', 'Forms', 'Forms', $level, 'newsletter-pro/forms.php');
add_submenu_page('newsletter-pro/intro.php', 'Themes', 'Themes', $level, 'newsletter-pro/themes.php');


add_submenu_page('newsletter-pro/intro.php', 'Statistics', 'Statistics', $level, 'newsletter-pro/statistics/statistics.php');
add_submenu_page('newsletter-pro/statistics/statistics.php', 'Statistics Clicks', 'Statistics Clicks', $level, 'newsletter-pro/statistics/statistics-clicks.php');
add_submenu_page('newsletter-pro/statistics/statistics.php', 'Statistics Profiles', 'Statistics Profiles', $level, 'newsletter-pro/statistics/statistics-profiles.php');
add_submenu_page('newsletter-pro/statistics/statistics.php', 'Statistics Users', 'Statistics Users', $level, 'newsletter-pro/statistics/statistics-users.php');

?>
