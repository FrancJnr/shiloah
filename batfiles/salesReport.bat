echo off
REM This adds the folder containing php.exe to the path
PATH=%PATH%;D:\xampp\php

REM Change Directory to the folder containing your script
CD D:\xampp\htdocs

REM Execute
php salesReport.php

::pause
