<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2017, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace topshelfcraft\wordsmith;

use topshelfcraft\wordsmith\models\Settings;
use topshelfcraft\wordsmith\services\EmojiService;
use topshelfcraft\wordsmith\services\TitleCapitalizationService;
use topshelfcraft\wordsmith\services\TypographyService;
use topshelfcraft\wordsmith\services\WordsmithService;
use topshelfcraft\wordsmith\twigextensions\WordsmithTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 *
 * @property  EmojiService $emoji
 * @property  WordsmithService $smith
 * @property  TypographyService $typography
 *
 * @method    Settings getSettings()
 */
class Wordsmith extends Plugin
{

    /*
     * Static properties
     * ===========================================================================
     */

    /**
     * Static instance of this plugin class, accessed via `Wordsmith::$plugin`
     *
     * @var Wordsmith
     */
    public static $plugin;


    /*
     * Public methods
     * ===========================================================================
     */

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();
        self::$plugin = $this;

        Craft::$app->getView()->registerTwigExtension(new WordsmithTwigExtension());

        // For folks coming from Craft 2.x, we'll provide our methods under a `{{ craft.wordsmith }}` variable
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable **/
                $variable = $event->sender;
                $variable->set('wordsmith', Wordsmith::$plugin->smith);
            }
        );

    }

    /*
     * Protected methods
     * ===========================================================================
     */

	/**
	 * Creates and returns the model used to store the plugin’s settings.
	 *
	 * @return Settings|null
	 */
	protected function createSettingsModel()
	{
		return new Settings();
	}

}
