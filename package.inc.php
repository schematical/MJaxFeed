<?php
define('__MJAX_FEED__', dirname(__FILE__));
define('__MJAX_FEED_CORE__', __MJAX_FEED__ . '/_core');
define('__MJAX_FEED_CORE_CTL__', __MJAX_FEED_CORE__ . '/ctl');
define('__MJAX_FEED_CORE_MODEL__', __MJAX_FEED_CORE__ . '/model');
define('__MJAX_FEED_CORE_VIEW__', __MJAX_FEED_CORE__ . '/view');
MLCApplicationBase::$arrClassFiles['MJaxFeedPanel'] = __MJAX_FEED_CORE_CTL__ . '/MJaxFeedPanel.class.php';
MLCApplicationBase::$arrClassFiles['MJaxFeedDisplayPanelBase'] = __MJAX_FEED_CORE_CTL__ . '/MJaxFeedDisplayPanelBase.class.php';


require_once(__MJAX_FEED_CORE_CTL__ . '/_events.inc.php');

define('__MJAX_FEED_ASSETS_JS__', MLCApplication::GetAssetUrl('/js', 'MJaxBootstrap'));
