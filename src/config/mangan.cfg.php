<?php

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefDecorator;
use Maslosoft\Mangan\Decorators\Model\AliasDecorator;
use Maslosoft\Mangan\Decorators\Model\ClassNameDecorator;
use Maslosoft\Mangan\Decorators\Model\OwnerDecorator;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Decorators\Property\SecretDecorator;
use Maslosoft\Mangan\Decorators\RelatedArrayDecorator;
use Maslosoft\Mangan\Decorators\RelatedDecorator;
use Maslosoft\Mangan\Events\Handlers\ParentIdHandler;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\DateWriteUnixSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Mangan\Traits\Model\WithParentTrait;
use Maslosoft\Mangan\Transformers\CriteriaArray;
use Maslosoft\Mangan\Transformers\DocumentArray;
use Maslosoft\Mangan\Transformers\Filters\DocumentArrayFilter;
use Maslosoft\Mangan\Transformers\Filters\JsonFilter;
use Maslosoft\Mangan\Transformers\Filters\PersistentFilter;
use Maslosoft\Mangan\Transformers\Filters\SafeFilter;
use Maslosoft\Mangan\Transformers\Filters\SecretFilter;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\Mangan\Transformers\SafeArray;
use Maslosoft\Mangan\Transformers\YamlArray;
use Maslosoft\Mangan\Validators\BuiltIn\CompareValidator;
use Maslosoft\Mangan\Validators\BuiltIn\CountValidator;
use Maslosoft\Mangan\Validators\BuiltIn\EmailValidator;
use Maslosoft\Mangan\Validators\BuiltIn\ImmutableValidator;
use Maslosoft\Mangan\Validators\BuiltIn\NumberValidator;
use Maslosoft\Mangan\Validators\BuiltIn\RangeValidator;
use Maslosoft\Mangan\Validators\BuiltIn\RegexValidator;
use Maslosoft\Mangan\Validators\BuiltIn\RequiredValidator;
use Maslosoft\Mangan\Validators\BuiltIn\StringValidator;
use Maslosoft\Mangan\Validators\BuiltIn\UniqueValidator;
use Maslosoft\Mangan\Validators\BuiltIn\UrlValidator;
use Maslosoft\Mangan\Validators\Proxy\BooleanProxy;
use Maslosoft\Mangan\Validators\Proxy\BooleanValidator;
use Maslosoft\Mangan\Validators\Proxy\CompareProxy;
use Maslosoft\Mangan\Validators\Proxy\CountProxy;
use Maslosoft\Mangan\Validators\Proxy\EmailProxy;
use Maslosoft\Mangan\Validators\Proxy\ImmutableProxy;
use Maslosoft\Mangan\Validators\Proxy\NumberProxy;
use Maslosoft\Mangan\Validators\Proxy\RangeProxy;
use Maslosoft\Mangan\Validators\Proxy\RegexProxy;
use Maslosoft\Mangan\Validators\Proxy\RequiredProxy;
use Maslosoft\Mangan\Validators\Proxy\StringProxy;
use Maslosoft\Mangan\Validators\Proxy\UniqueProxy;
use Maslosoft\Mangan\Validators\Proxy\UrlProxy;

return [
	/**
	 * Correct syntax is:
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
	 * @example mongodb://localhost:27017
	 * @var string host:port
	 * @since v1.0
	 */
	'connectionString' => 'mongodb://localhost:27017',
	/**
	 * Configuration of decorators for transformers
	 * Array key is decorator class name or interface, values are decorator class names.
	 * @var string[][]
	 */
	'decorators' => [
		TransformatorInterface::class => [
			EmbeddedArrayDecorator::class,
			EmbeddedDecorator::class,
			AliasDecorator::class,
			OwnerDecorator::class,
		],
		CriteriaArray::class => [
			I18NDecorator::class,
		],
		DocumentArray::class => [
			ClassNameDecorator::class,
			EmbedRefDecorator::class,
			EmbedRefArrayDecorator::class,
		],
		SafeArray::class => [
			ClassNameDecorator::class,
			EmbedRefDecorator::class,
			EmbedRefArrayDecorator::class,
		],
		JsonArray::class => [
			ClassNameDecorator::class,
			EmbedRefDecorator::class,
			EmbedRefArrayDecorator::class,
		],
		YamlArray::class => [
			ClassNameDecorator::class,
			EmbedRefDecorator::class,
			EmbedRefArrayDecorator::class,
		],
		RawArray::class => [
			DbRefArrayDecorator::class,
			DbRefDecorator::class,
			RelatedDecorator::class,
			RelatedArrayDecorator::class,
			SecretDecorator::class,
			I18NDecorator::class,
			ClassNameDecorator::class,
		]
	],
	/**
	 * Configuration for finalizers.
	 * @see https://github.com/Maslosoft/Mangan/issues/36
	 * @var string[][]
	 */
	'finalizers' => [
	],
	/**
	 * Configuration of property filters for transformers
	 * Array key is decorator class name or interface, values are filter class names.
	 * @var string[][]
	 */
	'filters' => [
		TransformatorInterface::class => [
		],
		DocumentArray::class => [
			DocumentArrayFilter::class,
		],
		JsonArray::class => [
			JsonFilter::class,
		],
		RawArray::class => [
			PersistentFilter::class,
			SecretFilter::class
		],
		SafeArray::class => [
			SafeFilter::class
		],
	],
	'eventHandlers' => [
		WithParentTrait::class => ParentIdHandler::class
	],
	/**
	 * Mapping for validators. Key is validator proxy class name, value is concrete validator implementation
	 * @var string[]
	 */
	'validators' => [
		BooleanProxy::class => BooleanValidator::class,
		CompareProxy::class => CompareValidator::class,
		CountProxy::class => CountValidator::class,
		ImmutableProxy::class => ImmutableValidator::class,
		UniqueProxy::class => UniqueValidator::class,
		EmailProxy::class => EmailValidator::class,
		NumberProxy::class => NumberValidator::class,
		RangeProxy::class => RangeValidator::class,
		RegexProxy::class => RegexValidator::class,
		RequiredProxy::class => RequiredValidator::class,
		StringProxy::class => StringValidator::class,
		UniqueProxy::class => UniqueValidator::class,
		UrlProxy::class => UrlValidator::class
	],
	/**
	 * Sanitizers ramapping for common scenarios.
	 * @var string[][]
	 */
	'sanitizersMap' => [
		JsonArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteUnixSanitizer::class
		],
		YamlArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteUnixSanitizer::class
		],
	],
	/**
	 * @var string $dbName name of the Mongo database to use
	 * @since v1.0
	 */
	'dbName' => null,
	/**
	 * If set to TRUE all internal DB operations will use SAFE flag with data modification requests.
	 *
	 * When SAFE flag is set to TRUE driver will wait for the response from DB, and throw an exception
	 * if something went wrong, is set to false, driver will only send operation to DB but will not wait
	 * for response from DB.
	 *
	 * MongoDB default value for this flag is: FALSE.
	 *
	 * @var boolean $safeFlag state of SAFE flag (global scope)
	 */
	'safeFlag' => false,
	/**
	 * If set to TRUE findAll* methods of models, will return {@see Cursor} instead of
	 * raw array of models.
	 *
	 * Generally you should want to have this set to TRUE as cursor use lazy-loading/instantiating of
	 * models, this is set to FALSE, by default to keep backwards compatibility.
	 *
	 * Note: {@see Cursor} does not implement ArrayAccess interface and cannot be used like an array,
	 * because offset access to cursor is highly ineffective and pointless.
	 *
	 * @var boolean $useCursor state of Use Cursor flag (global scope)
	 */
	'useCursor' => false,
	/**
	 * Queries profiling.
	 * Defaults to false. This should be mainly enabled and used during development
	 * to find out the bottleneck of mongo queries.
	 * @var boolean whether to enable profiling the mongo queries being executed.
	 */
	'enableProfiling' => false,
];
