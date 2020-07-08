<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2020, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace topshelfcraft\wordsmith\libs;

/*
 * This file is based on the `CapitalizationHelper` class from the Craft Entitle plugin.
 * https://github.com/experience/entitle.craft-plugin/
 *
 * The original source code is (c) Experience
 * c.f. https://github.com/experience/entitle.craft-plugin/blob/master/LICENSE.txt
 */

use Stringy\Stringy;
use topshelfcraft\wordsmith\Wordsmith;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class APTitleCapitalizer
{

	/**
	 * @var string[]
	 */
	protected $standardProtectedWords;

	/**
	 * @var string[]
	 */
	protected $customProtectedWords;

	/**
	 * Constructor.
	 *
	 * @param array $customProtectedWords
	 */
	public function __construct(array $customProtectedWords = [])
	{
		// TODO: Move the reference to Wordsmith's config out of the library to remove its dependence on Wordsmith.
		$this->standardProtectedWords = Wordsmith::getInstance()->getSettings()->apTitleProtectedWords;
		$this->setCustomProtectedWords($customProtectedWords);
	}

	/**
	 * Trims whitespace from the given words, and assigns the
	 * array to the "custom protected words" property.
	 *
	 * @param string[] $words
	 */
	public function setCustomProtectedWords(array $words)
	{
		$this->customProtectedWords = [];
		foreach ($words as $word) {
			$this->customProtectedWords[] = Stringy::create($word)->trim();
		}
	}

	/**
	 * Capitalizes the given string, according to AP rules.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function capitalize($string)
	{
		$string = $this->normalizeInput($string);
		$parts = $this->splitStringIntoParts($string);
		$parts = $this->processStringParts($parts);
		$parts = $this->processFirstSentenceWordsInParts($parts);
		$parts = $this->processLastWordInParts($parts);
		return $this->joinStringParts($parts);
	}

	/**
	 * Normalises the given string, ready for capitalisation.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected function normalizeInput($string)
	{
		$string = Stringy::create($string)->collapseWhitespace();
		$string = $this->normalizeInputPunctuation($string->__toString());
		return Stringy::create($string);
	}

	/**
	 * Splits the given string into an array of elements.
	 *
	 * // TODO: Make sure `splitStringIntoParts` is multi-byte safe
	 *
	 * @param string $string
	 *
	 * @return string[]
	 */
	protected function splitStringIntoParts($string)
	{
		return preg_split(
			"/([A-z]+[\-'’]{1}[A-z]+)|([A-z]+)/u",
			$string,
			null,
			PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
		);
	}

	/**
	 * Processes the given array of string parts.
	 *
	 * @param array $parts
	 *
	 * @return array
	 */
	protected function processStringParts(array $parts)
	{
		array_walk($parts, function (&$part) {
			$part = $this->isWordLike($part) ? $this->processWord($part) : $part;
		});
		return $parts;
	}

	/**
	 * Processes the first words in any sentences within the given array of
	 * parts.
	 *
	 * @param array $parts
	 *
	 * @return array
	 */
	protected function processFirstSentenceWordsInParts(array $parts)
	{
		$length = count($parts);
		$isFirst = true;
		for ($index = 0; $index < $length; $index++) {
			$part = $parts[$index];
			if ($this->isWordLike($part)) {
				if ($isFirst) {
					$parts[$index] = $this->processFirstLastWord($part);
					$isFirst = false;
				}
				continue;
			}
			if ($this->isSentenceDelimiter($part)) {
				$isFirst = true;
			}
		}
		return $parts;
	}

	/**
	 * Processes the last word-like item in the given array of parts.
	 *
	 * @param array $parts
	 *
	 * @return array
	 */
	protected function processLastWordInParts(array $parts)
	{
		$parts = array_reverse($parts);
		$length = count($parts);
		for ($index = 0; $index < $length; $index++) {
			$part = $parts[$index];
			if ($this->isWordLike($part)) {
				$parts[$index] = $this->processFirstLastWord($part);
				break;
			}
		}
		return array_reverse($parts);
	}

	/**
	 * Converts the array of string parts back into a string.
	 *
	 * @param array $parts
	 *
	 * @return string
	 */
	protected function joinStringParts(array $parts)
	{
		return implode('', $parts);
	}

	/**
	 * Ensures that punctuation characters have no leading spaces, and one
	 * trailing space.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected function normalizeInputPunctuation($string)
	{
		return $this->replacePattern(
			$string,
			'/\s*([,;:])\s*([A-z])/',
			'$1 $2'
		);
	}

	/**
	 * Returns a boolean indicating whether the given string looks, walks, and
	 * talks like a word.
	 *
	 * // TODO: Make `isWordLike` multi-byte safe
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	protected function isWordLike($string)
	{
		return (bool)preg_match(
			"/(^[A-z]+[\-'’]{1}[A-z]+$)|(^[A-z]+$)/u",
			$string
		);
	}

	/**
	 * Processes the given word.
	 *
	 * @param string $word
	 *
	 * @return string
	 */
	protected function processWord($word)
	{
		if ($this->isStandardProtectedWord($word)) {
			return $this->lowercaseWord($word);
		}
		if ($this->isCustomProtectedWord($word)) {
			return $word;
		}
		return $this->capitalizeWord($word);
	}

	/**
	 * Processes the first or last word in the sentence. The first and last
	 * word should always be capitalised, _unless_ it is a custom protected
	 * word.
	 *
	 * @param string $word
	 *
	 * @return string
	 */
	protected function processFirstLastWord($word)
	{
		return $this->isCustomProtectedWord($word)
			? $word
			: $this->capitalizeWord($word);
	}

	/**
	 * Returns a boolean indicating whether the given string is a sentence
	 * delimiter.
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	protected function isSentenceDelimiter($string)
	{
		return $string === '.';
	}

	/**
	 * Replaces all matches of the given regular expression pattern in the
	 * given string with the given replacement. If an error occurs, returns the
	 * original string.
	 *
	 * // TODO: Make `replacePattern` multi-byte safe
	 *
	 * @param string $string
	 * @param string $pattern
	 * @param string $replacement
	 *
	 * @return string
	 */
	protected function replacePattern($string, $pattern, $replacement)
	{
		$replaced = preg_replace($pattern, $replacement, $string);
		return is_null($replaced) ? $string : $replaced;
	}

	/**
	 * Returns a boolean indicating whether the given word is a standard
	 * protected word, which should not be capitalised.
	 *
	 * @param string $word
	 *
	 * @return bool
	 */
	protected function isStandardProtectedWord($word)
	{
		return in_array(strtolower($word), $this->standardProtectedWords);
	}

	/**
	 * Lowercases the given word.
	 *
	 * @param string $word
	 *
	 * @return Stringy
	 */
	protected function lowercaseWord($word)
	{
		return Stringy::create($word)->toLowerCase();
	}

	/**
	 * Returns a boolean indicating whether the given word is a custom
	 * protected word, which should not be modified.
	 *
	 * @param string $word
	 *
	 * @return bool
	 */
	protected function isCustomProtectedWord($word)
	{
		return in_array($word, $this->customProtectedWords);
	}

	/**
	 * Capitalises the given word.
	 *
	 * @param string $word
	 *
	 * @return Stringy
	 */
	protected function capitalizeWord($word)
	{
		return $this->lowercaseWord($word)->upperCaseFirst();
	}

}
