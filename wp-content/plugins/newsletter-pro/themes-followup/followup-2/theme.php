<?php
global $newsletter;
?>
<table width="500">
    <tr>
        <td>

            {message}

            <?php if ($newsletter->user->sex == "f") { ?>
                <!-- fixed message for women -->
            <?php } ?>

            <?php if ($newsletter->user->sex == "m") { ?>
                <!-- fixed message for man -->
            <?php } ?>

            <?php echo $labels['end']; ?>
            <?php echo $labels['unsubscription']; ?>

        </td>
    </tr>
</table>
