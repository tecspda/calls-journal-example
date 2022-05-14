<?php

// загружаем настройки
require_once('./config.php');

// загружаем шапку сайта
require(VIEWS.'/view_header.php');

// загружаем страницу с журналом
require(VIEWS.'/view_calls.php');
?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php
// загружаем шапку сайта
require(VIEWS.'/view_footer.php');

?>