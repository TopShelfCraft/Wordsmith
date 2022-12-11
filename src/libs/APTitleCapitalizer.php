<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2020, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace TopShelfCraft\Wordsmith\libs;

/*
 * This file is based on the `CapitalizationHelper` class from the Craft Entitle plugin.
 * https://github.com/experience/entitle.craft-plugin/
 *
 * The original source code is (c) Experience
 * c.f. https://github.com/experience/entitle.craft-plugin/blob/master/LICENSE.txt
 */

use Stringy\Stringy;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class APTitleCapitalizer
{

	/**
	 * @var string[]
	 * @todo Rename to `minorWords`
	 */
	protected array $standardProtectedWords;

	/**
	 * @var string[]
	 * @todo Rename to `protectedWords`
	 */
	protected array $customProtectedWords;

	/**
	 * @param array $protectedWords Words that should not be transformed, no matter where they appear
	 * @param array $minorWords Words that should be lowercase, except if they are first/last in the title
	 */
	public function __construct(array $protectedWords = [], array $minorWords = [])
	{
		$this->standardProtectedWords = $minorWords;
		$this->setCustomProtectedWords($protectedWords);
	}

	/**
	 * Trims whitespace from the given words, and assigns the
	 * array to the "custom protected words" property.
	 *
	 * @param string[] $words
	 * @todo Rename to setProtectedWords()
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
	 */
	public function capitalize(?string $string): string
	{
		if (empty($string))
		{
			return '';
		}
		$string = $this->normalizeInput($string);
		$parts = $this->splitStringIntoParts($string);
		$parts = $this->processStringParts($parts);
		$parts = $this->processFirstSentenceWordsInParts($parts);
		$parts = $this->processLastWordInParts($parts);
		return $this->joinStringParts($parts);
	}

	/**
	 * Normalises the given string, ready for capitalisation.
	 */
	protected function normalizeInput(string $string): string
	{
		$string = Stringy::create($string)->collapseWhitespace();
		$string = $this->normalizeInputPunctuation($string->__toString());
		return Stringy::create($string);
	}

	/**
	 * Splits the given string into an array of sentence units.
	 *
	 * We want to capture words and spaces as discreet tokens.
	 * (Capturing spaces separately is important because otherwise they might mess up detection of sentence delimiters.)
	 *
	 * @return string[]
	 */
	protected function splitStringIntoParts(string $string): array
	{
		return preg_split(
			"/([\w\'\’]+)|(\s)+/u",
			$string,
			null,
			PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
		);
	}

	/**
	 * Processes the given array of string parts.
	 */
	protected function processStringParts(array $parts): array
	{
		array_walk($parts, function (&$part) {
			$part = $this->isWordLike($part) ? $this->processWord($part) : $part;
		});
		return $parts;
	}

	/**
	 * Processes the first words in any sentences within the given array of
	 * parts.
	 */
	protected function processFirstSentenceWordsInParts(array $parts): array
	{
		$length = count($parts);
		$isFirst = true;
		for ($index = 0; $index < $length; $index++) {
			$part = $parts[$index];
			if ($this->isSentenceDelimiter($part)) {
				$isFirst = true;
				continue;
			}
			if ($this->isWordLike($part)) {
				if ($isFirst) {
					$parts[$index] = $this->processFirstLastWord($part);
					$isFirst = false;
				}
			}
		}
		return $parts;
	}

	/**
	 * Processes the last word-like item in the given array of parts.
	 */
	protected function processLastWordInParts(array $parts): array
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
	 */
	protected function joinStringParts(array $parts): string
	{
		return implode('', $parts);
	}

	/**
	 * Ensures that punctuation characters have no leading spaces, and one
	 * trailing space.
	 */
	protected function normalizeInputPunctuation(string $string): string
	{
		return $this->replacePattern(
			$string,
			'/\s*([,;:])\s*(\w)/',
			'$1 $2'
		);
	}

	/**
	 * Returns a boolean indicating whether the given string looks, walks, and
	 * talks like a word: It starts with an alpha character, and contains only alpha characters and apostrophe(s).
	 */
	protected function isWordLike(string $string): bool
	{
		return (bool)preg_match(
			"/^[[:alpha:]]+[[:alpha:]\'\’]*$/u",
			$string
		);
	}

	/**
	 * Processes the given word.
	 */
	protected function processWord(string $word): string
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
	 */
	protected function processFirstLastWord(string $word): string
	{
		return $this->isCustomProtectedWord($word)
			? $word
			: $this->capitalizeWord($word);
	}

	/**
	 * Returns a boolean indicating whether the given string is a sentence
	 * delimiter.
	 */
	protected function isSentenceDelimiter(string $string): bool
	{
		return in_array($string, [
			'.', ',"',
			'?', '?"',
			'!', '!"',
			';'
		]);
	}

	/**
	 * Replaces all matches of the given regular expression pattern in the
	 * given string with the given replacement. If an error occurs, returns the
	 * original string.
	 */
	protected function replacePattern(string $string, string $pattern, string $replacement): string
	{
		$replaced = preg_replace($pattern, $replacement, $string);
		return is_null($replaced) ? $string : $replaced;
	}

	/**
	 * Returns a boolean indicating whether the given word is a standard
	 * protected word, which should not be capitalised.
	 */
	protected function isStandardProtectedWord(string $word): bool
	{
		return in_array(strtolower($word), $this->standardProtectedWords);
	}

	/**
	 * Lowercases the given word.
	 */
	protected function lowercaseWord(string $word): Stringy
	{
		return Stringy::create($word)->toLowerCase();
	}

	/**
	 * Returns a boolean indicating whether the given word is a custom
	 * protected word, which should not be modified.
	 */
	protected function isCustomProtectedWord(string $word): bool
	{
		return in_array($word, $this->customProtectedWords);
	}

	/**
	 * Capitalises the given word.
	 */
	protected function capitalizeWord(string $word): Stringy
	{
		return $this->lowercaseWord($word)->upperCaseFirst();
	}

}
