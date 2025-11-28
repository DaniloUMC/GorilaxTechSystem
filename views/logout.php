<?php
session_start();
session_destroy();

header("location:" .$BASE_URL. "../index.php");

exit();