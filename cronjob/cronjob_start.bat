set eingabe=%1

del /q C:\xampp_1.8.2\htdocs\Tool\sql\data\*

::C:\xampp_1.8.2\php\php.exe -f C:\xampp_1.8.2\htdocs\Tool\cronjob\cronjob_server.php

if "%eingabe%" == "stop" (

	schtasks /query /s fcap17.falcon.local /fo csv >> C:\xampp_1.8.2\htdocs\Tool\sql\data\fcap17.txt

	schtasks /query /s fcap09.falcon.local /fo csv >> C:\xampp_1.8.2\htdocs\Tool\sql\data\fcap09.txt

	schtasks /query /s fcap09.falcon.local /fo csv /v >> C:\xampp_1.8.2\htdocs\Tool\sql\data\task_fcap09.txt

	schtasks /query /s fcap17.falcon.local /fo csv /v >> C:\xampp_1.8.2\htdocs\Tool\sql\data\task_fcap17.txt

	C:\xampp_1.8.2\php\php.exe -f C:\xampp_1.8.2\htdocs\Tool\taskInStatus.php
	
	exit
)

if "%eingabe%" == "go" (
	
	schtasks /query /s fcap17.falcon.local /fo csv >> C:\xampp_1.8.2\htdocs\Tool\sql\data\fcap17.txt

	schtasks /query /s fcap09.falcon.local /fo csv >> C:\xampp_1.8.2\htdocs\Tool\sql\data\fcap09.txt

	schtasks /query /s fcap09.falcon.local /fo csv /v >> C:\xampp_1.8.2\htdocs\Tool\sql\data\task_fcap09.txt

	schtasks /query /s fcap17.falcon.local /fo csv /v >> C:\xampp_1.8.2\htdocs\Tool\sql\data\task_fcap17.txt

	C:\xampp_1.8.2\php\php.exe -f C:\xampp_1.8.2\htdocs\Tool\cronjob\cronjob_task.php

	C:\xampp_1.8.2\php\php.exe -f C:\xampp_1.8.2\htdocs\Tool\taskInStatus.php
)
