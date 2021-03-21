<!--<html><head><title>RS Mailer</title></head></hmtl>-->
<?php

// Prüfscript vorneweg einbauen, dass der RS Prüfer nur einmal prüft und nicht hunderttausendmal am Tag:
// hole Inhalt von Textfile:
$latest_rs_from_file = "";
$day = date("d");
if (!file_exists("dailycheck.txt"))
         {
           // echo "Datei dailycheck.txt nicht vorhanden.";
            exit;
         }
         else
         {
	$dailycheck_from_file = file_get_contents('dailycheck.txt');
	}

if ($dailycheck_from_file == $day)
{
//echo ("RS Pr&uuml;fung heute schon erfolgt! <br>");
exit; // wenn heute schon geprüeft nicht andauernd neu prüefen...
}

// hier geht es nur weiter, wenn noch kein Check erfolgte heute:
// dann neues Check Datum reinschreiben also heute gechecked:

$handlex = fopen ("dailycheck.txt", "w");
fwrite ($handlex, $day);
fclose ($handlex);

//$loadjsplink = "http://servername.de/zeitung/index.xml.jsp";
$loadjsplink = "https://www.servername.de/sublink/services/zeitung/index.xml.jsp";

// Source Quelltext holen von xml.jsp File:
// da man die jsp nicht auslesen kann, zieht man den xml Code einfach auf den eigenen Server kurzzeitig:
$homepage = file_get_contents($loadjsplink);

$inhalt3 = $homepage;

// schreibe die jsp in eigene xml auf dem eigenen Server:
$handle3 = fopen ("websitesource.xml", "w");
fwrite ($handle3, $inhalt3);
fclose ($handle3);

// setze eigenen xml Link als Ausleseserver: denn wir haben von dguv ja die jsp auf eine xml bei mir auf dem server konvertiert:
$loadxmllink = "http://myserver.de/sublinkzeitung/websitesource.xml";
//$loadxmllink = "index.xml";

$xml=simplexml_load_file($loadxmllink) or die("Error: Cannot create object");
// EINZELNE ITEMS herausfinden:
echo $xml->channel->item[0]->pubDate . "<br>";
$latest_publish_date = $xml->channel->item[0]->pubDate;
$latest_publish_date = str_replace("<br>","",$latest_publish_date);
echo $xml->channel->item[0]->link . "<br>";

// Abfrage mit Textfile auf eigenem Server, ob das oberste neueste Zeitung ein aktuelles ist:

// wenn ja, d.h. neuestes entspricht NICHT dem, in der Textfile, dann ist ein neues vorhanden, 

// hole Inhalt von Textfile:
$latest_rs_from_file = "";
if (!file_exists("latest_rs.txt"))
         {
            echo "Datei nicht vorhanden.";
            exit;
         }
         else
         {
	$latest_rs_from_file = file_get_contents('latest_rs.txt');
	}

//echo ("Zeitung Stand aus Textdatei:".$latest_rs_from_file."<br>");
if ($latest_rs_from_file != $latest_publish_date) // d.h. ungleich, d.h. neueres vorhanden
{

//echo ("Es ist eine neue Zeitung vorhanden... :) "."<br>");

// dann Mail senden an E-Mail zur Information:
//echo ("Informations Mail wird an Benutzer versandt... <br>");
// Informationen zum aktuellsten RS:
$publishdate_latest_rs = $xml->channel->item[0]->pubDate;
$downloadlink_latest_rs = $xml->channel->item[0]->link;

// Informationen zum zweit aktuellsten Schreiben:

$publishdate_zweites_rs = $xml->channel->item[1]->pubDate; 
$downloadlink_zweites_rs = $xml->channel->item[1]->link;


// E Mail an User versenden, mit Datum und Links zu den neusten beiden Schreiben:
$empfaenger = "mail@empfaenger.de";
$betreff = "Zeitung $latest_publish_date";
$from = "From: Zeitung Service <zeitung@automatisiert.de>\r\n";
$from .= "Reply-To: zeitung@automatisiert.de\r\n";
$from .= "Content-Type: text/html\r\n";
$text = "<b>Neue Zeitung vorhanden!</b>\n
</br></br>
\n\n
Letzte Veroeffentlichung vom: $publishdate_latest_rs\n</br></br>
Downloadlink: <a href=\"$downloadlink_latest_rs\">$downloadlink_latest_rs</a>
</br></br>
Vorherige Veroeffentlichung vom: $publishdate_zweites_rs</br>
Downloadlink: <a href=\"$downloadlink_zweites_rs\">$downloadlink_zweites_rs</a></br>

</br></br>

Mit freundlichen Gruessen </br></br>
Ihr Newsletter Feed Service </br>
";
 
mail($empfaenger, $betreff, $text, $from);

// und neuestes Zeitung in die textfile schreiben:

$inhalt = $latest_publish_date;
$handle = fopen ("latest_rs.txt", "w");
fwrite ($handle, $inhalt);
fclose ($handle);
}
// wenn nein, d.h. neuestes entspricht dem in der Textfile, dann keine Mail senden:
else if ($latest_rs_from_file == $latest_publish_date)
{
 // keine neuen Zeitungen vorhanden
 //echo ("Es sind keine neuen Zeitungen vorhanden! <br>");
}
//echo ("<br><br>");	
?>
