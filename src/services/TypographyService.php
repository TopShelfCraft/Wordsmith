<?php
namespace TopShelfCraft\Wordsmith\services;

use PHP_Typography\Fixes\Node_Fixes\Dewidow_Fix;
use PHP_Typography\Fixes\Node_Fixes\Smart_Quotes_Fix;
use PHP_Typography\Fixes\Node_Fixes\Style_Ampersands_Fix;
use PHP_Typography\Fixes\Node_Fixes\Style_Caps_Fix;
use TopShelfCraft\Wordsmith\Wordsmith;

class TypographyService
{

	private $_typographer;
	private $_typographySettings;

	public function getTypographer(): \PHP_Typography\PHP_Typography
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

	public function getTypographySettings(array $adhocSettings = []): \PHP_Typography\Settings
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

	public function typogrify(?string $text, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process($text, $this->getTypographySettings($adhocSettings));
		return $result;
	}

	public function typogrifyFeed(string $text, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_feed($text, $this->getTypographySettings($adhocSettings));
		return $result;
	}

	public function smartypants(string $text, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Smart_Quotes_Fix()), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	public function widont($text, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Dewidow_Fix()), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	public function wrapAmps($text, string $class, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Style_Ampersands_Fix($class)), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

	public function wrapCaps($text, string $class, array $adhocSettings = []): string
	{
		if (empty($text))
		{
			return '';
		}
		$result = $this->getTypographer()->process_textnodes($text, [(new Style_Caps_Fix($class)), 'apply'], $this->getTypographySettings($adhocSettings));
		return $result;
	}

}
