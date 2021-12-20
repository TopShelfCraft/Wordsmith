<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace topshelfcraft\wordsmith\gql\directives;

use topshelfcraft\wordsmith\Wordsmith;

use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use GraphQL\Error\Error;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Class Wordsmith
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.3.0
 */
class WordsmithTransform extends Directive
{


    /**
     * @inheritdoc
     */
    public static function create(): GqlDirective
    {
        if ($type = GqlEntityRegistry::getEntity(self::name())) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity(static::name(), new self([
            'name' => static::name(),
            'locations' => [
                DirectiveLocation::FIELD,
            ],
            'description' => 'Run Wordsmith transforms on the value of the field.',
            'args' => [
                'function' => [
                    'name' => 'function',
                    'type' => Type::string(),
                    'description' => 'The Wordsmith function to use.'
                ],
                // 'args' => [
                //     'name' => 'args',
                //     'type' => Type::listOf,
                //     'description' => 'The Wordsmith function to use.'
                // ],
            ],
        ]));

        return $type;
    }

    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'wordsmith';
    }

    /**
     * @inheritdoc
     */
    public static function apply($source, $value, array $arguments, ResolveInfo $resolveInfo)
    {
        if(isset($arguments['function']))
        {
            switch ($arguments['function']) {
                case 'amp':
                    return Wordsmith::getInstance()->smith->amp((string)$value);
                    break;
                case 'apTitleize':
                    return Wordsmith::getInstance()->smith->apTitleize((string)$value);
                    break;
                case 'automatedReadabilityIndex':
                    return Wordsmith::getInstance()->smith->automatedReadabilityIndex((string)$value);
                    break;
                case 'averageWordsPerSentence':
                    return Wordsmith::getInstance()->smith->averageWordsPerSentence((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'between':
                //     return Wordsmith::getInstance()->smith->between((string)$value);
                //     break;
                case 'camelize':
                    return Wordsmith::getInstance()->smith->camelize((string)$value);
                    break;
                case 'caps':
                    return Wordsmith::getInstance()->smith->caps((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'chop':
                //     return Wordsmith::getInstance()->smith->chop((string)$value);
                //     break;
                case 'colemanLiauIndex':
                    return Wordsmith::getInstance()->smith->colemanLiauIndex((string)$value);
                    break;
                case 'daleChallReadabilityScore':
                    return Wordsmith::getInstance()->smith->daleChallReadabilityScore((string)$value);
                    break;
                case 'dasherize':
                    return Wordsmith::getInstance()->smith->dasherize((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'emojify':
                //     return Wordsmith::getInstance()->smith->emojify((string)$value);
                //     break;
                case 'entitle':
                    return Wordsmith::getInstance()->smith->entitle((string)$value);
                    break;
                case 'firstName':
                    return Wordsmith::getInstance()->smith->firstName((string)$value);
                    break;
                case 'firstWord':
                    return Wordsmith::getInstance()->smith->firstWord((string)$value);
                    break;
                case 'fleschKincaidReadingEase':
                    return Wordsmith::getInstance()->smith->fleschKincaidReadingEase((string)$value);
                    break;
                case 'fleschKincaidGradeLevel':
                    return Wordsmith::getInstance()->smith->fleschKincaidGradeLevel((string)$value);
                    break;
                case 'givenName':
                    return Wordsmith::getInstance()->smith->givenName((string)$value);
                    break;
                case 'gunningFogScore':
                    return Wordsmith::getInstance()->smith->gunningFogScore((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'hacksaw':
                //     return Wordsmith::getInstance()->smith->hacksaw((string)$value);
                //     break;
                case 'humanize':
                    return Wordsmith::getInstance()->smith->humanize((string)$value);
                    break;
                case 'hyphenate':
                    return Wordsmith::getInstance()->smith->hyphenate((string)$value);
                    break;
                case 'isStringy':
                    return Wordsmith::getInstance()->smith->isStringy((string)$value);
                    break;
                case 'lastName':
                    return Wordsmith::getInstance()->smith->lastName((string)$value);
                    break;
                case 'lastWord':
                    return Wordsmith::getInstance()->smith->lastWord((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'lowerCaseRoman':
                //     return Wordsmith::getInstance()->smith->lowerCaseRoman((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'markdown':
                //     return Wordsmith::getInstance()->smith->markdown((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'md':
                //     return Wordsmith::getInstance()->smith->md((string)$value);
                //     break;
                case 'ordinal':
                    return Wordsmith::getInstance()->smith->ordinal((string)$value);
                    break;
                case 'ordinalize':
                    return Wordsmith::getInstance()->smith->ordinalize((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'parsedown':
                //     return Wordsmith::getInstance()->smith->parsedown((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'parsedownExtra':
                //     return Wordsmith::getInstance()->smith->parsedownExtra((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with return type other than string.
                // case 'parseName':
                //     return Wordsmith::getInstance()->smith->parseName((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with return type other than string.
                // case 'parseUrl':
                //     return Wordsmith::getInstance()->smith->parseUrl((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'pde':
                //     return Wordsmith::getInstance()->smith->pde((string)$value);
                //     break;
                case 'pascalize':
                    return Wordsmith::getInstance()->smith->pascalize((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                case 'pluralize':
                    return Wordsmith::getInstance()->smith->pluralize((string)$value);
                    break;
                case 'readTime':
                    return Wordsmith::getInstance()->smith->readTime((string)$value);
                    break;
                case 'sentenceCount':
                    return Wordsmith::getInstance()->smith->sentenceCount((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                case 'singularize':
                    return Wordsmith::getInstance()->smith->singularize((string)$value);
                    break;
                case 'slugify':
                    return Wordsmith::getInstance()->smith->slugify((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                case 'smartypants':
                    return Wordsmith::getInstance()->smith->smartypants((string)$value);
                    break;
                case 'smogIndex':
                    return Wordsmith::getInstance()->smith->smogIndex((string)$value);
                    break;
                case 'spacheReadabilityScore':
                    return Wordsmith::getInstance()->smith->spacheReadabilityScore((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringAfterFirst':
                //     return Wordsmith::getInstance()->smith->substringAfterFirst((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringAfterLast':
                //     return Wordsmith::getInstance()->smith->substringAfterLast((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringBeforeFirst':
                //     return Wordsmith::getInstance()->smith->substringBeforeFirst((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringBeforeLast':
                //     return Wordsmith::getInstance()->smith->substringBeforeLast((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringBetween':
                //     return Wordsmith::getInstance()->smith->substringBetween((string)$value);
                //     break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                // case 'substringCount':
                //     return Wordsmith::getInstance()->smith->substringCount((string)$value);
                //     break;
                case 'surname':
                    return Wordsmith::getInstance()->smith->surname((string)$value);
                    break;
                case 'titleize':
                    return Wordsmith::getInstance()->smith->titleize((string)$value);
                    break;
                case 'typogrify':
                    return Wordsmith::getInstance()->smith->typogrify((string)$value);
                    break;
                case 'typogrifyFeed':
                    return Wordsmith::getInstance()->smith->typogrifyFeed((string)$value);
                    break;
                case 'underscore':
                    return Wordsmith::getInstance()->smith->underscore((string)$value);
                    break;
                case 'underscored':
                    return Wordsmith::getInstance()->smith->underscored((string)$value);
                    break;
                case 'upperCamelize':
                    return Wordsmith::getInstance()->smith->upperCamelize((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.
                case 'upperCaseRoman':
                    return Wordsmith::getInstance()->smith->upperCaseRoman((string)$value);
                    break;
                case 'urlFragment':
                    return Wordsmith::getInstance()->smith->urlFragment((string)$value);
                    break;
                case 'urlHost':
                    return Wordsmith::getInstance()->smith->urlHost((string)$value);
                    break;
                case 'urlPass':
                    return Wordsmith::getInstance()->smith->urlPass((string)$value);
                    break;
                case 'urlPath':
                    return Wordsmith::getInstance()->smith->urlPath((string)$value);
                    break;
                case 'urlPort':
                    return Wordsmith::getInstance()->smith->urlPort((string)$value);
                    break;
                case 'urlQuery':
                    return Wordsmith::getInstance()->smith->urlQuery((string)$value);
                    break;
                case 'urlScheme':
                    return Wordsmith::getInstance()->smith->urlScheme((string)$value);
                    break;
                case 'urlUser':
                    return Wordsmith::getInstance()->smith->urlUser((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.    
                case 'trim':
                    return Wordsmith::getInstance()->smith->trim((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.    
                case 'trimLeft':
                    return Wordsmith::getInstance()->smith->trimLeft((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.    
                case 'trimRight':
                    return Wordsmith::getInstance()->smith->trimRight((string)$value);
                    break;
                case 'wc':
                    return Wordsmith::getInstance()->smith->wc((string)$value);
                    break;
                // @TODO: Implement the rest of the Wordsmith functions with arguments.    
                case 'widont':
                    return Wordsmith::getInstance()->smith->widont((string)$value);
                    break;
                case 'wrapAmps':
                    return Wordsmith::getInstance()->smith->wrapAmps((string)$value);
                    break;
                case 'wrapCaps':
                    return Wordsmith::getInstance()->smith->wrapCaps((string)$value);
                    break;
                case 'wordcount':
                    return Wordsmith::getInstance()->smith->wordcount((string)$value);
                    break;
                case 'youtubeId':
                    return Wordsmith::getInstance()->smith->youtubeId((string)$value);
                    break;
                default:
                    throw new Error('Function "' . $arguments['function'] . '" is not supported.');
                    break;
            }
        }
        else 
        {
            return Wordsmith::getInstance()->smith->typogrify((string)$value);;
        }
    }
}