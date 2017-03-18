::
:: umbenennen
net use \\fcap17 /persistent:no

set "ziel="W:\Germany\IT Abteilung\Projekte_FKOS\B7.0312 Abrechnungsexport Targo\erg.txt""
set "cmd=SQL_Befehl = SQL_Befehl & vbCrLf & "
timeout/T 20

For /F "tokens=*" %%A in (test.txt) do  echo "%cmd%"%%A"">>%ziel%
timeout /T 20