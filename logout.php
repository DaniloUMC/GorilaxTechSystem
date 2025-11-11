<?php
session_start();
header("location:" .$BASE_URL. "../index.php");
session_destroy();


exit();