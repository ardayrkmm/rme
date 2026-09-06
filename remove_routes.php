<?php
$f = 'e:/Bisnis/RekamMedis/backend_rekammedis/routes/api.php';
$c = file_get_contents($f);
$c = preg_replace('/^\s*\/\/\s*Exercise Programs.*?(^\s*\/\/\s*Reports)/ms', '$1', $c);
file_put_contents($f, $c);
echo "Routes removed\n";
