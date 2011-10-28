<?php

/**
 * Script to interact with user during BotBlockX package install
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 * @author Bob Ray <http://bobsguides.com>
 * 10/12/2011
 *
 *  is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 *  is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 */
/**
 * Description: Script to interact with user during BotBlockX package install
 * @package botblockx
 * @subpackage build
 */
/* User enters Resource ID of contact page here. Used to set contact_id default property
 * of the plugin, which is used in the Tpl chunk for appeals ("If you think this is an error," etc).
 */

$output = '<p>&nbsp;</p>
<label for="contact_id">Enter the Resource ID of your contact page here. Leave blank if there is no contact page.</label>
<p>&nbsp;</p>
<input type="text" name="contact_id" id="contact_id" value="" align="left" size="40" maxlength="60" /><p>&nbsp;</p>';


return $output;