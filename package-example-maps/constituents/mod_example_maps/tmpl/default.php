<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$document = $this->app->getDocument();
$wa = $document->getWebAssetManager();
$wr = $wa->getRegistry();
$wr->addRegistryFile('media/mod_example_maps/joomla.asset.json');

$wa->usePreset('mod_example_maps.osmmaps');

$vars = $document->getScriptOptions('mod_example_maps.mapdata');
if ($vars) {
    $vars[] = $config;
} else {
    $vars = array($config);
}

$document->addScriptOptions('mod_example_maps.mapdata', $vars);

?>

<div>
    <p><?php echo Text::_('MOD_EXAMPLE_MAPS_CAPTION'); ?></p>
    <div id="<?php echo $config['id']; ?>" style="height: 400px; width: 100%"></div>
</div>