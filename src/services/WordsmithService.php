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
use DaveChild\TextStatistics\TextStatistics;
use ICanBoogie\Inflector;
use Stringy\Stringy;
use topshelfcraft\wordsmith\Word;
use topshelfcraft\wordsmith\Wordsmith;
use topshelfcraft\wordsmith\libs\APTitleCapitalizer;
use topshelfcraft\wordsmith\libs\FullNameParser;
use topshelfcraft\wordsmith\libs\Hacksaw;
use topshelfcraft\wordsmith\libs\RomanNumerals;
use topshelfcraft\wordsmith\libs\SubStringy\SubStringy;
use yii\helpers\Markdown;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class WordsmithService extends Component
{

	/*
     * Private properties
     * ===========================================================================
     */

	private $_methods = [

		// Documented methods

		'amp' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'apTitleize' => [
			'source' => 'internal', 'category' => 'casing'
		],
		'automatedReadabilityIndex' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'averageWordsPerSentence' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'between' => [
			'source' => 'Stringy', 'category' => 'substrings'
		],
		'camelize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'caps' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'chop' => [
			'source' => 'internal', 'category' => 'truncation', 'is_safe' => ['html']
		],
		'colemanLiauIndex' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'daleChallReadabilityScore' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'dasherize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'emojify' => [
			'source' => 'internal', 'category' => 'emoji'
		],
		'entitle' => [
			'source' => 'internal', 'category' => 'casing'
		],
		'firstName' => [
			'source' => 'FullNameParser', 'category' => 'names'
		],
		'firstWord' => [
			'source' => 'internal', 'category' => 'substrings'
		],
		'fleschKincaidReadingEase' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'fleschKincaidGradeLevel' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'givenName' => [
			'source' => 'FullNameParser', 'category' => 'names'
		],
		'gunningFogScore' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'hacksaw' => [
			'source' => 'internal', 'category' => 'truncation', 'is_safe' => ['html']
		],
		'humanize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'hyphenate' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'isStringy' => [
			'source' => 'internal', 'category' => 'utilities'
		],
		'lastName' => [
			'source' => 'FullNameParser', 'category' => 'names'
		],
		'lastWord' => [
			'source' => 'internal', 'category' => 'substrings'
		],
		'lowerCaseRoman' => [
			'source' => 'internal', 'category' => 'roman-numerals'
		],
		'markdown' => [
			'source' => 'internal', 'category' => 'markdown', 'is_safe' => ['html']
		],
		'md' => [
			'source' => 'internal', 'category' => 'markdown', 'is_safe' => ['html']
		],
		'ordinal' => [
			'source' => 'Inflector', 'category' => 'inflection'
		],
		'ordinalize' => [
			'source' => 'Inflector', 'category' => 'inflection'
		],
		'parsedown' => [
			'source' => 'internal', 'category' => 'markdown', 'is_safe' => ['html']
		],
		'parsedownExtra' => [
			'source' => 'internal', 'category' => 'markdown', 'is_safe' => ['html']
		],
		'parseName' => [
			'source' => 'FullNameParser', 'category' => 'names'
		],
		'parseUrl' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'pde' => [
			'source' => 'internal', 'category' => 'markdown', 'is_safe' => ['html']
		],
		'pascalize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'pluralize' => [
			'source' => 'Inflector', 'category' => 'inflection'
		],
		'readTime' => [
			'source' => 'internal', 'category' => 'statistics'
		],
		'sentenceCount' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'singularize' => [
			'source' => 'Inflector', 'category' => 'inflection'
		],
		'slugify' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'smartypants' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'smogIndex' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'spacheReadabilityScore' => [
			'source' => 'TextStatistics', 'category' => 'statistics'
		],
		'substringAfterFirst' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'substringAfterLast' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'substringBeforeFirst' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'substringBeforeLast' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'substringBetween' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'substringCount' => [
			'source' => 'SubStringy', 'category' => 'substrings'
		],
		'surname' => [
			'source' => 'FullNameParser', 'category' => 'names'
		],
		'titleize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'typogrify' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'typogrifyFeed' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'underscore' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'underscored' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'upperCamelize' => [
			'source' => 'Stringy', 'category' => 'casing'
		],
		'upperCaseRoman' => [
			'source' => 'internal', 'category' => 'roman-numerals'
		],
		'urlFragment' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlHost' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlPass' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlPath' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlPort' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlQuery' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlScheme' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'urlUser' => [
			'source' => 'internal', 'category' => 'urls'
		],
		'trim' => [
			'source' => 'internal', 'category' => 'truncation'
		],
		'trimLeft' => [
			'source' => 'internal', 'category' => 'truncation'
		],
		'trimRight' => [
			'source' => 'internal', 'category' => 'truncation'
		],
		'wc' => [
			'source' => 'internal', 'category' => 'statistics'
		],
		'widont' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'wrapAmps' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'wrapCaps' => [
			'source' => 'PHP-Typography', 'category' => 'typography', 'is_safe' => ['html']
		],
		'wordcount' => [
			'source' => 'internal', 'category' => 'statistics'
		],
		'youtubeId' => [
			'source' => 'internal', 'category' => 'urls'
		],

		// Internal junk methods

		'test' => [
			'source' => 'internal', 'category' => 'internal'
		],

	];

	/*
	 * Public internal methods
	 * ===========================================================================
	 */

	/**
	 * @return array
	 */
	public function getMethodList()
	{
		return $this->_methods;
	}

	/*
	 * Public API methods
	 * a.k.a The ever-lovin Beef
	 * ===========================================================================
	 */

	/**
	 * Alias for `wrapAmps()`
	 *
	 * @param string $s
	 * @param string $class
	 * @return string
	 */
	public function amp($s, $class = 'amp'): string
	{
		return $this->wrapAmps($s, $class);
	}

	/**
	 * Intelligently up-cases a string as a headline/title using AP capitalization rules.
	 *
	 * @param string $s
	 * @param array $protectedWords
	 * @return string
	 */
	public function apTitleize($s, $protectedWords = []): string
	{
		return (new APTitleCapitalizer($protectedWords))->capitalize($s);
	}

	/**
	 * Gives the Automated Readability Index of the text, from 0 to 12.
	 *
	 * @param $s
	 * @return int
	 */
	public function automatedReadabilityIndex($s): int
	{
		return (new TextStatistics())->automatedReadabilityIndex($s);
	}

	/**
	 * Returns the average number of words per sentence in the text.
	 *
	 * @param $s
	 * @return int
	 */
	public function averageWordsPerSentence($s): int
	{
		return (new TextStatistics())->averageWordsPerSentence($s);
	}

	/**
	 * Returns the substring between $start and $end, if found, or an empty
	 * string. An optional offset may be supplied from which to begin the
	 * search for the start string.
	 *
	 * c.f. Stringy::between()
	 *
	 * @param string $start Delimiter marking the start of the substring
	 * @param string $end Delimiter marking the end of the substring
	 * @param int $offset Index from which to begin the search
	 *
	 * @return string
	 */
	public function between($s, $start, $end, int $offset = 0): string
	{
		return (string) Stringy::create($s)->between($start, $end, $offset);
	}

	/**
	 * Returns a camelCase version of the string.
	 *
	 * c.f. Stringy::camelize()
	 *
	 * @param string $s
	 * @return string
	 */
	public function camelize($s): string
	{
		return (string) Stringy::create($s)->camelize();
	}

	/**
	 * Alias for `wrapCaps()`
	 *
	 * @param string $s
	 * @param string $class
	 * @return string
	 */
	public function caps($s, $class = 'caps'): string
	{
		return $this->wrapCaps($s, $class);
	}

	/**
	 * Helps chop your content down to a manageable size.
	 *
	 * @param $s
	 * @param int $limit
	 * @param string $unit
	 * @param null $append
	 * @param null $allowedTags
	 * @return string
	 */
	public function chop($s, $limit = 1, $unit = 'p', $append = null, $allowedTags = null): string
	{
		return (new Hacksaw())->chop($s, $limit, $unit, $append, $allowedTags);
	}

	/**
	 * Gives the Coleman-Liau Index of the text, from 0 to 12.
	 *
	 * @param $s
	 * @return int
	 */
	public function colemanLiauIndex($s): int
	{
		return (new TextStatistics())->colemanLiauIndex($s);
	}

	/**
	 * Gives the Dale-Chall readability score of the text, from 0 to 10.
	 *
	 * @param $s
	 * @return int
	 */
	public function daleChallReadabilityScore($s): int
	{
		return (new TextStatistics())->daleChallReadabilityScore($s);
	}

	/**
	 * Returns a lowercase and trimmed string separated by dashes.
	 *
	 * c.f. Stringy::dasherize()
	 *
	 * @param string $s
	 * @return string
	 */
	public function dasherize($s): string
	{
		return (string) Stringy::create($s)->dasherize();
	}

	/**
	 * Replaces emoticons and Emoji name codes in text with their actual Unicode Emoji characters.
	 *
	 * @param $s
	 * @param string $name_delimiter
	 * @param string $emoticon_delimiter
	 *
	 * @return string
	 */
	public function emojify($s, $name_delimiter = ':', $emoticon_delimiter = ''): string
	{
		return Wordsmith::getInstance()->emoji->emojify($s, $name_delimiter, $name_delimiter, $emoticon_delimiter, $emoticon_delimiter);
	}

	/**
	 * Alias for `apTitleize()`
	 *
	 * @param $s
	 * @return string
	 */
	public function entitle($s): string
	{
		return $this->apTitleize($s);
	}

	/**
	 * Alias for `givenName()`
	 *
	 * Attempts to parse a string as a name and returns the first/given name component.
	 *
	 * @param $s
	 * @return string
	 */
	public function firstName($s): string
	{
		return $this->givenName($s);
	}

	/**
	 * Returns the first word in the string, or an empty string if the source string is empty.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function firstWord($s)
	{
		if (empty($s))
		{
			return null;
		}
		return (Stringy::create($s)->collapseWhitespace()->split(' ', 2))[0];
	}

	/**
	 * Gives the Flesch-Kincaid Reading Ease of the text, from 0 to 100.
	 *
	 * @param $s
	 * @return int
	 */
	public function fleschKincaidReadingEase($s): int
	{
		return (new TextStatistics())->fleschKincaidReadingEase($s);
	}

	/**
	 * Gives the Flesch-Kincaid Grade level of the text, from 0 to 12.
	 *
	 * @param $s
	 * @return int
	 */
	public function fleschKincaidGradeLevel($s): int
	{
		return (new TextStatistics())->fleschKincaidGradeLevel($s);
	}

	/**
	 * Gives the Gunning-Fog score of the text, from 0 to 19.
	 *
	 * @param $s
	 * @return int
	 */
	public function gunningFogScore($s): int
	{
		return (new TextStatistics())->gunningFogScore($s);
	}

	/**
	 * Attempts to parse a string as a name and returns the first/given name component.
	 *
	 * @param $s
	 * @return string
	 */
	public function givenName($s): string
	{
		$parts = $this->parseName($s);
		return $parts['givenName'];
	}

	/**
	 * Alias for `chop()`
	 *
	 * Provides additional parameters beyond the `chop` method in order to provide
	 * backwards compatibility with the old Hacksaw plugins.
	 *
	 * @param $s
	 * @param string $unit
	 * @param int $limit
	 * @param null $allowedTags
	 * @param null $append
	 * @param null $hack
	 * @param null $allow
	 * @param null $chars
	 * @param null $chars_start
	 * @param null $words
	 * @return string
	 */
	public function hacksaw($s, $unit = 'p', $limit = 1, $allowedTags = null, $append = null, $hack = null, $allow = null, $chars = null, $chars_start = null, $words = null): string
	{
		if ($hack !== null)
		{
			$unit = $hack;
		}

		if ($allow !== null)
		{
			$allowedTags = $allow;
		}

		if ($chars !== null)
		{

			$unit = 'c';
			$limit = $chars;

			if ($chars_start !== null)
			{
				$s = mb_substr($s, $chars_start);
			}

		}

		if ($words !== null)
		{
			$unit = 'w';
			$limit = $words;
		}

		return $this->chop($s, $limit, $unit, $append, $allowedTags);
	}

	/**
	 * Capitalizes the first word of the string, replaces underscores with spaces, and strips away an '_id' suffix if
	 * one is present.
	 *
	 * c.f. Stringy::humanize()
	 *
	 * @param string $s
	 * @return string
	 */
	public function humanize($s): string
	{
		return Stringy::create($s)->humanize();
	}

	/**
	 * Alias for `dasherize()`
	 *
	 * @param string $s
	 * @return string
	 */
	public function hyphenate($s): string
	{
		return $this->dasherize($s);
	}

	/**
	 * Returns whether a thingy is stringy.
	 *
	 * Thingies that are stringy include: a string, a printable number, or a string-castable object
	 *
	 * @param $thingy
	 * @return bool
	 */
	public function isStringy($thingy): bool
	{
		return (
			is_string($thingy)
			|| is_numeric($thingy)
			|| (is_object($thingy) && method_exists($thingy, '__toString'))
		);
	}

	/**
	 * Alias for `surname()`
	 *
	 * Attempts to parse a string as a name and returns the surname (e.g. 'last name') component.
	 *
	 * @param $s
	 * @return string
	 */
	public function lastName($s): string
	{
		return $this->surname($s);
	}

	/**
	 * Returns the last word in the string, or an empty string if the source string is empty.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function lastWord($s)
	{
		if (empty($s))
		{
			return null;
		}
		$words = Stringy::create($s)->collapseWhitespace()->split(' ');
		return (string) current(end($words));
	}

	/**
	 * Lowercases any Roman numerals found in the string.
	 *
	 * @param $s
	 * @param string $matchMode
	 * @return string
	 */
	public function lowerCaseRoman($s, $matchMode = 'strict'): string
	{
		$result = RomanNumerals::romanNumeralMatchCallback(
			$s,
			$matchMode,
			function (array $matches) {
				if (empty($matches[1]))
				{
					return $matches[1];
				}
				return strtolower($matches[1]);
			}
		);
		return $result;
	}

	/**
	 * Parses text through Markdown.
	 *
	 * @param $s
	 * @param string $flavor
	 * @param bool $inlineOnly
	 * @return string
	 */
	public function markdown($s, $flavor = 'gfm', $inlineOnly = false): string
	{

		// If flavor is 'extra' we're looking for Parsedown

		if ($flavor == 'extra')
		{

			if ($inlineOnly)
			{
				return (new \ParsedownExtra())->line($s);
			}
			else
			{
				return (new \ParsedownExtra())->text($s);
			}

		}

		// Otherwise, use Craft's built-in Yii-powered parser

		$flavor = ($flavor == 'yii-extra' ? 'extra' : $flavor);

		if ($inlineOnly)
		{
			return Markdown::processParagraph($s, $flavor);
		}
		else
		{
			return Markdown::process($s, $flavor);
		}

	}

	/**
	 * Alias for `markdown()`
	 *
	 * @param $s
	 * @param string $flavor
	 * @param bool $inlineOnly
	 * @return string
	 */
	public function md($s, $flavor = 'gfm', $inlineOnly = false): string
	{
		return $this->markdown($s, $flavor, $inlineOnly);
	}

	/**
	 * Returns the ordinal suffix for the given number.
	 *
	 * @param $num
	 * @return string
	 */
	public function ordinal($num): string
	{
		return Inflector::get()->ordinal($num);
	}

	/**
	 * Returns the ordinalized variation of the given number.
	 *
	 * @param $num
	 * @return string
	 */
	public function ordinalize($num): string
	{
		return Inflector::get()->ordinalize($num);
	}

	/**
	 * Alias for `markdown()` using the 'gfm' flavor.
	 *
	 * @param $s
	 * @param bool $inlineOnly
	 * @return string
	 */
	public function parsedown($s, $inlineOnly = false): string
	{
		return $this->markdown($s, 'gfm', $inlineOnly);
	}

	/**
	 * Alias for `markdown()` using the 'extra' flavor.
	 *
	 * @param $s
	 * @param bool $inlineOnly
	 * @return string
	 */
	public function parsedownExtra($s, $inlineOnly = false): string
	{
		return $this->markdown($s, 'extra', $inlineOnly);
	}

	/**
	 * Attempts to parse a string as a name and returns its component parts (i.e. firstName, lastName, etc.)
	 *
	 * c.f. FullNameParser::parse()
	 *
	 * (We hijack the return to provide nicer key names than FullNameParser uses out of the box.)
	 *
	 * @param $s
	 * @return array
	 */
	public function parseName($s): array
	{

		$parts = FullNameParser::parse($s);

		return [
			'nickname' => $parts['nickname'] ?? '',
			'salutation' => $parts['salutation'],
			'givenName' => $parts['fname'],
			'firstName' => $parts['fname'],
			'initials' => $parts['initials'],
			'lastName' => $parts['lname'],
			'surname' => $parts['lname'],
			'lastNameBase' => $parts['lname_base'],
			'surnameBase' => $parts['lname_base'],
			'lastNameCompound' => $parts['lname_compound'],
			'surnameCompound' => $parts['lname_compound'],
			'suffix' => $parts['suffix'],
		];

	}

	/**
	 * Attempts to parse a string as a URL and returns its component parts (i.e. scheme, host, path, etc.)
	 *
	 * @param $s
	 * @return array
	 */
	public function parseUrl($s): array
	{

		$parts = parse_url($s);

		return [
			'scheme' => $parts['scheme'] ?? null,
			'host' => $parts['host'] ?? null,
			'port' => $parts['port'] ?? null,
			'user' => $parts['user'] ?? null,
			'pass' => $parts['pass'] ?? null,
			'path' => $parts['path'] ?? null,
			'query' => $parts['query'] ?? null,
			'fragment' => $parts['fragment'] ?? null,
		];

	}

	/**
	 * Alias for `parsedownExtra()`
	 *
	 * @param $s
	 * @param bool $inlineOnly
	 * @return string
	 */
	public function pde($s, $inlineOnly = false): string
	{
		return $this->parsedownExtra($s, $inlineOnly);
	}

	/**
	 * Alias for `upperCamelize()`
	 *
	 * @param string $s
	 * @return string
	 */
	public function pascalize($s): string
	{
		return $this->upperCamelize($s);
	}

	/**
	 * Returns the correct singular/plural form of the given word, for the provided quantity and language.
	 *
	 * @param $s
	 * @param int $qty
	 * @param string $locale
	 * @return string
	 */
	public function pluralize($s, $qty = 2, $locale = 'en'): string
	{
		if (intval($qty) != 1)
		{
			return Inflector::get($locale)->pluralize($s);
		}
		return Inflector::get($locale)->singularize($s);
	}

	/**
	 * Calculates the units of time required for an average person to read the given passage of text.
	 *
	 * // TODO: Add label and inflection
	 *
	 * @param string $s
	 * @param int $rate
	 * @param int $minimum
	 * @return mixed
	 */
	public function readTime($s, $rate = 200, $minimum = 1)
	{
		$words = $this->wordcount($s);
		return max(intval($minimum), floor($words / intval($rate)));
	}

	/**
	 * Returns an approximate count of sentences in the text.
	 *
	 * @param $s
	 * @return int
	 */
	public function sentenceCount($s): int
	{
		return (new TextStatistics())->sentenceCount($s);
	}

	/**
	 * Returns the singular form of the given word.
	 *
	 * @param $s
	 * @param string $locale
	 * @return string
	 */
	public function singularize($s, $locale = 'en'): string
	{
		return Inflector::get($locale)->singularize($s);
	}

	/**
	 * Converts the string into an URL slug.
	 *
	 * c.f. Stringy::slugify()
	 *
	 * @param string $s
	 * @return string
	 */
	public function slugify($s): string
	{
		return (string) Stringy::create($s)->slugify();
	}

	/**
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @return string
	 */
	public function smartypants($s, $settings = []): string
	{
		return Wordsmith::getInstance()->typography->smartypants($s, $settings);
	}

	/**
	 * Gives the SMOG Index of the text, from 0 to 12.
	 *
	 * @param $s
	 * @return int
	 */
	public function smogIndex($s): int
	{
		return (new TextStatistics())->smogIndex($s);
	}

	/**
	 * Gives the Spache readability score of the text, from 0 to 5.
	 * (This scale is not really suitable for measuring readability above grade 4.)
	 *
	 * @param $s
	 * @return int
	 */
	public function spacheReadabilityScore($s): int
	{
		return (new TextStatistics())->spacheReadabilityScore($s);
	}

	/**
	 * Gets the substring after the first occurrence of a separator.
	 * If no match is found returns an empty string.
	 *
	 * (We're hijacking the return from SubStringy to return a `null` instead of boolean `false` if the substring isn't matched.)
	 *
	 * c.f. SubStringy::substringAfterFirst()
	 *
	 * @param string $s
	 * @param string $separator
	 *
	 * @return string|null
	 */
	public function substringAfterFirst($s, $separator)
	{
		$s = SubStringy::create($s)->substringAfterFirst($separator);
		return ($s === false ? null : $s);
	}

	/**
	 * Gets the substring after the last occurrence of a separator.
	 * If no match is found returns an empty string.
	 *
	 * (We're hijacking the return from SubStringy to return a `null` instead of boolean `false` if the substring isn't matched.)
	 *
	 * c.f. SubStringy::substringAfterLast()
	 *
	 * @param string $s
	 * @param string $separator
	 *
	 * @return Word
	 */
	public function substringAfterLast($s, $separator)
	{
		$s = SubStringy::create($s)->substringAfterLast($separator);
		return ($s === false ? null : $s);
	}

	/**
	 * Gets the substring before the first occurrence of a separator.
	 * If no match is found returns an empty string.
	 *
	 * (We're hijacking the return from SubStringy to return a `null` instead of boolean `false` if the substring isn't matched.)
	 *
	 * c.f. SubStringy::substringBeforeFirst()
	 *
	 * @param string $s
	 * @param string $separator
	 *
	 * @return Word
	 */
	public function substringBeforeFirst($s, $separator)
	{
		$s = SubStringy::create($s)->substringBeforeFirst($separator);
		return ($s === false ? null : $s);
	}

	/**
	 * Gets the substring before the last occurrence of a separator.
	 * If no match is found returns an empty string.
	 *
	 * (We're hijacking the return from SubStringy to return a `null` instead of boolean `false` if the substring isn't matched.)
	 *
	 * c.f. SubStringy::substringBeforeLast()
	 *
	 * @param string $s
	 * @param string $separator
	 *
	 * @return Word
	 */
	public function substringBeforeLast($s, $separator)
	{
		$s = SubStringy::create($s)->substringBeforeLast($separator);
		return ($s === false ? null : $s);
	}

	/**
	 * Count occurrences of the substring in the source string.
	 *
	 * @param string $s
	 * @param string $substring
	 *
	 * c.f. SubStringy::substringCount()
	 *
	 * @return int
	 */
	public function substringCount($s, $substring): int
	{
		return SubStringy::create($s)->substringCount($substring);
	}

	/**
	 * Attempts to parse a string as a name and returns the surname (i.e. 'last name') component.
	 *
	 * @param $s
	 * @return string
	 */
	public function surname($s): string
	{
		$parts = $this->parseName($s);
		return $parts['surname'];
	}

	/**
	 * Returns a trimmed string with the first letter of each word capitalized.
	 *
	 * c.f. Stringy::titleize()
	 *
	 * @param string $s
	 * @param array $ignore
	 * @return string
	 */
	public function titleize($s, $ignore = []): string
	{
		return (string) Stringy::create($s)->titleize($ignore);
	}

	/**
	 * Returns a string with whitespace removed from the start and end of the string.
	 *
	 * c.f. Stringy::trim()
	 *
	 * @param string $s
	 * @param string $chars
	 * @return string
	 */
	public function trim($s, $chars = null): string
	{
		return (string) Stringy::create($s)->trim($chars);
	}

	/**
	 * Returns a string with whitespace removed from the start of the string.
	 *
	 * c.f. Stringy::trimLeft()
	 *
	 * @param string $s
	 * @param string $chars
	 * @return string
	 */
	public function trimLeft($s, $chars = null): string
	{
		return (string) Stringy::create($s)->trimLeft($chars);
	}

	/**
	 * Returns a string with whitespace removed from the end of the string.
	 *
	 * c.f. Stringy::trimRight()
	 *
	 * @param string $s
	 * @param string $chars
	 * @return string
	 */
	public function trimRight($s, $chars = null): string
	{
		return (string) Stringy::create($s)->trimRight($chars);
	}

	/**
	 * Typogrify applies a full suite of typographic treatments to beautify your web typography.
	 *
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @return string
	 */
	public function typogrify($s, $settings = []): string
	{
		return Wordsmith::getInstance()->typography->typogrify($s, $settings);
	}

	/**
	 * Applies a full suite of typographic treatments to beautify your web typography,
	 * in a way that is appropriate for RSS feeds (i.e. excluding processes that may not display well
	 * with limited character set intelligence).
	 *
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @return string
	 */
	public function typogrifyFeed($s, $settings = []): string
	{
		return Wordsmith::getInstance()->typography->typogrifyFeed($s, $settings);
	}

	/**
	 * Returns a lowercase and trimmed string separated by underscores.
	 *
	 * c.f. Stringy::underscored()
	 *
	 * @param string $s
	 * @return string
	 */
	public function underscore($s): string
	{
		return (string) Stringy::create($s)->underscored();
	}

	/**
	 * Alias for `underscore()`
	 *
	 * @param string $s
	 * @return string
	 */
	public function underscored($s)
	{
		return $this->underscore($s);
	}

	/**
	 * Uppercases any Roman numerals found in the string.
	 *
	 * @param $s
	 * @param string $match_mode
	 * @return string
	 */
	public function upperCaseRoman($s, $match_mode = 'strict'): string
	{
		$result = RomanNumerals::romanNumeralMatchCallback(
			$s,
			$match_mode,
			function (array $matches) {
				if (empty($matches[1]))
				{
					return $matches[1];
				}
				return strtoupper($matches[1]);
			}
		);
		return $result;
	}

	/**
	 * Returns an UpperCamelCase version of the supplied string.
	 *
	 * c.f. Stringy::upperCamelize()
	 *
	 * @param string $s
	 * @return string
	 */
	public function upperCamelize($s): string
	{
		return (string) Stringy::create($s)->upperCamelize();
	}

	/**
	 * Returns the fragment component of a URL. (i.e. the part after a hashmark '#', which usually represents an anchor
	 * on the target page.)
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlFragment($s)
	{
		return ($this->parseUrl($s))['fragment'];
	}

	/**
	 * Returns the host name component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlHost($s)
	{
		return ($this->parseUrl($s))['host'];
	}

	/**
	 * Returns the password component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlPass($s)
	{
		return ($this->parseUrl($s))['pass'];
	}

	/**
	 * Returns the resource path component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlPath($s)
	{
		return ($this->parseUrl($s))['path'];
	}

	/**
	 * Returns the port number component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlPort($s)
	{
		return ($this->parseUrl($s))['port'];
	}

	/**
	 * Returns the query string component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlQuery($s)
	{
		return ($this->parseUrl($s))['query'];
	}

	/**
	 * Returns the protocol component of a URL (e.g. 'http', 'https').
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlScheme($s)
	{
		return ($this->parseUrl($s))['scheme'];
	}

	/**
	 * Returns the user component of a URL.
	 *
	 * @param $s
	 * @return string|null
	 */
	public function urlUser($s)
	{
		return ($this->parseUrl($s))['user'];
	}

	/**
	 * Alias for `wordcount()`
	 *
	 * @param $s
	 * @return int
	 */
	public function wc($s): int
	{
		return $this->wordcount($s);
	}

	/**
	 * Replaces the space between the last two words in a string with a `&nbsp;` to prevent widowing.
	 *
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @param array $settings
	 * @return string
	 */
	public function widont($s, $settings = []): string
	{
		return Wordsmith::getInstance()->typography->widont($s, $settings);
	}

	/**
	 * Wraps ampersands in `<span class="amp">` so they can be styled with CSS.
	 *
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @param string $class
	 * @param array $settings
	 * @return string
	 */
	public function wrapAmps($s, $class = 'amp', $settings = []): string
	{
		return Wordsmith::getInstance()->typography->wrapAmps($s, $class, $settings);
	}

	/**
	 * Wraps all-uppercase words in `<span class="caps">` so they can be styled with CSS.
	 *
	 * c.f. PHP-Typography
	 *
	 * @param string $s
	 * @param string $class
	 * @param array $settings
	 * @return string
	 */
	public function wrapCaps($s, $class = 'caps', $settings = []): string
	{
		return Wordsmith::getInstance()->typography->wrapCaps($s, $class, $settings);
	}

	/**
	 * // TODO: Document `wordcount`
	 *
	 * @param $s
	 * @return int
	 */
	public function wordcount($s): int
	{
		return Stringy::create(strip_tags($s))->collapseWhitespace()->countSubstr(' ') + 1;
	}

	/**
	 * Returns the YouTube video ID from a URL, if one is present in the URL.
	 *
	 * c.f. https://gist.github.com/ghalusa/6c7f3a00fd2383e5ef33
	 * c.f. https://stackoverflow.com/questions/3452546/how-do-i-get-the-youtube-video-id-from-a-url
	 *
	 * @param $s
	 * @return string|null
	 */
	public function youtubeId($s)
	{
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $s, $matches))
		{
			return $matches[1];
		}
//		if (preg_match("/^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/", $s, $matches))
//		{
//			return $matches[2];
//		}
		return null;
	}

	/*
	 * TEST METHODS
	 *
	 */

	/**
	 * @param null $arg1
	 * @param null $arg2
	 * @param null $arg3
	 * @param null $arg4
	 * @return mixed
	 */
	public function test($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
	{
		return print_r(
			[$arg1, $arg2, $arg3, $arg4],
			true
		);
	}

	public function testEmptyStringy()
	{
		$stringy = Stringy::create('') ? 'not empty' : 'empty';
		$string = '' ? 'not empty' : 'empty';
		var_dump([$stringy, $string]);
	}

}
