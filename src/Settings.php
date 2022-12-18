<?php
namespace TopShelfCraft\Wordsmith;

use craft\config\BaseConfig;

class Settings extends BaseConfig
{

	public const MINOR_TITLE_WORDS = [
		'a',
		'an',
		'and',
		'at',
		'as',
		'but',
		'by',
		'en',
		'for',
		'if',
		'in',
		'nor',
		'of',
		'on',
		'or',
		'so',
		'the',
		'to',
		'up',
		'yet',
		'via',
		'vs',
	];

	public const PROTECTED_TITLE_WORDS = [
		'ABBA',
		'ASAP',
		'CPR',
		'KFC',
		'ETA',
		'NZ',
		'PR',
		'RSVP',
		'UAE',
		'UK',
		'USA',
		'YOLO',
	];

	protected static array $renamedSettings = [
		'apTitleProtectedWords' => 'minorTitleWords',
	];

	/**
	 * @var string[] Whether to register Wordsmith functions as GraphQL directives. You can enable/disable all functions at once, or provide an (un-prefixed) list of functions to expose.
	 */
	public bool|array $gqlAllowFunctions = false;

	/**
	 * Set whether to register Wordsmith functions as GraphQL directives. You can enable/disable all functions at once,
	 * or provide an (unprefixed) list of functions to expose.
	 *
	 * ```php
	 * ->gqlAllowFunctions([...])
	 * ```
	 *
	 * @defaultAlt false
	 */
	public function gqlAllowFunctions(bool|array $value): self
	{
		$this->gqlAllowFunctions = $value;
		return $this;
	}

	/**
	 * @var string[] Words that should be lowercased in AP-format titles, except when they occur in first/last position
	 */
	public array $minorTitleWords = self::MINOR_TITLE_WORDS;

	/**
	 * Set words that should be lowercased in AP-format titles, except when they occur in first/last position.
	 *
	 * ```php
	 * ->minorTitleWords(...Settings::MINOR_TITLE_WORDS)
	 * ```
	 *
	 * @defaultAlt self::MINOR_TITLE_WORDS
	 */
	public function minorTitleWords(array $value): self
	{
		$this->minorTitleWords = $value;
		return $this;
	}

	/**
	 * @var string[] Words that should not be transformed at all when titlelizing
	 */
	public array $protectedTitleWords = self::PROTECTED_TITLE_WORDS;

	/**
	 * Set words that should not be transformed at all when titlelizing.
	 *
	 * ```php
	 * ->protectedTitleWords(...Settings::PROTECTED_TITLE_WORDS)
	 * ```
	 *
	 * @defaultAlt self::PROTECTED_TITLE_WORDS
	 */
	public function protectedTitleWords(array $value): self
	{
		$this->protectedTitleWords = $value;
		return $this;
	}

	/**
	 * @var string Prefix for Twig function/filter names
	 */
	public string $twigPrefix = '';

	/**
	 * Set the prefix for Twig function/filter names.
	 *
	 * ```php
	 * ->twigPrefix('wordsmith_')
	 * ```
	 *
	 * @defaultAlt ''
	 */
	public function twigPrefix(string $value): self
	{
		$this->twigPrefix = $value;
		return $this;
	}

	/**
	 * @var array Custom settings for typography functions
	 */
	public array $typographySettings = [];

	/**
	 * Set custom settings for typography functions.
	 *
	 * ```php
	 * ->typographySettings([])
	 * ```
	 */
	public function typographySettings(array $value): self
	{
		$this->typographySettings = $value;
		return $this;
	}

}
