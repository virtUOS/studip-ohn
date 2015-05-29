<?= $GLOBALS['default_factory']->open('loginform')->render() ?>

<div style="float: right; width: 250px; margin-top: -350px;">
    <p>
        <?= _('Sie haben noch keinen Account?') ?>
        <br><br>

        <span>
            <a href="<?= URLHelper::getUrl('plugins.php/mooc/courses/index?cancel_login=1') ?>">
                <?= _('Registrieren Sie sich zunächst für einen Kurs um einen Account zu erhalten.') ?>
            </a>
        </span>
    </p>
</div>

<?= $GLOBALS['template_factory']->open('footer')->render() ?>

<style>
    #layout_footer:not(.ohn) {
        display: none;
    }
</style>
