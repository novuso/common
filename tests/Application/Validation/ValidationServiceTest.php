<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation;

use Mockery\MockInterface;
use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Exception\ValidationException;
use Novuso\Common\Application\Validation\ValidationService;
use Novuso\Common\Application\Validation\Validator;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\ValidationService
 * @covers \Novuso\Common\Application\Validation\RulesParser
 */
class ValidationServiceTest extends UnitTestCase
{
    /** @var ValidationService */
    protected $validationService;

    protected function setUp(): void
    {
        $this->validationService = new ValidationService();
    }

    public function test_that_add_validator_allows_adding_custom_validation()
    {
        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field'  => 'foo',
                'label'  => 'Foo',
                'rules'  => 'not_empty',
                'errors' => [
                    'not_empty' => 'Foo cannot be empty'
                ]
            ]
        ];

        /** @var Validator|MockInterface $validator */
        $validator = $this->mock(Validator::class);
        $validator
            ->shouldReceive('validate')
            ->once()
            ->andReturnTrue();

        $this->validationService->addValidator($validator);

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_simple_validation_passes()
    {
        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field'  => 'foo',
                'label'  => 'Foo',
                'rules'  => 'not_empty',
                'errors' => [
                    'not_empty' => 'Foo cannot be empty'
                ]
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_it_can_parse_rules_with_arguments()
    {
        $input = [
            'foo' => 'bar',
            'baz' => 'bar'
        ];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo',
                'rules' => 'not_empty|equals[baz]'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_in_list_error_message_is_formatted_as_expected()
    {
        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo',
                'rules' => 'in_list[one,two,three]'
            ]
        ];

        try {
            $this->validationService->validate($input, $rules);
        } catch (ValidationException $e) {
            static::assertSame('Foo must be one of [one,two,three]', $e->getErrors()['foo'][0]);
        }
    }

    public function test_that_match_accepts_character_class_regular_expressions()
    {
        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'foo',
                'rules' => 'match[/[a-z]+/]'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_required_accepts_falsey_values_when_present()
    {
        $input = ['foo' => false];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'foo',
                'rules' => 'required'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_validation_passes_when_item_missing_and_not_required()
    {
        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'baz',
                'label' => 'baz',
                'rules' => 'max_length[100]'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_validation_rules_allow_regex_with_pipes()
    {
        $input = ['foo' => 'ASD67feHJM'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'foo',
                'rules' => 'required|match[/^([A-Z0-9]|[a-f])+$/]|type[string]'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_validation_rules_allow_valid_date_time_formats()
    {
        $input = [
            'date'      => '2016-01-20',
            'time'      => '1:32:30PM',
            'date_time' => '2016-01-20T13:32:30'
        ];

        $rules = [
            [
                'field' => 'date',
                'label' => 'date',
                'rules' => 'required|date[Y-m-d]'
            ],
            [
                'field' => 'time',
                'label' => 'time',
                'rules' => 'required|time[g:i:sA]'
            ],
            [
                'field' => 'date_time',
                'label' => 'date_time',
                'rules' => 'required|date_time[Y-m-d\TH:i:s]'
            ]
        ];

        $applicationData = $this->validationService->validate($input, $rules);

        static::assertInstanceOf(ApplicationData::class, $applicationData);
    }

    public function test_that_validation_throws_exception_for_missing_date_format()
    {
        $this->expectException(DomainException::class);

        $input = [
            'date' => '2016-01-20 13:32:30'
        ];

        $rules = [
            [
                'field' => 'date',
                'label' => 'date',
                'rules' => 'required|date[]'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validation_throws_exception_for_invalid_date_format()
    {
        $this->expectException(DomainException::class);

        $input = [
            'date' => '2016-01-20 13:32:30'
        ];

        $rules = [
            [
                'field' => 'date',
                'label' => 'date',
                'rules' => 'required|date[Y-m-d H:i:s]'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validation_throws_exception_for_invalid_time_format()
    {
        $this->expectException(DomainException::class);

        $input = [
            'time' => '2016-01-20 13:32:30'
        ];

        $rules = [
            [
                'field' => 'time',
                'label' => 'time',
                'rules' => 'required|time[Y-m-d H:i:s]'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validation_throws_exception_for_invalid_date_time_format()
    {
        $this->expectException(DomainException::class);

        $input = [
            'date_time' => '2016-01-20$13:32:30'
        ];

        $rules = [
            [
                'field' => 'date_time',
                'label' => 'date_time',
                'rules' => 'required|date_time[Y-m-d$H:i:s]'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_rules_is_not_array_of_arrays()
    {
        $this->expectException(DomainException::class);

        $input = ['foo' => 'bar'];

        $rules = [
            'field' => 'foo',
            'label' => 'Foo',
            'rules' => 'not_blank'
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_rule_is_missing_field()
    {
        $this->expectException(DomainException::class);

        $input = ['foo' => 'bar'];

        $rules = [
            [
                'label' => 'Foo',
                'rules' => 'not_blank'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_rule_is_missing_label()
    {
        $this->expectException(DomainException::class);

        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'rules' => 'not_blank'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_rule_is_missing_rules()
    {
        $this->expectException(DomainException::class);

        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_input_has_non_string_keys()
    {
        $this->expectException(DomainException::class);

        $input = ['foo', 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo',
                'rules' => 'not_blank'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_rule_contains_unsupported_rule_name()
    {
        $this->expectException(DomainException::class);

        $input = ['foo' => 'bar'];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo',
                'rules' => 'foo'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }

    public function test_that_validate_throws_exception_when_validation_fails()
    {
        $this->expectException(ValidationException::class);

        $input = ['foo' => ' '];

        $rules = [
            [
                'field' => 'foo',
                'label' => 'Foo',
                'rules' => 'not_blank'
            ]
        ];

        $this->validationService->validate($input, $rules);
    }
}
