<? if ($GLOBALS['perm']->have_perm('root')) : ?>
<!-- Stud.IP Navigation only visible for root -->
<div id="openStudipNavigation">
	<div class="navigationButton"></div>
	<div class="navigationButton"></div>
	<div class="navigationButton"></div>

    <!-- Start Header -->
    <div id="flex-header">
        <div id="header">
            <!--<div id='barTopLogo'>
                <img src="<?=$GLOBALS['ASSETS_URL']?>images/logos/logoneu.jpg" alt="Logo Uni Göttingen">
            </div>
             -->
            <div id="barTopFont">
            <?= htmlReady($GLOBALS['UNI_NAME_CLEAN']) ?>
            </div>
            <? SkipLinks::addIndex(_("Hauptnavigation"), 'barTopMenu', 1); ?>
            <ul id="barTopMenu" role="navigation">
            <? $accesskey = 0 ?>
            <? foreach (Navigation::getItem('/') as $path => $nav) : ?>
                <? if ($nav->isVisible(true)) : ?>
                    <?
                    $accesskey_attr = '';
                    $image = $nav->getImage();

                    if ($accesskey_enabled) {
                        $accesskey = ++$accesskey % 10;
                        $accesskey_attr = 'accesskey="' . $accesskey . '"';
                        $image['title'] .= "  [ALT] + $accesskey";
                    }

                    $badge_attr = '';
                    if ($nav->hasBadgeNumber()) {
                        $badge_attr = ' class="badge" data-badge-number="' . intval($nav->getBadgeNumber())  . '"';
                    }

                    ?>
                    <li id="nav_<?= $path ?>"<? if ($nav->isActive()) : ?> class="active"<? endif ?>>
                        <a href="<?= URLHelper::getLink($nav->getURL(), $link_params) ?>" title="<?= $image['title'] ?>" <?= $accesskey_attr ?><?= $badge_attr ?>>
                           <span style="background-image: url('<?= $image['src'] ?>'); background-size: auto 32px;" class="<?= $image['class'] ?>"> </span><br>
                           <?= htmlReady($nav->getTitle()) ?>
                       </a>
                    </li>
                <? endif ?>
            <? endforeach ?>
            </ul>
        </div>
        <!-- Stud.IP Logo -->
        <div id="barTopStudip">
            <a href="http://www.studip.de/" title="Studip Homepage">
                <?= Assets::img('/images/logos/header_logo.png', array('@2x' => TRUE, 'size' => '203@76')); ?>
            </a>
        </div>
    </div>
</div>
<? endif ?>

<!--##### OHN HEADER #####-->

<div id="header_slim" class="main-container">

    <div class="user_menu">
        <!-- Split button -->
			
			<? if (is_object($GLOBALS['user']) && $GLOBALS['user']->id != 'nobody') : ?>
				<a href="#" class="user_button">
					<? echo $GLOBALS['auth']->auth['perm']; ?>
				</a>
				<a href="#" class="user_dropdown">
					<?= Assets::img('/images/icons/16/grey/arr_1down.png'); ?>
				</a>
			<? endif ?>

            <ul>
                <? if (is_object($GLOBALS['perm']) && PersonalNotifications::isActivated() && $GLOBALS['perm']->have_perm("autor")) : ?>
                <? $notifications = PersonalNotifications::getMyNotifications() ?>
                <? $lastvisit = (int) UserConfig::get($GLOBALS['user']->id)->getValue('NOTIFICATIONS_SEEN_LAST_DATE') ?>

                <? endif ?>
                <? if (Navigation::hasItem('/links')) : ?>
                <? foreach (Navigation::getItem('/links') as $nav) : ?>
                    <? if ($nav->isVisible()) : ?>
                        <li <? if ($nav->isActive()) echo 'class="active"'; ?>>
                        <a
                        <? if (is_internal_url($url = $nav->getURL())) : ?>
                            href="<?= URLHelper::getLink($url, $link_params) ?>"
                        <? else : ?>
                            href="<?= htmlReady($url) ?>" target="_blank"
                        <? endif ?>
                        <? if ($nav->getDescription()): ?>
                            title="<?= htmlReady($nav->getDescription()) ?>"
                        <? endif; ?>
                        ><?= htmlReady($nav->getTitle()) ?></a>
                        </li>
                    <? endif ?>
                <? endforeach ?>
                <? endif ?>
            </ul>
            
		</div>

    <header aria-label="Globale Navigation" class="ohn-global">
        <nav>
            <h1 class="logo_slim">
                <a href="<?= URLHelper::getUrl('plugins.php/mooc/courses/overview') ?>">
                    <img src="<?= $GLOBALS['OHN_IMAGES'] ?>/header-logo.png" alt="Logo" />
                </a>
            </h1>
  
            <ol class="left nav-global">
                <? if (Navigation::hasItem('/header')) : ?>
                    <? foreach (Navigation::getItem('/header') as $nav) : ?>
                        <? if ($nav->isVisible()) : ?>
                            <li <? if ($nav->isActive()) echo 'class="active"'; ?>>
                                <a
                                <? if (is_internal_url($url = $nav->getURL())) : ?>
                                    href="<?= URLHelper::getLink($url, $link_params) ?>"
                                <? else : ?>
                                    href="<?= htmlReady($url) ?>" target="_blank"
                                <? endif ?>
                                <? if ($nav->getDescription()): ?>
                                    title="<?= htmlReady($nav->getDescription()) ?>"
                                <? endif; ?>
                                ><?= htmlReady(strtoupper($nav->getTitle())) ?></a>
                            </li>
                        <? endif ?>
                    <? endforeach ?>
                <? endif ?>
            </ol>

            <ol class="right nav-courseware">
                <li class="ohn-logo">
                    <img src="<?= $GLOBALS['OHN_IMAGES'] ?>/ohn_logo.png">
                </li>
            </ol>
        </nav>
    </header>

    <h2>
        <?=($current_page != "" ? htmlReady($current_page) : "")?>
        <?= $publi_chint ? '(' . htmlReady($public_hint) . ')' : '' ?>
    </h2>
</div>
		
<!-- Ende Header -->

<!-- Beginn Page -->
<div id="layout_page">
<? if ($changed_status) : ?>
    <?= $this->render_partial('change_view') ?>
<? endif ?>
<? if (isset($navigation)) : ?>
    <?= $this->render_partial('tabs') ?>
<? endif ?>

<div id="layout_container">
    <div id="layout_content">