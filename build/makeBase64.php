<?php

$dirHandle = dirname(__DIR__).'/small-logo';
$smallLogoList = [];
$filename = dirname(__DIR__).'/bank-logo-base64/SmallLogo.php';

if ($handle = opendir($dirHandle)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $shortCode = explode('.', $file)[0];
            $image = file_get_contents($dirHandle.'/'.$file);
            $content = 'data:image/png;base64,'.base64_encode($image);
            if (!file_exists($filename)) {
                file_put_contents($filename, "<?php\rreturn array(\r");
            } else {
                $row = "\t"."'".$shortCode."'".' => '."'".$content."',"."\r";
                file_put_contents($filename,  $row, FILE_APPEND);
            }
        }
    }
    // eof
    file_put_contents($filename,  "\r);", FILE_APPEND);

    closedir($handle);
}

echo "Done.";
