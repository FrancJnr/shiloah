echo off
"D:\xampp\php\php.exe" -f D:\xamp\htdocs\shiloahmega\weekly_updates\expiry_list1.php

::start "email reminder task" "C:\Program Files (x86)\PHP\v5.3\php.exe" -f C:\inetpub\wwwroot\sitename\crons\reminder-email.php
::"C:\Program Files (x86)\PHP\v5.3\php.exe" -f C:\inetpub\wwwroot\sitename\crons\reminder-email.php


::pause