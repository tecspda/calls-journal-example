<?php

// загружаем настройки
require_once('./config.php');

// загружаем шапку сайта
require(VIEWS.'/view_header.php');

// загружаем страницу с операторами
require(VIEWS.'/view_users.php');

// загружаем шапку сайта
require(VIEWS.'/view_footer.php');

?>