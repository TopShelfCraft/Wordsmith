<?php
/**
 * Wordsmith
 *
 * @author     Michael Rog <michael@michaelrog.com>
 * @link       https://topshelfcraft.com
 * @copyright  Copyright 2017, Top Shelf Craft (Michael Rog)
 * @see        https://github.com/topshelfcraft/Wordsmith
 */

namespace topshelfcraft\wordsmith\twigextensions;

use topshelfcraft\wordsmith\Wordsmith;

use Craft;
use Twig_Filter;
use Twig_Function;


/**
 * @author Michael Rog <michael@michaelrog.com>
 * @package Wordsmith
 * @since 3.0.0
 */
class WordsmithTwigExtension extends \Twig_Extension
{


    /*
     * Public methods
     * ===========================================================================
     */


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Wordsmith';
    }


    /**
     * Returns an array of Twig filters, to be used in Twig templates via:
     *
     *      {{ 'bar' | fooFilter }}
     *
     * @return array
     */
    public function getFilters()
    {

		$smith = Wordsmith::$plugin->smith;
		$prefix = Wordsmith::$plugin->getSettings()->twigPrefix;

		$filters = [];

		foreach ($smith->getMethodList() as $method => $meta)
		{
			$filters[] = new Twig_Filter($prefix.$method, [$smith, $method], $meta);
		}

		return $filters;

    }


    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set fizz = fooFunction('buzz') %}
     *
     * @return array
     */
    public function getFunctions()
    {

    	$smith = Wordsmith::$plugin->smith;
    	$prefix = Wordsmith::$plugin->getSettings()->twigPrefix;

    	$functions = [];

    	foreach ($smith->getMethodList() as $method => $meta)
		{
			$functions[] = new Twig_Function($prefix.$method, [$smith, $method], $meta);
		}

        return $functions;

    }


	/*
	 * Private methods
	 * ===========================================================================
	 */




}
