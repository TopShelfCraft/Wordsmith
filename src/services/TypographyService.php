<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2020, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace topshelfcraft\wordsmith\services;

use craft\base\Component;
use PHP_Typography\Fixes\Node_Fixes\Dewidow_Fix;
use PHP_Typography\Fixes\Node_Fixes\Smart_Quotes_Fix;
use PHP_Typography\Fixes\Node_Fixes\Style_Ampersands_Fix;
use PHP_Typography\Fixes\Node_Fixes\Style_Caps_Fix;
use topshelfcraft\wordsmith\Wordsmith;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class TypographyService extends Component
{

	/*
     * Private properties
     * ===========================================================================
     */

	private $_typographer;
	private $_typographySettings;

	/*
     * Public methods
     * ===========================================================================
     */

	/**
	 *
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * @return \PHP_Typography\PHP_Typography
	 */
	public function getTypographer()
	{

		if (!isset($this->_typographer))
		{

			// Create a new PhpTypography instance

			$this->_typographer = new \PHP_Typography\PHP_Typography();

			// Create a new phpTypographySettings instance

			$this->_typographySettings = new \PHP_Typography\Settings();

			// Tweak the de-widow settings to make our `widont` filter a bit more aggressive.
			$this->_typographySettings->set_max_dewidow_length(10);
			$this->_typographySettings->set_max_dewidow_pull(10);

			// Apply settings from our plugin config

			$settings = Wordsmith::getInstance()->getSettings()->typographySettings;

			if (is_array($settings))
			{
				foreach ($settings as $key => $value)
				{
					$this->_typographySettings->{$key}($value);
				}
			}

		}

		return $this->_typographer;

	}

	/**
	 * @param array $adhocSettings
	 * @return \PHP_Typography\Settings
	 */
	public function getTypographySettings($adhocSettings = [])
	{

		$settings = (clone $this->_typographySettings);

		if (is_array($adhocSettings))
		{
			foreach ($adhocSettings as $key => $value)
			{
				$settings->{$key}($value);
			}
		}

		return $settings;

	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function typogrify($text, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process($text, $this->getTypographySettings($adhocSettings));
		return $result;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function typogrifyFeed($text, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_feed($text, $this->getTypographySettings($adhocSettings));
		return $result;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function smartypants($text, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Smart_Quotes_Fix()), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	/**
	 * @param $text
	 * @param array $adhocSettings
	 * @return string
	 */
	public function widont($text, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Dewidow_Fix()), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	/**
	 * @param $text
	 * @param $class
	 * @param array $adhocSettings
	 * @return string
	 */
	public function wrapAmps($text, $class, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Style_Ampersands_Fix($class)), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	/**
	 * @param $text
	 * @param $class
	 * @param array $adhocSettings
	 * @return string
	 */
	public function wrapCaps($text, $class, $adhocSettings = [])
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Style_Caps_Fix($class)), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

}
