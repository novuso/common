<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Data\ErrorData;
use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\Common\Application\Validation\Rule\CountExact;
use Novuso\Common\Application\Validation\Rule\CountMax;
use Novuso\Common\Application\Validation\Rule\CountMin;
use Novuso\Common\Application\Validation\Rule\CountRange;
use Novuso\Common\Application\Validation\Rule\InList;
use Novuso\Common\Application\Validation\Rule\IsAlnum;
use Novuso\Common\Application\Validation\Rule\IsAlnumDashed;
use Novuso\Common\Application\Validation\Rule\IsAlpha;
use Novuso\Common\Application\Validation\Rule\IsAlphaDashed;
use Novuso\Common\Application\Validation\Rule\IsBlank;
use Novuso\Common\Application\Validation\Rule\IsDateTime;
use Novuso\Common\Application\Validation\Rule\IsDigits;
use Novuso\Common\Application\Validation\Rule\IsEmail;
use Novuso\Common\Application\Validation\Rule\IsEmpty;
use Novuso\Common\Application\Validation\Rule\IsFalse;
use Novuso\Common\Application\Validation\Rule\IsFalsy;
use Novuso\Common\Application\Validation\Rule\IsIpAddress;
use Novuso\Common\Application\Validation\Rule\IsIpV4Address;
use Novuso\Common\Application\Validation\Rule\IsIpV6Address;
use Novuso\Common\Application\Validation\Rule\IsJson;
use Novuso\Common\Application\Validation\Rule\IsListOf;
use Novuso\Common\Application\Validation\Rule\IsMatch;
use Novuso\Common\Application\Validation\Rule\IsNaturalNumber;
use Novuso\Common\Application\Validation\Rule\IsNotBlank;
use Novuso\Common\Application\Validation\Rule\IsNull;
use Novuso\Common\Application\Validation\Rule\IsNumeric;
use Novuso\Common\Application\Validation\Rule\IsScalar;
use Novuso\Common\Application\Validation\Rule\IsTimezone;
use Novuso\Common\Application\Validation\Rule\IsTrue;
use Novuso\Common\Application\Validation\Rule\IsTruthy;
use Novuso\Common\Application\Validation\Rule\IsType;
use Novuso\Common\Application\Validation\Rule\IsUri;
use Novuso\Common\Application\Validation\Rule\IsUrn;
use Novuso\Common\Application\Validation\Rule\IsUuid;
use Novuso\Common\Application\Validation\Rule\IsWholeNumber;
use Novuso\Common\Application\Validation\Rule\KeyIsset;
use Novuso\Common\Application\Validation\Rule\KeyNotEmpty;
use Novuso\Common\Application\Validation\Rule\LengthExact;
use Novuso\Common\Application\Validation\Rule\LengthMax;
use Novuso\Common\Application\Validation\Rule\LengthMin;
use Novuso\Common\Application\Validation\Rule\LengthRange;
use Novuso\Common\Application\Validation\Rule\NumberExact;
use Novuso\Common\Application\Validation\Rule\NumberMax;
use Novuso\Common\Application\Validation\Rule\NumberMin;
use Novuso\Common\Application\Validation\Rule\NumberRange;
use Novuso\Common\Application\Validation\Rule\StringContains;
use Novuso\Common\Application\Validation\Rule\StringEndsWith;
use Novuso\Common\Application\Validation\Rule\StringStartsWith;
use Novuso\Common\Application\Validation\Specification\EqualFieldsSpecification;
use Novuso\Common\Application\Validation\Specification\RequiredFieldSpecification;
use Novuso\Common\Application\Validation\Specification\SameFieldsSpecification;
use Novuso\Common\Application\Validation\Specification\SingleFieldSpecification;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Collection\Type\Sequence;

/**
 * Class ValidationCoordinator
 */
final class ValidationCoordinator
{
    /**
     * List of validators
     *
     * @var Sequence
     */
    protected $validators;

    /**
     * Constructs ValidationCoordinator
     */
    public function __construct()
    {
        $this->resetValidators();
    }

    /**
     * Validates input data
     *
     * @param InputData $input The input data
     *
     * @return ValidationResult
     */
    public function validate(InputData $input): ValidationResult
    {
        $context = $this->createContext($input);

        $valid = $this->validators->every(function (Validator $validator) use ($context) {
            return $validator->validate($context);
        });

        if ($context->hasErrors() || !$valid) {
            $result = ValidationResult::failed(new ErrorData($context->getErrors()));
        } else {
            $result = ValidationResult::passed(new ApplicationData($input->toArray()));
        }

        $this->resetValidators();

        return $result;
    }

    /**
     * Adds a validator
     *
     * @param Validator $validator The validator
     *
     * @return void
     */
    public function addValidator(Validator $validator): void
    {
        $this->validators->add($validator);
    }

    /**
     * Adds a validation that asserts a string is alphabetic
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addAlphaValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsAlpha()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is alphabetic dashed
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addAlphaDashValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsAlphaDashed()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is alphanumeric
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addAlphaNumValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsAlnum()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is alphanumeric dashed
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addAlphaNumDashValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsAlnumDashed()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is blank
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addBlankValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsBlank()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string contains a search string
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $search       The search string
     *
     * @return void
     */
    public function addContainsValidation(string $fieldName, string $errorMessage, string $search): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new StringContains($search)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a valid date
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $format       The date format
     *
     * @return void
     */
    public function addDateValidation(string $fieldName, string $errorMessage, string $format): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsDateTime($format)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a valid date/time
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $format       The date/time format
     *
     * @return void
     */
    public function addDateTimeValidation(string $fieldName, string $errorMessage, string $format): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsDateTime($format)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field only contains digits
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addDigitsValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsDigits()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is an email address
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addEmailValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsEmail()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is empty
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addEmptyValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsEmpty()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string ends with a search string
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $search       The search string
     *
     * @return void
     */
    public function addEndsWithValidation(string $fieldName, string $errorMessage, string $search): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new StringEndsWith($search)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts two fields are equal
     *
     * @param string $fieldName       The primary field name
     * @param string $errorMessage    The error message
     * @param string $comparisonField The comparison field name
     *
     * @return void
     */
    public function addEqualsValidation(string $fieldName, string $errorMessage, string $comparisonField): void
    {
        $this->addValidator(
            new BasicValidator(
                new EqualFieldsSpecification($fieldName, $comparisonField),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field has an exact count
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $count        The count
     *
     * @return void
     */
    public function addExactCountValidation(string $fieldName, string $errorMessage, string $count): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new CountExact((int) $count)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is an exact length
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $length       The length
     *
     * @return void
     */
    public function addExactLengthValidation(string $fieldName, string $errorMessage, string $length): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new LengthExact((int) $length)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a number matches another number
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $number       The number
     *
     * @return void
     */
    public function addExactNumberValidation(string $fieldName, string $errorMessage, string $number): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new NumberExact($number)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is false
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addFalseValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsFalse()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is falsy
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addFalsyValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsFalsy()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field matches one of a list of values
     *
     * @param string   $fieldName    The field name
     * @param string   $errorMessage The error message
     * @param string[] $list         The list of options
     *
     * @return void
     */
    public function addInListValidation(string $fieldName, string $errorMessage, string ...$list): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new InList($list)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is an IP address
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addIpAddressValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsIpAddress()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is an IP V4 address
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addIpV4AddressValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsIpV4Address()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is an IP V6 address
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addIpV6AddressValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsIpV6Address()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is JSON formatted
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addJsonValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsJson()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a key is set
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $key          The key
     *
     * @return void
     */
    public function addKeyIssetValidation(string $fieldName, string $errorMessage, string $key): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new KeyIsset($key)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a key is not empty
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $key          The key
     *
     * @return void
     */
    public function addKeyNotEmptyValidation(string $fieldName, string $errorMessage, string $key): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new KeyNotEmpty($key)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is list of type
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $type         The type
     *
     * @return void
     */
    public function addListOfValidation(string $fieldName, string $errorMessage, string $type): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsListOf($type)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field matches a regular expression
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $pattern      The regex pattern
     *
     * @return void
     */
    public function addMatchValidation(string $fieldName, string $errorMessage, string $pattern): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsMatch($pattern)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field has a count equal to or less than a given count maximum
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $maxCount     The max count
     *
     * @return void
     */
    public function addMaxCountValidation(string $fieldName, string $errorMessage, string $maxCount): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new CountMax((int) $maxCount)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is less than or equal to a maximum length
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $maxLength    The max length
     *
     * @return void
     */
    public function addMaxLengthValidation(string $fieldName, string $errorMessage, string $maxLength): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new LengthMax((int) $maxLength)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a number is less than or equal to a maximum number
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $maxNumber    The max number
     *
     * @return void
     */
    public function addMaxNumberValidation(string $fieldName, string $errorMessage, string $maxNumber): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new NumberMax($maxNumber)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field has a count equal to or greater than a given count minimum
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minCount     The min count
     *
     * @return void
     */
    public function addMinCountValidation(string $fieldName, string $errorMessage, string $minCount): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new CountMin((int) $minCount)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string is greater than or equal to a minimum length
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minLength    The min length
     *
     * @return void
     */
    public function addMinLengthValidation(string $fieldName, string $errorMessage, string $minLength): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new LengthMin((int) $minLength)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a number is greater than or equal to a minimum number
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minNumber    The min number
     *
     * @return void
     */
    public function addMinNumberValidation(string $fieldName, string $errorMessage, string $minNumber): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new NumberMin($minNumber)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a natural number
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNaturalNumberValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsNaturalNumber()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is not blank
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNotBlankValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsNotBlank()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is not empty
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNotEmptyValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, (new IsEmpty())->not()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts two fields are not equal
     *
     * @param string $fieldName       The primary field name
     * @param string $errorMessage    The error message
     * @param string $comparisonField The comparison field name
     *
     * @return void
     */
    public function addNotEqualsValidation(string $fieldName, string $errorMessage, string $comparisonField): void
    {
        $this->addValidator(
            new BasicValidator(
                (new EqualFieldsSpecification($fieldName, $comparisonField))->not(),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is not null
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNotNullValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, (new IsNull())->not()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts two fields are not the same
     *
     * @param string $fieldName       The field name
     * @param string $errorMessage    The error message
     * @param string $comparisonField The comparison field
     *
     * @return void
     */
    public function addNotSameValidation(string $fieldName, string $errorMessage, string $comparisonField): void
    {
        $this->addValidator(
            new BasicValidator(
                (new SameFieldsSpecification($fieldName, $comparisonField))->not(),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is not scalar
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNotScalarValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, (new IsScalar())->not()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is null
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNullValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsNull()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is numeric
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addNumericValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsNumeric()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field has a count within a defined range
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minCount     The min count
     * @param string $maxCount     The max count
     *
     * @return void
     */
    public function addRangeCountValidation(
        string $fieldName,
        string $errorMessage,
        string $minCount,
        string $maxCount
    ): void {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new CountRange((int) $minCount, (int) $maxCount)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string length is within a range
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minLength    The min length
     * @param string $maxLength    The max length
     *
     * @return void
     */
    public function addRangeLengthValidation(
        string $fieldName,
        string $errorMessage,
        string $minLength,
        string $maxLength
    ): void {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new LengthRange((int) $minLength, (int) $maxLength)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a number is within a range
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $minNumber    The min number
     * @param string $maxNumber    The max number
     *
     * @return void
     */
    public function addRangeNumberValidation(
        string $fieldName,
        string $errorMessage,
        string $minNumber,
        string $maxNumber
    ): void {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new NumberRange($minNumber, $maxNumber)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is required
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addRequiredValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new RequiredFieldSpecification($fieldName),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts two fields are the same
     *
     * @param string $fieldName       The field name
     * @param string $errorMessage    The error message
     * @param string $comparisonField The comparison field
     */
    public function addSameValidation(string $fieldName, string $errorMessage, string $comparisonField): void
    {
        $this->addValidator(
            new BasicValidator(
                new SameFieldsSpecification($fieldName, $comparisonField),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is scalar
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addScalarValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsScalar()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a string starts with a search string
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $search       The search string
     *
     * @return void
     */
    public function addStartsWithValidation(string $fieldName, string $errorMessage, string $search): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new StringStartsWith($search)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a valid time
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $format       The time format
     *
     * @return void
     */
    public function addTimeValidation(string $fieldName, string $errorMessage, string $format): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsDateTime($format)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a timezone
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addTimezoneValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsTimezone()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is true
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addTrueValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsTrue()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts two fields are equal
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addTruthyValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsTruthy()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is of a type
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     * @param string $type         The type
     *
     * @return void
     */
    public function addTypeValidation(string $fieldName, string $errorMessage, string $type): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsType($type)),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a URI
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addUriValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsUri()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a URN
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addUrnValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsUrn()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a UUID
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addUuidValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsUuid()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Adds a validation that asserts a field is a whole number
     *
     * @param string $fieldName    The field name
     * @param string $errorMessage The error message
     *
     * @return void
     */
    public function addWholeNumberValidation(string $fieldName, string $errorMessage): void
    {
        $this->addValidator(
            new BasicValidator(
                new SingleFieldSpecification($fieldName, new IsWholeNumber()),
                $fieldName,
                $errorMessage
            )
        );
    }

    /**
     * Resets the list of validators
     *
     * @return void
     */
    protected function resetValidators(): void
    {
        $this->validators = ArrayList::of(Validator::class);
    }

    /**
     * Creates validation context
     *
     * @param InputData $input The input data
     *
     * @return ValidationContext
     */
    protected function createContext(InputData $input): ValidationContext
    {
        return new ValidationContext($input);
    }
}
