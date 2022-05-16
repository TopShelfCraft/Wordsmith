<?php
namespace TopShelfCraft\Wordsmith;

use craft\base\Model;

class Settings extends Model
{

	public string $twigPrefix = '';

	/**
	 * @var string[]
	 */
	public array $apTitleProtectedWords = ['a', 'an', 'and', 'at', 'as', 'but', 'by', 'en', 'for', 'if', 'in', 'nor', 'of', 'on', 'or', 'so', 'the', 'to', 'up', 'yet', 'via', 'vs'];

	public array $typographySettings = [];

}
