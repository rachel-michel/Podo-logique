@echo off
cd /d "%~dp0"

start "" "%cd%\php\php.exe" -S localhost:8000 -t public public\router.php

ping 127.0.0.1 -n 2 > nul

start "" http://localhost:8000
