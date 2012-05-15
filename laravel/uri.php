<?php namespace Laravel;

class URI {

	/**
	 * The URI for the current request.
	 *
	 * @var string
	 */
	public static $uri;

	/**
	 * The URI segments for the current request.
	 *
	 * @var array
	 */
	public static $segments = array();

	/**
	 * Get the full URI including the query string.
	 *
	 * @return string
	 */
	public static function full()
	{
		return Request::getUri();
	}

	/**
	 * Get the URI for the current request.
	 *
	 * @return string
	 */
	public static function current()
	{
		if ( ! is_null(static::$uri)) return static::$uri;

		// We'll simply get the path info from the Symfony Request instance and then
		// format to meet our needs in the router. If the URI is root, we'll give
		// back a single slash, otherwise we'll strip all of the slashes off.
		$uri = static::format(Request::getPathInfo());

		static::segments($uri);

		return static::$uri = $uri;
	}

	/**
	 * Format a given URI.
	 *
	 * @param  string  $uri
	 * @return string
	 */
	protected static function format($uri)
	{
		return trim($uri, '/') ?: '/';
	}

	/**
	 * Determine if the current URI matches a given pattern.
	 *
	 * @param  string  $pattern
	 * @return bool
	 */
	public static function is($pattern)
	{
		return Str::is($pattern, static::current());
	}

	/**
	 * Get a specific segment of the request URI via an one-based index.
	 *
	 * <code>
	 *		// Get the first segment of the request URI
	 *		$segment = URI::segment(1);
	 *
	 *		// Get the second segment of the URI, or return a default value
	 *		$segment = URI::segment(2, 'Taylor');
	 * </code>
	 *
	 * @param  int     $index
	 * @param  mixed   $default
	 * @return string
	 */
	public static function segment($index, $default = null)
	{
		static::current();

		return array_get(static::$segments, $index - 1, $default);
	}
	
	/**
	 * Get the value of a specific segment key of the request URI.
	 *
	 * <code>
	 *		// Get the value of the segment key "page"
	 *		$value = URI::segment_value('page');
	 *
	 *		// Get the value of the segment key "page", or return a default value
	 *		$segment = URI::segment('page', '1');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return string
	 */
	public static function segment_value($uri_key, $default = null)
	{
		static::current();
		
		$i = 0;
		
		foreach (static::$segments as $value)
		{
			if ($uri_key == $value)
			{
				// We do +1 because the next segment is the value for the key
				return static::$segments[$i+1]; 
			}
			else
			{
				$i++;
			}
		}
		
		// If we got this far, return our default
		return $default;
	}
	
	/**
	 * Set the URI segments for the request.
	 *
	 * @param  string  $uri
	 * @return void
	 */
	protected static function segments($uri)
	{
		$segments = explode('/', trim($uri, '/'));

		static::$segments = array_diff($segments, array(''));
	}

}