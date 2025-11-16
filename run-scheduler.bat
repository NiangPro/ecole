@echo off
REM Script pour exécuter le scheduler Laravel sur Windows
REM À utiliser avec Task Scheduler Windows pour exécuter toutes les minutes

cd /d "%~dp0"

REM Exécuter le scheduler
php artisan schedule:run

REM Logs (optionnel - décommenter pour activer les logs)
REM echo %date% %time% - Scheduler executé >> scheduler.log

