<?php
session_start();
session_unset();
session_destroy();

header("Location: " . BASE_URL . "index.php?page=homePage");
exit;
