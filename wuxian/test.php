<?php

$fp = @fopen("test.txt", "a+");
fwrite($fp, date("Y-m-d H:i:s") . " 成功成功了！\n");
fclose($fp);

?>