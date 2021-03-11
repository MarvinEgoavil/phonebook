@echo off
SET CMDER_ROOT=%~dp0

for %%f in (%1) do set TER_TITLE=%%~nxf
title %TER_TITLE% - %1

:: Remove trailing '\'
@if "%CMDER_ROOT:~-1%" == "\" SET CMDER_ROOT=%CMDER_ROOT:~0,-1%

cd /d "%1"
cmd /k %CMDER_ROOT%\vendor\init.bat