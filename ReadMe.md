
RS Catcher 1.0
21 / 03 / 2021

Fragt als Rundschreiben Catcher bestimmte Zeitungs- und Zeitschriften Inhalte vom Server ab.
Auf dem Server liegen XML Files mit Aktualisierungsstaenden im Item pubDate XML
sowie Links zum Download der Zeitungen.

	
Momentan ist der Rundschreiben Catcher so eingestellt, dass er:

mit einem simplen:

<?php include ("zeitschrift/index.php"); /*bindet den Zeitschrift mail newsletter mit ein (mailversand, wenn es ein neues RS gibt... */?>

In eine andere Seite ohne (echo) Ausgabe unten eingebunden werden kann, solange er im Verzeichnis zeitschrift Ordner liegt.
Dementsprechend ist anzupassen.

Beispiel:
$dailycheck_from_file = file_get_contents('zeitung/dailycheck.txt');
	
Hierbei liegt der Ordner zeitung im Root Server / Wurzelverzeichnis.

Wenn der Ordner gewechselt wird, dann ist im index.php File noch bei jedem der Ordner zeitung vornewegzunehmen bei jeder File, sonst gehts nicht.

Er verschickt dann immer eine neue Mail, wenn es neue RS gibt.


