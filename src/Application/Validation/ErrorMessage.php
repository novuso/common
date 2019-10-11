<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\System\Type\Enum;

/**
 * Class ErrorMessage
 *
 * @method static ALPHA
 * @method static ALPHA_DASH
 * @method static ALPHA_NUM
 * @method static ALPHA_NUM_DASH
 * @method static BLANK
 * @method static CONTAINS
 * @method static DATE
 * @method static DATE_TIME
 * @method static DIGITS
 * @method static EMAIL
 * @method static EMPTY
 * @method static ENDS_WITH
 * @method static EQUALS
 * @method static EXACT_COUNT
 * @method static EXACT_LENGTH
 * @method static EXACT_NUMBER
 * @method static FALSE
 * @method static FALSY
 * @method static IN_LIST
 * @method static IP_ADDRESS
 * @method static IP_V4_ADDRESS
 * @method static IP_V6_ADDRESS
 * @method static JSON
 * @method static KEY_ISSET
 * @method static KEY_NOT_EMPTY
 * @method static LIST_OF
 * @method static MATCH
 * @method static MAX_COUNT
 * @method static MAX_LENGTH
 * @method static MAX_NUMBER
 * @method static MIN_COUNT
 * @method static MIN_LENGTH
 * @method static MIN_NUMBER
 * @method static NATURAL_NUMBER
 * @method static NOT_BLANK
 * @method static NOT_EMPTY
 * @method static NOT_EQUALS
 * @method static NOT_NULL
 * @method static NOT_SAME
 * @method static NOT_SCALAR
 * @method static NULL
 * @method static NUMERIC
 * @method static RANGE_COUNT
 * @method static RANGE_LENGTH
 * @method static RANGE_NUMBER
 * @method static REQUIRED
 * @method static SAME
 * @method static SCALAR
 * @method static STARTS_WITH
 * @method static TIME
 * @method static TIMEZONE
 * @method static TRUE
 * @method static TRUTHY
 * @method static TYPE
 * @method static URI
 * @method static URN
 * @method static UUID
 * @method static WHOLE_NUMBER
 */
final class ErrorMessage extends Enum
{
    /**
     * Alpha error
     *
     * @var string
     */
    public const ALPHA = '%s may only contain alphabetic characters';

    /**
     * Alpha dash error
     *
     * @var string
     */
    public const ALPHA_DASH = '%s may only contain alphabetic characters, hyphens, or underscores';

    /**
     * Alpha num error
     *
     * @var string
     */
    public const ALPHA_NUM = '%s may only contain alphanumeric characters';

    /**
     * Alpha num dash error
     *
     * @var string
     */
    public const ALPHA_NUM_DASH = '%s may only contain alphanumeric characters, hyphens, or underscores';

    /**
     * Blank error
     *
     * @var string
     */
    public const BLANK = '%s must be blank';

    /**
     * Contains error
     *
     * @var string
     */
    public const CONTAINS = '%s must contain "%s"';

    /**
     * Date error
     *
     * @var string
     */
    public const DATE = '%s must be a valid date in format "%s"';

    /**
     * Date/Time error
     *
     * @var string
     */
    public const DATE_TIME = '%s must be a valid date/time in format "%s"';

    /**
     * Digits error
     *
     * @var string
     */
    public const DIGITS = '%s may only contain digits';

    /**
     * Email error
     *
     * @var string
     */
    public const EMAIL = '%s must be a valid email address';

    /**
     * Empty error
     *
     * @var string
     */
    public const EMPTY = '%s must be empty';

    /**
     * Ends with error
     *
     * @var string
     */
    public const ENDS_WITH = '%s must end with "%s"';

    /**
     * Equals error
     *
     * @var string
     */
    public const EQUALS = '%s must equal the value in the %s field';

    /**
     * Exact count error
     *
     * @var string
     */
    public const EXACT_COUNT = '%s must contain exactly %d items';

    /**
     * Exact length error
     *
     * @var string
     */
    public const EXACT_LENGTH = '%s must contain exactly %d characters';

    /**
     * Exact number error
     *
     * @var string
     */
    public const EXACT_NUMBER = '%s must be equal to %s';

    /**
     * False error
     *
     * @var string
     */
    public const FALSE = '%s must be false';

    /**
     * Falsy error
     *
     * @var string
     */
    public const FALSY = '%s must evaluate to false';

    /**
     * In list error
     *
     * @var string
     */
    public const IN_LIST = '%s must be one of {{list}}';

    /**
     * IP address error
     *
     * @var string
     */
    public const IP_ADDRESS = '%s must be a valid IP address';

    /**
     * IP V4 address error
     *
     * @var string
     */
    public const IP_V4_ADDRESS = '%s must be a valid IP V4 address';

    /**
     * IP V6 address error
     *
     * @var string
     */
    public const IP_V6_ADDRESS = '%s must be a valid IP V6 address';

    /**
     * JSON error
     *
     * @var string
     */
    public const JSON = '%s must be a valid JSON-formatted string';

    /**
     * Key isset error
     *
     * @var string
     */
    public const KEY_ISSET = '%s must have the %s key set';

    /**
     * Key not empty error
     *
     * @var string
     */
    public const KEY_NOT_EMPTY = '%s must have the %s key set to a non-empty value';

    /**
     * List of error
     *
     * @var string
     */
    public const LIST_OF = '%s must be a list of type: %s';

    /**
     * Match error
     *
     * @var string
     */
    public const MATCH = '%s must match the regular expression "%s"';

    /**
     * Max count error
     *
     * @var string
     */
    public const MAX_COUNT = '%s must contain no more than %d items';

    /**
     * Max length error
     *
     * @var string
     */
    public const MAX_LENGTH = '%s must contain no more than %d characters';

    /**
     * Max number error
     *
     * @var string
     */
    public const MAX_NUMBER = '%s must be less than or equal to %s';

    /**
     * Min count error
     *
     * @var string
     */
    public const MIN_COUNT = '%s must contain no less than %d items';

    /**
     * Min length error
     *
     * @var string
     */
    public const MIN_LENGTH = '%s must contain no less than %d characters';

    /**
     * Min number error
     *
     * @var string
     */
    public const MIN_NUMBER = '%s must be greater than or equal to %s';

    /**
     * Natural number error
     *
     * @var string
     */
    public const NATURAL_NUMBER = '%s must be a whole number greater than zero';

    /**
     * Not blank error
     *
     * @var string
     */
    public const NOT_BLANK = '%s cannot be blank';

    /**
     * Not empty error
     *
     * @var string
     */
    public const NOT_EMPTY = '%s cannot be empty';

    /**
     * Not equals error
     *
     * @var string
     */
    public const NOT_EQUALS = '%s must not equal the value in the %s field';

    /**
     * Not null error
     *
     * @var string
     */
    public const NOT_NULL = '%s cannot be null';

    /**
     * Not same error
     *
     * @var string
     */
    public const NOT_SAME = '%s must not be the same as the value in the %s field';

    /**
     * Not scalar error
     *
     * @var string
     */
    public const NOT_SCALAR = '%s cannot be scalar';

    /**
     * Null error
     *
     * @var string
     */
    public const NULL = '%s must be null';

    /**
     * Numeric error
     *
     * @var string
     */
    public const NUMERIC = '%s must be numeric';

    /**
     * Range count error
     *
     * @var string
     */
    public const RANGE_COUNT = '%s must contain between %d and %d items';

    /**
     * Range length error
     *
     * @var string
     */
    public const RANGE_LENGTH = '%s must contain between %d and %d characters';

    /**
     * Range number error
     *
     * @var string
     */
    public const RANGE_NUMBER = '%s must be between %s and %s';

    /**
     * Required error
     *
     * @var string
     */
    public const REQUIRED = '%s is required';

    /**
     * Same error
     *
     * @var string
     */
    public const SAME = '%s must be the same as the value in the %s field';

    /**
     * Scalar error
     *
     * @var string
     */
    public const SCALAR = '%s must be scalar';

    /**
     * Starts with error
     *
     * @var string
     */
    public const STARTS_WITH = '%s must start with "%s"';

    /**
     * Time error
     *
     * @var string
     */
    public const TIME = '%s must be a valid time in format "%s"';

    /**
     * Timezone error
     *
     * @var string
     */
    public const TIMEZONE = '%s must be a valid timezone';

    /**
     * True error
     *
     * @var string
     */
    public const TRUE = '%s must be true';

    /**
     * Truthy error
     *
     * @var string
     */
    public const TRUTHY = '%s must evaluate to true';

    /**
     * Type error
     *
     * @var string
     */
    public const TYPE = '%s must be a value of type: %s';

    /**
     * URI error
     *
     * @var string
     */
    public const URI = '%s must be a valid URI';

    /**
     * URN error
     *
     * @var string
     */
    public const URN = '%s must be a valid URN';

    /**
     * UUID error
     *
     * @var string
     */
    public const UUID = '%s must be a valid UUID';

    /**
     * Whole number error
     *
     * @var string
     */
    public const WHOLE_NUMBER = '%s must be a whole number';
}
