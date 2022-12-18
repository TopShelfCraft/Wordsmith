<?php
namespace TopShelfCraft\Wordsmith\gql;

use craft\gql\GqlEntityRegistry;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use TopShelfCraft\Wordsmith\Wordsmith;

class GqlHelper
{

	public static function directiveFromDefinition(array $definition): GqlDirective
	{

		$prefixedName = Wordsmith::getInstance()->getSettings()->gqlPrefix . $definition['name'];

		if ($type = GqlEntityRegistry::getEntity($prefixedName))
		{
			return $type;
		}

		return GqlEntityRegistry::createEntity($prefixedName, new WordsmithDirective(
			$definition + [
				'locations' => [
					DirectiveLocation::FIELD,
				],
			]
		));

	}

	/*
	 * An attempt to generate custom directives dynamically, from PHP Attributes decorating each of the service methods.
	 * I don't think it's gonna work, because:
	 *  - I don't know how to pass a GQL type instance through Attributes
	 *  - Craft uses a static `apply()` method, so I don't know where to stash instance-specific behavior.
	 */
	public function getGqlDirectiveDefinitions()
	{

		$registerGqlDirectives = Wordsmith::getInstance()->getSettings()->registerGqlDirectives;

		if ($registerGqlDirectives === false) {
			return [];
		}

		$publicMethods = (new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC);
		$methodsToRegister = Collection::make($publicMethods)
			->filter(function(ReflectionMethod $method) use ($registerGqlDirectives) {
				return $registerGqlDirectives === true || in_array($method->name, $registerGqlDirectives, true);
			});

		$gqlDirectiveDefinitions = [];

		foreach ($methodsToRegister as $method)
		{

			$descriptionAttributes = new Collection(
				$method->getAttributes('TopShelfCraft\\Wordsmith\\services\\GqlDescription')
			);

			if ($descriptionAttributes->isEmpty())
			{
				continue;
			}

			$gqlDirectiveDefinitions[$method->name] = [
				'name' => $method->name,
				'description' => ($descriptionAttributes[0])->getArguments()[0],
				'args' => array_reduce(
					$method->getAttributes('TopShelfCraft\\Wordsmith\\services\\GqlArgument'),
					function ($args, ReflectionAttribute $attribute) {
						[$name, $type, $description] = $attribute->getArguments();
						$args[$name] = [
							'name' => $name,
							'type' => $type::create(),
							'description' => $description,
						];
						return $args;
					},
					[]
				),
			];

		}

		return $gqlDirectiveDefinitions;

	}

}
