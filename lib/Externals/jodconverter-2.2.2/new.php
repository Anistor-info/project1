<?php
// Some servers may have an auto timeout, so take as long as you want.
set_time_limit(0);

// Show all errors, warnings and notices whilst developing.
error_reporting(E_ALL);
 $time_start = (float) array_sum(explode(' ',microtime())); 
echo exec("java -jar lib/jodconverter-cli-2.2.2.jar C:/wamp/www/test_script/ClassificationSystems.ppt C:/wamp/www/test_script/abc.pdf");
//echo exec("java -jar lib/jodconverter-cli-2.2.2.jar -f pdf *.doc");
//java -jar lib/jodconverter-cli-2.2.0.jar document.doc document.pdf
$time_end = (float) array_sum(explode(' ',microtime()));
 
echo "Processing time: ". sprintf("%.4f", ($time_end-$time_start))." seconds"; 
exit;
?>