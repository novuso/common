<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\Common\Application\Validation\Exception\ValidationException;
use Novuso\Common\Domain\Type\StringObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;

/**
 * Class ValidationService
 */
final class ValidationService
{
    /**
     * Validation coordinator
     *
     * @var ValidationCoordinator
     */
    protected $coordinator;

    /**
     * Constructs ValidationService
     *
     * @param ValidationCoordinator|null $coordinator The validation coordinator
     */
    public function __construct(?ValidationCoordinator $coordinator = null)
    {
        $this->coordinator = $coordinator ?: new ValidationCoordinator();
    }

    /**
     * Performs validation on input with the given rules
     *
     * @param array $input The input data
     * @param array $rules The validation rules
     *
     * @return ApplicationData
     *
     * @throws ValidationException When validation fails
     * @throws DomainException When input or rules are formatted incorrectly
     */
    public function validate(array $input, array $rules): ApplicationData
    {
        $this->validateInput($input);
        $this->validateRules($rules);
        $this->addValidators($rules);

        $result = $this->coordinator->validate(new InputData($input));

        if ($result->isFailed()) {
            throw ValidationException::fromErrors($result->getErrors()->toArray());
        }

        return $result->getData();
    }

    /**
     * Adds a custom validator
     *
     * @param Validator $validator The validator
     *
     * @return void
     */
    public function addValidator(Validator $validator): void
    {
        $this->coordinator->addValidator($validator);
    }

    /**
     * Adds validators to the validation coordinator
     *
     * @param array $rules The validation rules
     *
     * @return void
     *
     * @throws DomainException When rules are formatted incorrectly
     */
    protected function addValidators(array $rules): void
    {
        /** @var array $rule */
        foreach ($rules as $rule) {
            $fieldName = $rule['field'];
            $label = $rule['label'];

            $matchString = null;
            if (StringObject::create($rule['rules'])->contains('match[')) {
                list($rulesString, $matchString) = $this->extractMatchString($rule['rules']);
                $rule['rules'] = $rulesString;
            }

            $validations = explode('|', $rule['rules']);

            if ($matchString !== null) {
                $validations[] = $matchString;
            }

            foreach ($validations as $validation) {
                if (empty($validation)) {
                    continue;
                }

                $validation = StringObject::create($validation);

                if (!$validation->contains('[')) {
                    $ruleName = $validation;

                    $errorMessage = $this->getErrorMessage($rule, $ruleName->toString(), $label);

                    $method = sprintf('add%sValidation', $ruleName->toPascalCase()->toString());
                    $methodArgs = [$fieldName, $errorMessage];
                } else {
                    $ruleName = $validation->substr(0, $validation->indexOf('['));

                    $ruleArgs = $validation->slice(
                        $validation->indexOf('[') + 1,
                        $validation->lastIndexOf(']')
                    );
                    $args = array_map(function (string $arg) {
                        return trim($arg);
                    }, explode(',', $ruleArgs->toString()));

                    // validate date/time formats
                    if (in_array($ruleName->toString(), ['date', 'time', 'date_time'])) {
                        if (!isset($args[0]) || empty($args[0])) {
                            $message = sprintf('%s validation requires format', $ruleName->toString());
                            throw new DomainException($message);
                        }
                        $format = $args[0];
                        $this->validateDateTimeFormat($ruleName->toString(), $format);
                    }

                    $errorMessage = $this->getErrorMessage($rule, $ruleName->toString(), $label, $args);

                    $method = sprintf('add%sValidation', $ruleName->toPascalCase()->toString());
                    $methodArgs = array_merge([$fieldName, $errorMessage], $args);
                }

                // @codeCoverageIgnoreStart
                // exception should be thrown in getErrorMessage
                if (!method_exists($this->coordinator, $method)) {
                    $message = sprintf('Unsupported validation: %s', $validation->toString());
                    throw new DomainException($message);
                }
                // @codeCoverageIgnoreEnd

                call_user_func_array([$this->coordinator, $method], $methodArgs);
            }
        }
    }

    /**
     * Retrieves the error message for a rule
     *
     * @param array  $rule     The rule data
     * @param string $ruleName The rule name
     * @param string $label    The field label
     * @param array  $args     The rule arguments
     *
     * @return string
     *
     * @throws DomainException When rules are formatted incorrectly
     */
    protected function getErrorMessage(array $rule, string $ruleName, string $label, array $args = []): string
    {
        try {
            $format = ErrorMessage::fromName(
                StringObject::create($ruleName)
                    ->toUpperUnderscored()
                    ->toString()
            )->value();
        } catch (DomainException $e) {
            $message = sprintf('Unsupported rule name: %s', $ruleName);
            throw new DomainException($message, $e->getCode(), $e);
        }

        if (isset($rule['errors'][$ruleName])) {
            $format = $rule['errors'][$ruleName];
        }

        if ($ruleName === 'in_list') {
            $listString = sprintf('[%s]', rtrim(str_repeat('%s,', count($args)), ','));
            $format = str_replace('{{list}}', $listString, $format);
        }

        return call_user_func_array('sprintf', array_merge([$format, $label], $args));
    }

    /**
     * Validates validation rules
     *
     * @param array $rules The validation rules
     *
     * @throws DomainException When rules are formatted incorrectly
     */
    protected function validateRules(array $rules): void
    {
        /** @var array $rule */
        foreach ($rules as $rule) {
            if (!is_array($rule)) {
                $message = sprintf('Invalid rule definition: %s', VarPrinter::toString($rules));
                throw new DomainException($message);
            }
            if (!isset($rule['field'])) {
                $message = sprintf('Field is required: %s', VarPrinter::toString($rule));
                throw new DomainException($message);
            }
            if (!isset($rule['label'])) {
                $message = sprintf('Label is required: %s', VarPrinter::toString($rule));
                throw new DomainException($message);
            }
            if (!isset($rule['rules'])) {
                $message = sprintf('Rules are required: %s', VarPrinter::toString($rule));
                throw new DomainException($message);
            }
        }
    }

    /**
     * Validates input data
     *
     * @param array $input The input data
     *
     * @return void
     *
     * @throws DomainException When input is formatted incorrectly
     */
    protected function validateInput(array $input): void
    {
        if (!Validate::isListOf(array_keys($input), 'string')) {
            $message = sprintf('Input keys should be strings: %s', VarPrinter::toString($input));
            throw new DomainException($message);
        }
    }

    /**
     * Validates date/time format
     *
     * @param string $ruleName The rule name
     * @param string $format The date/time format
     *
     * @return void
     *
     * @throws DomainException When the format is invalid
     */
    protected function validateDateTimeFormat(string $ruleName, string $format): void
    {
        $unreservedChars = '\s+-_.:;,\/\[\]\(\)\|';
        $dateFormats = 'dDjlNSwzWFmMntLoYy';
        $timeFormats = 'aABgGhHisuveIOPTZ';
        $dateTimeFormats = sprintf('crU%s%s', $dateFormats, $timeFormats);
        $regexFormat = '/^([%s%s]+|[\\\\]{1}.{1})+$/';

        switch ($ruleName) {
            case 'date':
                $pattern = sprintf($regexFormat, $dateFormats, $unreservedChars);
                if (!preg_match($pattern, $format)) {
                    $message = sprintf('Invalid date format "%s"', $format);
                    throw new DomainException($message);
                }
                break;
            case 'time':
                $pattern = sprintf($regexFormat, $timeFormats, $unreservedChars);
                if (!preg_match($pattern, $format)) {
                    $message = sprintf('Invalid time format "%s"', $format);
                    throw new DomainException($message);
                }
                break;
            case 'date_time':
                $pattern = sprintf($regexFormat, $dateTimeFormats, $unreservedChars);
                if (!preg_match($pattern, $format)) {
                    $message = sprintf('Invalid date/time format "%s"', $format);
                    throw new DomainException($message);
                }
                break;
        }
    }

    /**
     * Extracts match portion of the rules string
     *
     * @param string $rulesString The entire rules string
     *
     * @return array
     */
    protected function extractMatchString(string $rulesString): array
    {
        $rules = StringObject::create($rulesString);
        $matchString = '';

        $startPos = $rules->indexOf('match[');
        $remainingParts = $rules->substr($startPos)->split('|');
        $ruleSet = array_change_key_case(ErrorMessage::getMembers(), CASE_LOWER);
        /** @var StringObject $part */
        foreach ($remainingParts as $part) {
            if ($part->startsWith('match[')) {
                $matchString .= $part->toString();
                continue;
            }
            if ($part->indexOf('[') !== -1) {
                $ruleName = $part->substr(0, $part->indexOf('['))->toString();
                if (isset($ruleSet[$ruleName])) {
                    break;
                }
            }
            $matchString .= sprintf('|%s', $part->toString());
        }

        return [str_replace($matchString, '', $rulesString), $matchString];
    }
}
