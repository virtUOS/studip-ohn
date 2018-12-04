<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<table class='default'>
    <tr>
        <th>Kurs</th>
        <? foreach($datafields as $field): ?>
            <th><?= $field->name ?></th>
        <? endforeach ?>
    </tr>
<? foreach ($courses as $course) : ?>

    <tr>
        <td><?= $course['Name'] . ' - ' . $course['VeranstaltungsNummer'] ?></td>
        <? foreach ($user as $field) : ?>
        <td><?= $field ?></td>
        <? endforeach ?>
    </tr>
<? endforeach ?>
</table>