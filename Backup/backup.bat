::
::				 BACKUP
::
::	Anwendung zur Überwachung und Kontrolle von automatischen Tasks
::
:: 			erstellt am	: 14-02-2014
:: 			von		: Felix Koschmidder 
::____________________________________________________________________________

echo off


:: DB BACKUP 
::___________

SqlCmd -E -S FCVP02\SQLEXPRESS -Q "BACKUP DATABASE [Lindorff_DB] TO DISK = 'C:\xampp_1.8.2\htdocs\Tool\Backup\Lindorff_DB_%date:~6%_%date:~3,2%_%date:~0,2%__%time:~-11,2%_%time:~-8,2%_%time:~-5,2%.bak' "

::pause


:: Projektordner kopieren
::_______________________ 

xcopy  /E /Y "C:\xampp_1.8.2\htdocs\Tool" "\\groupad1.com\data\Germany\IT Abteilung\Projekte_FKOS\BACKEND-TOOL\Tool_Backup"



:: Datenbank Backup kopieren
::__________________________ 


if not exist "\\groupad1.com\data\Germany\IT Abteilung\Projekte_FKOS\BACKEND-TOOL\Datenbank_Backup\%date:~6%\%date:~6%_%date:~3,2%\%date:~6%_%date:~3,2%_%date:~0,2%" (

  mkdir "\\groupad1.com\data\Germany\IT Abteilung\Projekte_FKOS\BACKEND-TOOL\Datenbank_Backup\%date:~6%\%date:~6%_%date:~3,2%\%date:~6%_%date:~3,2%_%date:~0,2%"

)

xcopy /E /Y "C:\xampp_1.8.2\htdocs\Tool\Backup\Lindorff_DB_%date:~6%_%date:~3,2%_%date:~0,2%*.bak" "\\groupad1.com\data\Germany\IT Abteilung\Projekte_FKOS\BACKEND-TOOL\Datenbank_Backup\%date:~6%\%date:~6%_%date:~3,2%\%date:~6%_%date:~3,2%_%date:~0,2%"

del "C:\xampp_1.8.2\htdocs\Tool\Backup\Lindorff_DB*"

