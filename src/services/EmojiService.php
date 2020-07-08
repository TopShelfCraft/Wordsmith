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
use Stringy\Stringy;
use topshelfcraft\wordsmith\libs\Emoji;
use topshelfcraft\wordsmith\Wordsmith;


/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class EmojiService extends Component
{

	private $_emojiData;

	/**
	 * @return array
	 */
	public function getEmojiData()
	{

		if (!isset($this->_emojiData))
		{

			$data = json_decode(file_get_contents(Wordsmith::getInstance()->getBasePath() . '/libs/emoji.json'));

			$this->_emojiData = array_map(
				function ($entry) {

					$assumedName = !empty($entry->name) ? $entry->name : (string) Stringy::create($entry->short_name)->humanize()->toUpperCase();
					$snakeName = Stringy::create($assumedName)->toLowerCase()->replace('&', 'AND')->slugify('_');
					$constantName = (string) $snakeName->toUpperCase();
					$camelName = (string) Stringy::create($assumedName)->toLowerCase()->camelize();
					// TODO: I think toLowerCase is redundant before camelize...? ^^

					return [
						'name' => $entry->name,
						'unified' => $entry->unified,
						'nonQualified' => $entry->non_qualified,
						'text' => $entry->text,
						'texts' => $entry->texts,
						'shortName' => $entry->short_name,
						'shortNames' => $entry->short_names,
						'snakeName' => (string) $snakeName,
						'constantName' => $constantName,
						'camelName' => $camelName,
						'unicode' => constant('topshelfcraft\wordsmith\libs\Emoji::' . $constantName)
					];
				},
				$data
			);

			// GC this plz.
			unset($data);

		}

		return $this->_emojiData;

	}

	/**
	 * @param string $startDelimiter
	 * @param string $endDelimiter
	 * @return array
	 */
	public function getShortNameReplacements($startDelimiter = ':', $endDelimiter = ':')
	{

		$replacements = [];

		foreach ($this->getEmojiData() as $emoji)
		{
			if (!empty($emoji['shortNames']))
			{
				foreach ($emoji['shortNames'] as $shortName)
				{
					$replacements[$startDelimiter . $shortName . $endDelimiter] = $emoji['unicode'];
				}
			}
		}

		return $replacements;

	}

	/**
	 * @param string $startDelimiter
	 * @param string $endDelimiter
	 * @return array
	 */
	public function getSnakeNameReplacements($startDelimiter = ':', $endDelimiter = ':')
	{

		$replacements = [];

		foreach ($this->getEmojiData() as $emoji)
		{
			if (!empty($emoji['snakeName']))
			{
				$replacements[$startDelimiter . $emoji['snakeName'] . $endDelimiter] = $emoji['unicode'];
			}
		}

		return $replacements;

	}

	/**
	 * @param string $startDelimiter
	 * @param string $endDelimiter
	 * @return array
	 */
	public function getConstantNameReplacements($startDelimiter = ':', $endDelimiter = ':')
	{

		$replacements = [];

		foreach ($this->getEmojiData() as $emoji)
		{
			if (!empty($emoji['constantName']))
			{
				$replacements[$startDelimiter . $emoji['constantName'] . $endDelimiter] = $emoji['unicode'];
			}
		}

		return $replacements;

	}

	/**
	 * @param string $startDelimiter
	 * @param string $endDelimiter
	 * @return array
	 */
	public function getCamelNameReplacements($startDelimiter = ':', $endDelimiter = ':')
	{

		$replacements = [];

		foreach ($this->getEmojiData() as $emoji)
		{
			if (!empty($emoji['camelName']))
			{
				$replacements[$startDelimiter . $emoji['camelName'] . $endDelimiter] = $emoji['unicode'];
			}
		}

		return $replacements;

	}

	/**
	 * @param string $startDelimiter
	 * @param string $endDelimiter
	 * @return array
	 */
	public function getTextReplacements($startDelimiter = '', $endDelimiter = '')
	{

		$replacements = [];

		foreach ($this->getEmojiData() as $emoji)
		{
			if (!empty($emoji['texts']))
			{
				foreach ($emoji['texts'] as $text)
				{
					$replacements[$startDelimiter . $text . $endDelimiter] = $emoji['unicode'];
				}
			}
		}

		return $replacements;

	}

	/**
	 * @param $str
	 * @param string $nameStartDelimiter
	 * @param string $nameEndDelimiter
	 * @param string $textStartDelimiter
	 * @param string $textEndDelimiter
	 *
	 * @return string The parsed text
	 */
	public function emojify($str, $nameStartDelimiter = ':', $nameEndDelimiter = ":", $textStartDelimiter = '', $textEndDelimiter = '')
	{

		$sets = [
			$this->getShortNameReplacements($nameStartDelimiter, $nameEndDelimiter),
			$this->getSnakeNameReplacements($nameStartDelimiter, $nameEndDelimiter),
			$this->getConstantNameReplacements($nameStartDelimiter, $nameEndDelimiter),
			$this->getCamelNameReplacements($nameStartDelimiter, $nameEndDelimiter),
			$this->getTextReplacements($textStartDelimiter, $textEndDelimiter),
		];

		foreach ($sets as $set)
		{
			$str = str_replace(array_keys($set), $set, $str);
		}

		return $str;

	}

	/**
	 * For internal use only.
	 *
	 * We use this function to reformat data from the source-of-truth JSON in order to create the code for \libs\Emoji.php
	 *
	 * @return string
	 */
	public function generateEmojiPhpConstants()
	{

		$data = json_decode(file_get_contents(Wordsmith::getInstance()->getBasePath() . '/libs/emoji.json'));

		$data = array_map(
			function ($entry) {

				$assumedName = !empty($entry->name) ? $entry->name : (string) Stringy::create($entry->short_name)->humanize()->toUpperCase();
				$constantName = (string) Stringy::create($assumedName)->toLowerCase()->replace('&', 'AND')->slugify('_')->toUpperCase();

				$char = '';
				foreach (explode('-', $entry->unified) as $code)
				{
					$char .= '\u{' . $code . '}';
				}

				return [
					'constantName' => $constantName,
					'unicode' => $char,
				];
			},
			$data
		);

		$output = '';

		foreach ($data as $emoji)
		{
			$output .= "\n" . "const {$emoji['constantName']} = \"{$emoji['unicode']}\";";
		}

		return $output;

	}

}
