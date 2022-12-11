<?php
namespace TopShelfCraft\Wordsmith\libs;

/**
 * Tweetify library, originally created by Michael Rog for https://github.com/RogEE/Tweetify
 *
 * @internal In development; not rated for use yet.
 * @todo Make this multi-byte safe, and more general (not Twitter-specific).
 */
class Tweetify
{

	/**
	 * @param string $tweet
	 * @param string $class
     *
	 * @return string
	 */
	function tweetify($tweet = '', $class = null)
	{
		$tweet = $this->hash($tweet, $class);
		$tweet = $this->at($tweet, $class);
		$tweet = $this->url($tweet, $class);
		return $tweet;
	}

	/**
     * Link URLs in Tweet text
     *
	 * @param string $tweet
	 * @param string $class
     *
	 * @return string
	 */
	function url($tweet = '', $class = null)
	{

		$links = preg_replace('`(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))`si', '<a href="$1"'.(empty($class)?"":' class="'.$class.'"').' rel="nofollow">$1</a>', $tweet);

		return preg_replace('[href=\"www.]','href="https://www.',$links);

	}

	/**
     * Link @-handles in Tweet text
     *
	 * @param string $tweet
	 * @param string $class
     *
	 * @return string
	 */
	function at($tweet = '', $class = null)
	{
		return stripslashes(preg_replace("#(^|\W)@(\w{1,20})#ise", "'\\1@<a href=\"https://www.twitter.com/\\2\"".(empty($class)?"":' class="'.$class.'"').">\\2</a>'", $tweet));
	}

	/**
     * Link #-hashtags in Tweet text
     *
	 * @param string $tweet
	 * @param string $class
     *
	 * @return string
	 */
	function hash($tweet = '', $class = null)
	{
		return preg_replace("#(^|[\W])(?<!\&)\#(\w+)#ise", "'\\1<a href=\"https://twitter.com/search?q=%23\\2\"".(empty($class)?"":' class="'.$class.'"').">#\\2</a>'", $tweet);
	}

}
