<?

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 ?>

 <? foreach($datafields as $field): ?>    
    <? if (is_array($field['choices']) && ($field['type'] == 'selectbox' || $field['type'] == 'text')): ?>
    <h2> <?= $field['label'] ?> </h2>
        <canvas id="<?= $field['fieldName'] ?>" style='max-width:600px; max-height:600px'></canvas>
    <? endif ?>
  <? endforeach ?>
  
  
<script>
    <? foreach($datafields as $field): ?>
                <? if (is_array($field['choices']) && ($field['type'] == 'selectbox' || $field['type'] == 'text')): ?>
        var c<?= $field['fieldName'] ?> = new Chart(document.getElementById("<?= $field['fieldName'] ?>").getContext('2d'), {
            type: 'pie',
            data: {
                labels: [
                    <? foreach ($field['choices'] as $choice): ?>
                        "<?= trim($choice) ?>",
                    <? endforeach ?>              
                ],
                datasets: [{
                    label: '# of Votes',
                    data: [
                        <? foreach ($field['choices'] as $choice): ?>
                        "<?= $data[$field['fieldName']][trim($choice)] ?>",
                    <? endforeach ?>  
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
<? endif ?>
<? endforeach ?>
            
</script>
 
 

 
 <style>
    #layout_wrapper {
        min-width: 980px;
        max-width: 2200px;
    }
</style>