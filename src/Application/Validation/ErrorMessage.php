<?php

declare(strict_types=1);

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
    public const ALPHA = '%s may only contain alphabetic characters';
    public const ALPHA_DASH = '%s may only contain alphabetic characters, hyphens, or underscores';
    public const ALPHA_NUM = '%s may only contain alphanumeric characters';
    public const ALPHA_NUM_DASH = '%s may only contain alphanumeric characters, hyphens, or underscores';
    public const BLANK = '%s must be blank';
    public const CONTAINS = '%s must contain "%s"';
    public const DATE = '%s must be a valid date in format "%s"';
    public const DATE_TIME = '%s must be a valid date/time in format "%s"';
    public const DIGITS = '%s may only contain digits';
    public const EMAIL = '%s must be a valid email address';
    public const EMPTY = '%s must be empty';
    public const ENDS_WITH = '%s must end with "%s"';
    public const EQUALS = '%s must equal the value in the %s field';
    public const EXACT_COUNT = '%s must contain exactly %d items';
    public const EXACT_LENGTH = '%s must contain exactly %d characters';
    public const EXACT_NUMBER = '%s must be equal to %s';
    public const FALSE = '%s must be false';
    public const FALSY = '%s must evaluate to false';
    public const IN_LIST = '%s must be one of {{list}}';
    public const IP_ADDRESS = '%s must be a valid IP address';
    public const IP_V4_ADDRESS = '%s must be a valid IP V4 address';
    public const IP_V6_ADDRESS = '%s must be a valid IP V6 address';
    public const JSON = '%s must be a valid JSON-formatted string';
    public const KEY_ISSET = '%s must have the %s key set';
    public const KEY_NOT_EMPTY = '%s must have the %s key set to a non-empty value';
    public const LIST_OF = '%s must be a list of type: %s';
    public const MATCH = '%s must match the regular expression "%s"';
    public const MAX_COUNT = '%s must contain no more than %d items';
    public const MAX_LENGTH = '%s must contain no more than %d characters';
    public const MAX_NUMBER = '%s must be less than or equal to %s';
    public const MIN_COUNT = '%s must contain no less than %d items';
    public const MIN_LENGTH = '%s must contain no less than %d characters';
    public const MIN_NUMBER = '%s must be greater than or equal to %s';
    public const NATURAL_NUMBER = '%s must be a whole number greater than zero';
    public const NOT_BLANK = '%s cannot be blank';
    public const NOT_EMPTY = '%s cannot be empty';
    public const NOT_EQUALS = '%s must not equal the value in the %s field';
    public const NOT_NULL = '%s cannot be null';
    public const NOT_SAME = '%s must not be the same as the value in the %s field';
    public const NOT_SCALAR = '%s cannot be scalar';
    public const NULL = '%s must be null';
    public const NUMERIC = '%s must be numeric';
    public const RANGE_COUNT = '%s must contain between %d and %d items';
    public const RANGE_LENGTH = '%s must contain between %d and %d characters';
    public const RANGE_NUMBER = '%s must be between %s and %s';
    public const REQUIRED = '%s is required';
    public const SAME = '%s must be the same as the value in the %s field';
    public const SCALAR = '%s must be scalar';
    public const STARTS_WITH = '%s must start with "%s"';
    public const TIME = '%s must be a valid time in format "%s"';
    public const TIMEZONE = '%s must be a valid timezone';
    public const TRUE = '%s must be true';
    public const TRUTHY = '%s must evaluate to true';
    public const TYPE = '%s must be a value of type: %s';
    public const URI = '%s must be a valid URI';
    public const URN = '%s must be a valid URN';
    public const UUID = '%s must be a valid UUID';
    public const WHOLE_NUMBER = '%s must be a whole number';
}
