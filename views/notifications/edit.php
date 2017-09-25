

<html>
<head>
<meta charset="utf-8">
</head> 
<body>
<h2>Automatische Benachrichtigung bearbeiten/erstellen</h2>
<form method="post" action="<?=$controller->url_for('/notifications/save/'. $entry->id)?>">
    <p><label>Wann:<br><input value="<?= abs($entry->days_from_start)?>" type="int" name="days_from_start"></label>Tage
        <select name="delay">
    <option value="before"> vor Kursbeginn<br>
    <option value="after" <?= ($entry->days_from_start > 0) ? 'selected' : ''  ?> > nach Kursbeginn
        </select>
  </p>
  <p><label>Betreff:<br><input value="<?= $entry->mailsubject?>" type="text" name="mailsubject" size="60"></label>
   </p> 
   <p><label>Text:<br><textarea value="<?= $entry->mailcontent?>" type="text" name="mailcontent" rows="20" cols="60"><?= $entry->mailcontent?></textarea></label>
   </p>
  
   <p> Am angegebenen Tag wird morgens gegen 8:00 Uhr die oben definierte e-Mail <br> an alle TeilnehmerInnnen dieses Kurses versendet.</p>

<button type="submit" name="submit" > Speichern
</form>
</body>

