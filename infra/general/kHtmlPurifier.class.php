<?php

require_once BORHAN_ROOT_PATH . '/vendor/htmlpurifier/library/HTMLPurifier.auto.php';

/**
 * @package infra
 * @subpackage utils
 */
class kHtmlPurifier
{
	private static $purifier = null;
	private static $AllowedProperties = null;
	private static $allowedTokenPatterns;

	public static function purify( $className, $propertyName, $value )
	{
		if ( ! is_string($value)								// Skip objects like BorhanNullField, for example
			|| self::isMarkupAllowed($className, $propertyName)	// Skip fields that are allowed to contain HTML/XML tags
		)
		{
			return $value;
		}

		$tokenMapper = new kRegExTokenMapper();
		$tokenizedValue = $tokenMapper->tokenize($value, self::$allowedTokenPatterns);
		$purifiedValue = self::$purifier->purify( $tokenizedValue );
		$modifiedValue = $tokenMapper->unTokenize($purifiedValue);

		if (kCurrentContext::$HTMLPurifierBehaviour == HTMLPurifierBehaviourType::SANITIZE)
			return $modifiedValue;

		if ( $modifiedValue != $value )
		{
			$msg = "Potential Unsafe HTML tags found in $className::$propertyName"
					. "\nORIGINAL VALUE: [" . $value . "]"
					. "\nMODIFIED VALUE: [" . $modifiedValue . "]"
				;

			BorhanLog::err( $msg );

			if (kCurrentContext::$HTMLPurifierBehaviour == HTMLPurifierBehaviourType::NOTIFY)
			{
//			$this->notifyAboutHtmlPurification($className, $propertyName, $value);
				BorhanLog::debug("should send notification");
				return $value;
			}
			// If we reach here kCurrentContext::$HTMLPurifierBehaviour must be BLOCK


			throw new BorhanAPIException(BorhanErrors::UNSAFE_HTML_TAGS, $className, $propertyName);
		} 

		return $value;
	}

	public static function isMarkupAllowed( $className, $propertyName )
	{
		// Is it an excluded property?
		if ( array_key_exists($className . ":" . $propertyName, self::$AllowedProperties) )
		{
			return true;
		}

		return false;
	}
	
	public static function init()
	{
		self::initHTMLPurifier();
		self::initAllowedProperties();
		self::initAllowedTokenPatterns();
	}
	
	public static function initHTMLPurifier()
	{
		$cacheKey = null;
		if ( function_exists('apc_fetch') && function_exists('apc_store') )
		{
			$cacheKey = 'kHtmlPurifierPurifier-' . kConf::getCachedVersionId();
			self::$purifier = apc_fetch($cacheKey);
		}
		
		if ( ! self::$purifier )
		{
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Cache.DefinitionImpl', null);
			self::$purifier = new HTMLPurifier($config);
			if ( $cacheKey )
			{
				apc_store( $cacheKey, self::$purifier );
			}
		}
	}
		
	public static function initAllowedProperties()
	{
		$cacheKey = null;
		if ( function_exists('apc_fetch') && function_exists('apc_store') )
		{
			$cacheKey = 'kHtmlPurifierAllowedProperties-' . kConf::getCachedVersionId();
			self::$AllowedProperties = apc_fetch($cacheKey);
		}

		
		if ( ! self::$AllowedProperties )
		{
			self::$AllowedProperties = kConf::get("xss_allowed_object_properties");

			// Convert values to keys (we don't care about the values) in order to test via array_key_exists.
			self::$AllowedProperties = array_flip(self::$AllowedProperties);

			if ( $cacheKey )
			{
				apc_store( $cacheKey, self::$AllowedProperties );
			}
		}
	}

	public static function initAllowedTokenPatterns()
	{
		$cacheKey = null;
		if ( function_exists('apc_fetch') && function_exists('apc_store') )
		{
			$cacheKey = 'kHtmlPurifierAllowedTokenPatterns-' . kConf::getCachedVersionId();
			self::$allowedTokenPatterns = apc_fetch($cacheKey);
		}

		if ( ! self::$allowedTokenPatterns )
		{
			self::$allowedTokenPatterns = kConf::get("xss_allowed_token_patterns");
			self::$allowedTokenPatterns = preg_replace("/\\\\/", "\\", self::$allowedTokenPatterns);

			if ( $cacheKey )
			{
				apc_store( $cacheKey, self::$allowedTokenPatterns );
			}
		}
	}
}

kHtmlPurifier::init();
