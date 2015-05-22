<?
# Lifter010: TODO
?>
    <!-- Beginn Footer -->
    <div id="layout_footer" class="ohn">
      <ul class="footer_navigation_main">
      <? if (Navigation::hasItem('/footer')) : ?>
      <? foreach (Navigation::getItem('/footer') as $nav) : ?>
          <? if ($nav->isVisible()) : ?>
              <li>
              <a
              <? if (is_internal_url($url = $nav->getURL())) : ?>
                  href="<?= URLHelper::getLink($url, $header_template->link_params) ?>"
              <? else : ?>
                  href="<?= htmlReady($url) ?>" target="_blank"
              <? endif ?>
              ><?= htmlReady($nav->getTitle()) ?></a>
              </li>
          <? endif ?>
      <? endforeach ?>
      <? endif ?>
      </ul>
      
      <div class="footer_about">
	      <div class="footer_logos">
		      <a href="#">
		      	<img src="<?= $GLOBALS['OHN_IMAGES'] ?>/ohn_logo.png" alt="OHN Logo" />
		      </a>
		      
		      <a href="#">
		      	<img src="<?= $GLOBALS['OHN_IMAGES'] ?>/nds_logo.png" alt="Niedersachsen Logo" />
		      </a>
	      </div>
	      
	      <p>Die Kosten für die Entwicklung des Portals und die Teilnahme an den Online-Studienvorbereitungskursen des OHN-KursPortals trägt das Niedersächsische Ministerium für Wissenschaft und Kultur.</p>
				<div class="clear"></div>
      </div>
      
      <div class="footer_navigation_sec">
	      <ul>
		      <li>
			          <a href="<?= URLHelper::getUrl('plugins.php/ohnlayout/index/nutzungsbedingungen') ?>">
				    	Nutzungsbedingungen
			      </a>
		      </li>
		      <li>
           <a href="<?= URLHelper::getUrl('plugins.php/ohnlayout/index/datenschutz') ?>">
				       Datenschutz 
			      </a>
		      </li>
          <a href="https://www.facebook.com/offenehochschuleniedersachsen">
            <img src="<?= $GLOBALS['OHN_IMAGES'] ?>/fb-icon.png" alt="facebook" />
          </a>
	      </ul>
      </div>
      
    </div>
    <!-- Ende Footer -->
