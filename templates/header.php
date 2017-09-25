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
                    $link_attributes = $nav->getLinkAttributes();

                    // Add access key to link attributes
                    if ($accesskey_enabled) {
                        $accesskey = ++$accesskey % 10;
                        $link_attributes['accesskey'] = $accesskey;
                        $link_attributes['title']    .= "  [ALT] + $accesskey";
                    }

                    // Add badge number to link attributes
                    if ($nav->getBadgeNumber()) {
                        $link_attributes['data-badge'] = (int)$nav->getBadgeNumber();
                    }

                    // Convert link attributes array to proper attribute string
                    $attr_str = '';
                    foreach ($link_attributes as $key => $value) {
                        $attr_str .= sprintf(' %s="%s"', htmlReady($key), htmlReady($value));
                    }

                    ?>
                    <li id="nav_<?= $path ?>"<? if ($nav->isActive()) : ?> class="active"<? endif ?>>
                        <a href="<?= URLHelper::getLink($nav->getURL(), $link_params) ?>" <?= $attr_str ?>>
                            <?= $image->asImg(['class' => 'headericon original']) ?>
                            <br>
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



    
    

    <header aria-label="Globale Navigation" class="ohn-global">
        <nav>
<div id="barBottomContainer">
    <div id="barBottomLeft">

</div>
</div>

            <h1 class="logo_slim">
                <? if ($GLOBALS['user']->id == 'nobody') : ?>
                    <a href="<?= URLHelper::getUrl('plugins.php/mooc/courses/overview?cancel_login=1') ?>">
                <? elseif (is_object($GLOBALS['user']) && $GLOBALS['user']->id != 'nobody') : ?>
                      <a href="<?= URLHelper::getUrl('dispatch.php/start') ?>">
                <?endif ?>
                    <img src="<?= $GLOBALS['OHN_IMAGES'] ?>/header-logo.png" alt="Logo" />
                </a>
            </h1>


            <? if ($GLOBALS['user']->id !== 'nobody') : ?>
                            <h2>
                            <?=($current_page != "" ? htmlReady($current_page) : "")?>
                            <?= $publi_hint ? '(' . htmlReady($public_hint) . ')' : '' ?>
                            </h2>
                        <? endif ?>


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
                        <a href='http://www.offene-hochschule-niedersachsen.de/'>
                    <img src="<?= $GLOBALS['OHN_IMAGES'] ?>/ohn_logo.png">
                        </a>
                </li>

                <? if ($GLOBALS['user']->id == 'nobody') : ?>

                    <li>
                                        <a href="<?= URLHelper::getUrl('index.php?again=yes') ?>" class="login_button">Anmelden</a>
                    </li>

                  <? else : ?>

                    <li class="user_menu">
                                        <? if (is_object($GLOBALS['user']) && $GLOBALS['user']->id != 'nobody') : ?>
                                            <a href="<?= URLHelper::getUrl('dispatch.php/start') ?>" class="user_button">
                                                <? echo $GLOBALS['auth']->auth['uname']; ?>
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

                                        </li>
                                <? endif ?>

            </ol>

        </nav>
    </header>



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
        <? if (is_object($GLOBALS['user']) && $GLOBALS['user']->id != 'nobody') : ?>
            <? $user = User::findByUsername($GLOBALS['user']->username);?>
            <div id="userdata">
                <input id="username" type="hidden" value="<?= $user->username;?>">
                <input id="userfullname" type="hidden" value="<?= $user->vorname." ".$user->nachname ;?>">
                <input id="usermail" type="hidden" value="<?= $user->email;?>">
                <input id="linksettings" type="hidden" value="<?= URLHelper::getLink('dispatch.php/settings/account', $header_template->link_params) ?>">
                <input id="linkpassword" type="hidden" value="<?= URLHelper::getLink('dispatch.php/settings/password', $header_template->link_params) ?>">
            </div>
        <? endif ?>
