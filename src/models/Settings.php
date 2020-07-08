<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2020, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace topshelfcraft\wordsmith\models;

use craft\base\Model;

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class Settings extends Model
{

	/*
	 * Public properties
	 */

	/**
	 * @var string
	 */
	public $twigPrefix = '';

	/**
	 * @var string
	 */
	public $apTitleProtectedWords = ['a', 'an', 'and', 'at', 'as', 'but', 'by', 'en', 'for', 'if', 'in', 'nor', 'of', 'on', 'or', 'so', 'the', 'to', 'up', 'yet', 'via', 'vs'];

	/**
	 * @var array
	 */
	public $typographySettings = [];

}
