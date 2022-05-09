<?php

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Collation set-up, leave any field `null` to use DB defaults
 */
class Collation implements AnnotatedInterface
{
	/**
	 * The ICU locale. See Supported Languages and Locales for a list of supported locales.
	 * To specify simple binary comparison, specify locale `value` of `"simple"`.
	 * @see https://www.mongodb.com/docs/manual/reference/collation-locales-defaults/#std-label-collation-languages-locales
	 * @var string|null
	 */
	public ?string $locale = null;

	/**
	 *
	 * Optional. Flag that determines whether to include case comparison at strength level 1 or 2.
 	 *
	 * If true, include case comparison:
	 *
	 * * When used with `strength:1`, collation compares base characters and case.
	 * * When used with `strength:2`, collation compares base characters, diacritics (and possible other secondary differences) and case.
	 * If false, do not include case comparison at level `1` or `2`. The default is `false`.
	 *
	 * For more information, see
	 * ICU Collation: Case Level
	 * @see http://userguide.icu-project.org/collation/concepts#TOC-CaseLevel
	 * @var bool|null
	 */
	public ?bool $caseLevel = null;

	/**
	 * Optional. A field that determines sort order of case differences during tertiary level comparisons.
	 * @var string|null
	 */
	public ?string $caseFirst = null;

	/**
	 *
	 * Optional. The level of comparison to perform. Corresponds to
	 * ICU Comparison Levels
	 * @see http://userguide.icu-project.org/collation/concepts#TOC-Comparison-Levels
	 * @var int|null
	 */
	public ?int $strength = null;

	/**
	 * Optional. Flag that determines whether to compare numeric strings as numbers or as strings.
	 * @var bool|null
	 */
	public ?bool $numericOrdering = null;

	/**
	 * Optional. Field that determines whether collation should consider whitespace and punctuation as base characters for purposes of comparison.
	 * @var string|null
	 */
	public ?string $alternate = null;

	/**
	 * Optional. Field that determines up to which characters are considered ignorable when alternate: "shifted". Has no effect if alternate: "non-ignorable"
	 * @var string|null
	 */
	public ?string $maxVariable = null;

	/**
	 *
	 * Optional. Flag that determines whether strings with diacritics sort from back of the string, such as with some French dictionary ordering.
	 *
	 * If `true`, compare from back to front.
	 *
	 * If `false`, compare from front to back.
	 *
	 * The default value is `false`.
	 * @var bool|null
	 */
	public ?bool $backwards = null;

	/**
	 * Optional. Flag that determines whether to check if text require normalization and to perform normalization. Generally, majority of text does not require this normalization processing.
	 *
	 * If true, check if fully normalized and perform normalization to compare text.
	 *
	 * If false, does not check.
	 *
	 * The default value is false.
	 *
	 * @var ?bool
	 */
	public ?bool $normalization = null;
}