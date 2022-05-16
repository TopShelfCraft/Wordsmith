<?php
namespace TopShelfCraft\Wordsmith;

use Craft;
use craft\web\twig\variables\CraftVariable;
use TopShelfCraft\base\Plugin;
use TopShelfCraft\Wordsmith\services\EmojiService;
use TopShelfCraft\Wordsmith\services\TypographyService;
use TopShelfCraft\Wordsmith\services\WordsmithService;
use TopShelfCraft\Wordsmith\view\WordsmithTwigExtension;
use yii\base\Event;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @link https://topshelfcraft.com
 * @copyright Copyright 2022, Top Shelf Craft (Michael Rog)
 *
 * @property  EmojiService $emoji
 * @property  WordsmithService $smith
 * @property  TypographyService $typography
 *
 * @method Settings getSettings()
 */
class Wordsmith extends Plugin
{

	public ?string $changelogUrl = 'https://raw.githubusercontent.com/TopShelfCraft/Wordsmith/master/CHANGELOG.md';
	public bool $hasCpSection = false;
	public bool $hasCpSettings = false;
	public string $schemaVersion = "0.0.0.0";

	public function init()
	{

		$this->setComponents([
			'emoji' => EmojiService::class,
			'smith' => WordsmithService::class,
			'typography' => TypographyService::class,
		]);

		parent::init();

		Craft::$app->getView()->registerTwigExtension(new WordsmithTwigExtension());

		// For folks coming from Craft 2.x, we'll provide our methods under a `{{ craft.wordsmith }}` variable
		Event::on(
			CraftVariable::class,
			CraftVariable::EVENT_INIT,
			function (Event $event) {
				/** @var CraftVariable $variable * */
				$variable = $event->sender;
				$variable->set('wordsmith', Wordsmith::getInstance()->smith);
			}
		);

	}

	/**
	 * Creates and returns the model used to store the plugin’s settings.
	 */
	protected function createSettingsModel(): Settings
	{
		return new Settings();
	}

}
