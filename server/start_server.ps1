Start-Process php\php.exe "-S localhost:8000 -t public public\router.php" -WindowStyle Hidden
Start-Sleep -Milliseconds 800
Start-Process http://localhost:8000
