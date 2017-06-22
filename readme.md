BotBlockX Extra for MODx Revolution
===================================

This plugin is in development and should NOT be used on a live server.

**Author:** Alex Kemp [bot-block](http://biostatisticien.eu/www.searchlores.org/bot-block.php.txt)<br>
**Author:** Bob Ray [Bob's Guides](http://bobsguides.com)

**Documentation:** [BotBlockX Docs](http://bobsguides.com/botblockx-tutorial.html)

**Bugs and requests:** [BotBlockX Issues](https://github.com/BobRay/BotBlockX/issues)

**Questions about using BotBlockX** [MODx Forums](https://forums.modx.com)

BotBlockX is a plugin for MODx Revolution that stops bad bots from hammering your site looking for a way to break in and stops scrapers from stealing your content.

BotBlockX is based on Alex Kemps's bot-block.php script [bot-block.php](http://biostatisticien.eu/www.searchlores.org/bot-block.php.txt). It has been rewritten for MODX Revolution and uses properties for the settings and Tpl chunks for the warning message. An extra penalty for multiple file-not-found hits has been added to help foil hammerers. It does not inhibit well-behaved legitimate search spiders from indexing your site.

Add this to your robots.txt file (with a space above and below it):

    User-agent: *
    Crawl-delay: 90
    Crawl-Delay: 90