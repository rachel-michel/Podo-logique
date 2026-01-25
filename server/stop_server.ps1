$processes = Get-Process php -ErrorAction SilentlyContinue | Where-Object {
    $_.Path -like "*\php\php.exe"
}

if ($processes) {
    $processes | Stop-Process -Force
}

Add-Type -AssemblyName PresentationFramework
[System.Windows.MessageBox]::Show(
    "The application has been successfully stopped",
    "Stop server",
    "OK",
    "Information"
)
