<?
# Lifter010: TODO
foreach (Navigation::getItem("/")->getSubNavigation() as $path => $nav) {
    if ($nav->isActive()) {
        $path1 = $path;
    }
}
?>
<? SkipLinks::addIndex(_("Erste Reiternavigation"), 'tabs', 10); ?>
<ul id="tabs" role="navigation">
    <? foreach ($navigation as $path => $nav) : ?>
        <? if ($nav->isVisible()) : ?>
            <? if ($nav->isActive()) : ?>
            <li id="nav_<?= $path1 ?>__<?= $path ?>" class="current"
                    onMouseOver="showSubmenu(this)">
            <? else : ?>
            <li id="nav_<?= $path1 ?>__<?= $path ?>">
            <? endif ?>
                <? $nav->isActive() && $path2 = $path ?>
                <? if ($nav->isEnabled()) : ?>
                    <?
                    $badge_attr = '';
                    if ($nav->hasBadgeNumber()) {
                      $badge_attr = ' class="badge" data-badge-number="' . intval($nav->getBadgeNumber())  . '"';
                    }
                    ?>

                    <a href="<?= URLHelper::getLink($nav->getURL()) ?>"<?= $badge_attr ?>>
                        <? if ($image = $nav->getImage()) : ?>
                            <?= Assets::img($image['src'], array('class' => "tab-icon", 'alt' => htmlReady($nav->getTitle()), 'title' => $nav->getTitle() ? htmlReady($nav->getTitle()) : htmlReady($nav->getDescription()))) ?>
                        <? endif ?>
                        <span title="<?= $nav->getDescription() ? htmlReady($nav->getDescription()) :  htmlReady($nav->getTitle())?>" ><?= $nav->getTitle() ? htmlReady($nav->getTitle()) : '&nbsp;' ?></span>
                    </a>
                <? else: ?>
                    <span class="quiet">
                        <? if ($image = $nav->getImage()) : ?>
                            <?= Assets::img($image['src'], array('class' => "tab-icon", 'alt' => htmlReady($nav->getTitle()), 'title' => htmlReady($nav->getTitle()))) ?>
                        <? endif ?>
                        <?= htmlReady($nav->getTitle()) ?>
                    </span>
                <? endif ?>
            </li>
        <? endif ?>
    <? endforeach ?>
</ul>

<style>
    #tabs2_dropdown {
        padding: 10px;
        margin: 0;
        background-color: white;
        position: absolute;
        border-left: 1px solid #CCCCFF;
        border-right: 1px solid #CCCCFF;
        border-bottom: 1px solid #CCCCFF;
        -webkit-box-shadow: 2px 2px 2px 0px rgba(50, 50, 50, 0.75);
        -moz-box-shadow:    2px 2px 2px 0px rgba(50, 50, 50, 0.75);
        box-shadow:         2px 2px 2px 0px rgba(50, 50, 50, 0.75);
        min-width: 250px;
    }
    
    #tabs2_dropdown li.current {
        background-color: #CCCCFF;
    }
</style>
<script>
    var i = 0;
    jQuery('body').bind('mousemove', function(e) {
        if (i > 20) {
            var rX, rY, rW, rH;

            rX = jQuery('#tabs li.current').offset().left;
            rY = jQuery('#tabs li.current').offset().top;

            rW = Math.max(jQuery('#tabs li.current').width(), jQuery('#tabs2_dropdown').width()) + 10;
            rH = jQuery('#tabs li.current').height() + jQuery('#tabs2_dropdown').height() + 10;

            if (e.pageX < rX || e.pageX > rX + rW 
                    || e.pageY < rY || e.pageY > rY + rH) {
                jQuery('#tabs2_dropdown').slideUp();
            }
            i = 0;
        }
        i++;
    });
    
    function showSubmenu(element) {
        if (jQuery('#tabs2_dropdown li').length > 0) {
            jQuery('#tabs2_dropdown').width(jQuery(element).width());
            jQuery('#tabs2_dropdown').css('left', jQuery(element).offset().left);
            jQuery('#tabs2_dropdown').slideDown();
        }
    }
</script>
<ul id="tabs2_dropdown" role="navigation" style="display: none;" onClick="jQuery($this).blindUp()">
    <? $subnavigation = $navigation->activeSubNavigation() ?>
    <? if (isset($subnavigation)) : ?>
        <? foreach ($subnavigation as $path3 => $nav) : ?>
            <? if ($nav->isVisible()) : ?>
                <? SkipLinks::addIndex(_("Zweite Reiternavigation"), 'tabs2', 20); ?>
                <li id="nav_<?= $path1 ?>__<?= $path2 ?>__<?= $path3 ?>"<?= $nav->isActive() ? ' class="current"' : '' ?>
                    style="list-style: none outside none; margin: 0; padding: 0;">
                    <!-- <?= htmlReady($nav->getTitle()) ?> -->
                    <? if ($nav->isEnabled()) : ?>
                        <a href="<?= URLHelper::getLink($nav->getURL()) ?>">
                            <?= htmlReady($nav->getTitle()) ?>
                        </a>
                    <? else: ?>
                        <span class="quiet">
                            <?= htmlReady($nav->getTitle()) ?>
                        </span>
                    <? endif ?>
                </li>
            <? endif ?>
        <? endforeach ?>
    <? endif ?>
</ul>

<script>
function sortAlpha(a, b){
    return a.innerHTML.toLowerCase() > b.innerHTML.toLowerCase() ? 1 : -1;
};

$('#tabs2_dropdown li').sort(sortAlpha).appendTo('#tabs2_dropdown');
</script>