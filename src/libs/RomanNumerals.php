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
 * This file is based on the `RomanNumeralTrait` class from GeckoPackages.
 * c.f. https://github.com/GeckoPackages/GeckoTwig
 *
 * The original source code is (c) GeckoPackages https://github.com/GeckoPackages
 * c.f. https://github.com/GeckoPackages/GeckoTwig/blob/2.x/LICENSE
 */

/**
 * Supports the Roman numerals in modern notation (strict) or loose notation.
 *
 * Roman | Value
 * ------+-------
 *  I    |    1
 *  IV   |    4
 *  V    |    5
 *  IX   |    9
 *  X    |   10
 *  XL   |   40
 *  L    |   50
 *  XC   |   90
 *  C    |  100
 *  CD   |  400
 *  D    |  500
 *  CM   |  900
 *  M    | 1000
 *
 * Note: large numbers, for example in 'apostrophus' and 'vinculum' are not supported.
 *
 * In 'strict' mode:
 * - Symbols are combined from left to right, high to low values.
 * - Symbols are not repeated more than 3 times.
 * - C may b placed after D or M.
 * - X may be placed before L or C.
 * - I may be placed before V or X.
 * - This makes the maximum number supported 'MMMCMXCIX'.
 *
 * In 'loose' mode, follows 'strict' mode with the following exceptions:
 * - Symbols may be repeated more than 3 times.
 * - There is no more maximum number.
 *
 * In 'loose-order', follows 'loose' mode with the following exception:
 * - Symbols may appear in any order.
 *
 * More details:
 * https://en.wikipedia.org/wiki/Roman_numerals
 *
 *
 * @author SpacePossum
 */
class RomanNumerals
{

	/**
	 * @param mixed $string       String to modify
	 * @param string $matchMode   'strict', 'loose' or 'loose-order'
	 * @param callable $callback  Callback that does the manipulation
	 *
	 * @return string
	 */
	public static function romanNumeralMatchCallback($string, $matchMode, callable $callback)
	{

		switch ($matchMode) {
			case 'loose':
			{
				// Note: empty strings are also captured.
				$matchMode = '#\b(M*(?:CM|CD|D?C*)(?:XC|XL|L?X*)(?:IX|IV|V?I*)\b)#i';
				break;
			}
			case 'loose-order':
			{
				$matchMode = '#\b([MDCLXVI]+)\b#i';
				break;
			}
			case 'strict':
			default:
			{
				// Note: empty strings are also captured.
				$matchMode = '#\b(M{0,3}(?:CM|CD|D?C{0,3})(?:XC|XL|L?X{0,3})(?:IX|IV|V?I{0,3})\b)#i';
				break;
			}
		}

		return preg_replace_callback($matchMode, $callback, $string);

	}

}
