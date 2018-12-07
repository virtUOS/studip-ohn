<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
Die Kursnummer steht jeweils für das Jahr und den Monat: 1805 = Mai 2018
<table class='default'>
    <tr>
        <th>Kurs</th>
            <th>Anzahl TN</th>
    </tr>
<? foreach ($courses as $course) : ?>

    <tr>
        <td><?= $course['Name'] . ' - ' . $course['VeranstaltungsNummer'] ?></td>
        <td><?= $coursemembers[$course['Seminar_id']] ?></td>

        
    </tr>
<? endforeach ?>
</table>