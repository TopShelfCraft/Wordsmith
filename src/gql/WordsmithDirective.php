<?php
namespace TopShelfCraft\Wordsmith\gql;

use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use GraphQL\Error\Error;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use TopShelfCraft\Wordsmith\Wordsmith;

class WordsmithDirective extends Directive
{

	public static function create(): GqlDirective
	{

		if ($type = GqlEntityRegistry::getEntity(static::name()))
		{
			return $type;
		}

		return GqlEntityRegistry::createEntity(static::name(), new WordsmithDirective([
			'name' => static::name(),
			'description' => "Applies a Wordsmith function to the field value",
			'locations' => [
				DirectiveLocation::FIELD,
			],
			'args' => [
				'function' => [
					'name' => 'function',
					'description' => 'Which function to apply',
					'type' => Type::string(),
				],
			],
		]));

	}

	/**
	 * @inheritdoc
	 */
	public static function name(): string {
		return 'wordsmith';
	}

	/**
	 * @inheritdoc
	 */
	public static function apply($source, $value, array $arguments, ResolveInfo $resolveInfo): mixed
	{

		$function = $arguments['function'];

		static::_ensureFunctionIsAllowed($function);

		$smith = Wordsmith::getInstance()->smith;

		if (!method_exists($smith, $function))
		{
			throw new Error("Wordsmith does not support a `{$function}` function.");
		}

		return call_user_func([$smith, $function], (string)$value);

	}

	private static function _ensureFunctionIsAllowed(string $function)
	{

		$gqlAllowFunctions = Wordsmith::getInstance()->getSettings()->gqlAllowFunctions;

		if ($gqlAllowFunctions === true)
		{
			return;
		}

		if (is_array($gqlAllowFunctions) && in_array($function, $gqlAllowFunctions, true))
		{
			return;
		}

		throw new Error("The `{$function}` function is not enabled.");

	}

}
