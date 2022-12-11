<?php
namespace TopShelfCraft\Wordsmith\libs;

class Videos
{

	/**
	 * Returns the YouTube video ID from a URL, if one is present in the URL.
	 *
	 * c.f. https://gist.github.com/ghalusa/6c7f3a00fd2383e5ef33
	 * c.f. https://stackoverflow.com/questions/3452546/how-do-i-get-the-youtube-video-id-from-a-url
	 */
	public static function getYoutubeId(string $url): ?string
	{
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches))
		{
			return $matches[1];
		}
		if (preg_match("/^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/", $url, $matches))
		{
			return $matches[2];
		}
		return null;
	}

	/**
	 * Extracts the Vimeo video ID from a URL, if one is present.
	 *
	 * Vimeo uses four known URL formats:
	 *  - vimeo.com/[VideoID]
	 *  - vimeo.com/channels/[Channel]/[VideoID]
	 *  - vimeo.com/groups/[Group]/[VideoID]
	 *  - player.vimeo.com/video/[VideoID]
	 */
	public static function getVimeoId(string $url): ?string
	{
		if (preg_match("/vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $matches))
		{
			return $matches[3];
		}
		return null;
	}

}
