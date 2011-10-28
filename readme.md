BotBlockX Extra for MODx Revolution
===================================

**Author:** Alex Kemp [Modem-Help](http://bobsguides.com)
**Author:** Bob Ray <http://bobsguides.com> [Bob's Guides](http://bobsguides.com)

Documentation is available at [Bob's Guides](http://bobsguides.com/antihammerx-tutorial.html)

BotBlockX is a plugin for MODx Revolution that stops bad bots from hammering your site looking for a way to break in and stops scrapers from stealing your content.

BotBlockX was inspired by Alex Kemps's bot-block.php script [bot-block.php](http://download.modem-help.co.uk/non-modem/PHP/Rogue-Bot-Blocking/bot-block.php.txt.7z.php?showFile=bot-block.php.txt#archive). It has been rewritten for MODX Revolution and uses properties for the settings and Tpl chunks for the warning message. An extra penalty for multiple file-not-found hits has been added to help foil hammerers. It does not inhibit well-behaved legitimate search spiders from indexing your site.

It is critical to add this to your .htaccess file (with a space above and below it):

    User-agent: *
    Crawl-delay: 90
    Crawl-Delay: 90