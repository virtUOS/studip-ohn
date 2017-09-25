<?php

use Studip\Button, Studip\LinkButton; ?>



<div id="sidebar"> 
<table class="default">
    <tr>
        <th>Aktionen </th>
        <th> Termin </th>
        <th> Subject </th>
        <th> Versendet </th>
    </tr>
    
    
<? foreach ($planned_mails as $mail){ 
    if ($mail['days_from_start'] >= 0){ $termin_string = ' Tage nach Kursbeginn'; }
    if ($mail['days_from_start'] < 0){ $termin_string = ' Tage vor Kursbeginn'; }
?>
    <tr>
        <td>
            <a href="<?=$this->controller->url_for('notifications/delete/'. $mail['id']) ?>" onclick="return confirm('Eintrag wirklich löschen?')"><?= Assets::img('icons/24/blue/trash.png', array('title' => 'Eintrag löschen')) ?> </a>
            <a href="<?=$this->controller->url_for('notifications/edit/'. $mail['id']) ?>"><?= Assets::img('icons/24/blue/edit.png', array('title' => 'Eintrag bearbeiten')) ?> </a>
        </td>
                    
        <td> <?=abs($mail['days_from_start']) . $termin_string?> </td>
        <td> <?=$mail['mailsubject']?> </td>
        <td> <?=$mail['sent']?> </td>
    </tr>




<? } ?>
</table>