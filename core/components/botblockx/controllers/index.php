<?php

/**
 * Controller index.php for the BotBlockX package
 * @author Bob Ray
 * 2/4/11
 *
 * @package botblockx

 */

/* This file is not used in the package. It's an example of a possible controller */

require_once dirname(dirname(__FILE__)).'/model/antihammerx/antihammerx.class.php';
$antihammerx = new BotBlockX($modx, $scriptProperties);
return $antihammerx->init('mgr');
