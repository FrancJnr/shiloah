echo off
REM This adds the folder containing php.exe to the path
PATH=%PATH%;E:\xampp\php

REM Change Directory to the folder containing your script
CD E:\xampp\htdocs\shiloahmega

REM Execute
php salesReport.php

::pause

