<?= $GLOBALS['default_factory']->open('loginform')->render() ?>

<div style="float: right; width: 250px; margin-top: -350px;">
    <p>
        <?= _('Sie haben noch keinen Account?') ?>
        <br><br>

        <span>
            <a href="<?= URLHelper::getUrl('plugins.php/ohnlayout/index/kurse?cancel_login=1') ?>">
                <?= _('Registrieren Sie sich zun�chst f�r einen Kurs um einen Account zu erhalten.') ?>
            </a>
        </span>
    </p>
</div>
