<?php if (isset($error)) {
    foreach ($error as $key => $value) { ?>
        <div class="message errormsg"><p><?php echo $value ?></p></div><?php } } ?>
<?php if (isset($warning)) {
    foreach ($warning as $key => $value) { ?>
        <div class="message warning"><p><?php echo $value ?></p></div><?php } } ?>
<?php if (isset($info)) {
    foreach ($info as $key => $value) { ?>
        <div class="message info"><p><?php echo $value ?></p></div><?php } } ?>
<?php if (isset($success)) {
    foreach ($success as $key => $value) { ?>
        <div class="message success"><p><?php echo $value ?></p></div><?php } } ?>