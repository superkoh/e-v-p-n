<?php
echo password_hash("wlt19860718@", 1, ['cost' => 13]);

var_dump(password_verify("wlt19860718@", '$2y$13$40NmeK5JW5SmDLkaX.lKIuvqaQ71SGkon6yqKwrYBoiUnB9VmTnfm'));
//echo phpinfo();