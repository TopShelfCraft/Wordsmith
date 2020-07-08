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
 * This class is inspired by the Hacksaw plugin for ExpressionEngine, by Brett DeWoody
 * https://devot-ee.com/add-ons/hacksaw
 *
 * Hacksaw was originally ported to Craft by Ryan Shrum, and additional inspiration for this class is drawn from his Craft plugin:
 * https://github.com/ryanshrum/hacksaw
 * The MIT License (MIT)
 * Copyright (c) 2016 Ryan Shrum
 *
 */

/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class Hacksaw
{

	public function chop($content, $limit = 1, $unit = 'p', $append = null, $allowedTags = null)
	{

		if ($unit == 'c' || $unit == 'chars' || $unit == 'characters')
		{

			$plainText = $this->_cleanHtml($content);
			$cleanContent = $this->_cleanHtml($content, $allowedTags);
			if (mb_strlen($plainText) <= $limit)
			{
				$return = $cleanContent;
			}
			else
			{
				$return = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($cleanContent, 0, $limit)) . $append;
			}

			return $this->_closeTags($return);

		}
		else if ($unit == 'w' || $unit == 'words')
		{

			$plainText = $this->_cleanHtml($content);
			$cleanContent = $this->_cleanHtml($content, $allowedTags);

			if (str_word_count($plainText) <= $limit)
			{
				$return = $cleanContent;
			}
			else
			{
				$wordCount = str_word_count($cleanContent, 0);
				if ($wordCount > $limit)
				{
					$words = preg_split('/\s+/u', $cleanContent);
					$cleanContent = implode(' ', array_slice($words, 0, $limit));
					$return = $cleanContent;
					if (preg_match("/[0-9.!?,;:]$/u", $cleanContent))
					{
						$return = mb_substr($cleanContent, 0, -1);
					}
					$return .= $append;
				}
			}

			return $this->_closeTags($return);

		}
		else if ($unit = 'p' || $unit == 'paragraphs')
		{

			$cleanContent = $this->_cleanHtml($content, $allowedTags . "<p>");
			$paragraphs = array_filter(explode("<p>", str_replace("</p>", "", $cleanContent)));
			$paragraphs = array_slice($paragraphs, 0, $limit);
			$paragraphsCount = count($paragraphs)-1;
			$return = "";
			foreach ($paragraphs as $key => $paragraph)
			{
				$return .= "<p>" . $paragraph;
				if ($key < $paragraphsCount)
				{
					$return .= "</p>";
				}
			}
			$return .= $append . "</p>";

			return $return;

		}

	}

	private function _cleanHtml($content, $allowedTags = null)
	{
		return strip_tags($content, $allowedTags);
	}

	private function _closeTags($content) {

		preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $content, $result);
		$openedTags = $result[1];

		preg_match_all('#</([a-z]+)>#iU', $content, $result);
		$closedTags = $result[1];

		$openTagCount = count($openedTags);

		if (count($closedTags) == $openTagCount) {
			return $content;
		}

		$openedTags = array_reverse($openedTags);

		for ($i=0; $i < $openTagCount; $i++) {
			if (!in_array($openedTags[$i], $closedTags)) {
				$content .= '</'.$openedTags[$i].'>';
			} else {
				unset($closedTags[array_search($openedTags[$i], $closedTags)]);
			}
		}

		return $content;

	}

}
