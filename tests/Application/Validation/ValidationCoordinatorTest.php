<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation;

use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\Common\Application\Validation\ValidationCoordinator;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\ValidationCoordinator
 */
class ValidationCoordinatorTest extends UnitTestCase
{
    use TestDataProvider;

    /** @var ValidationCoordinator */
    protected $coordinator;

    protected function setUp(): void
    {
        $this->coordinator = new ValidationCoordinator();
    }

    public function test_that_input_data_is_added_to_application_data_when_validators_are_not_present()
    {
        $input = new InputData(['foo' => 'bar']);

        $result = $this->coordinator->validate($input);

        $this->assertSame('bar', $result->getData()->get('foo'));
    }

    /**
     * @dataProvider validAlphaProvider
     */
    public function test_that_alpha_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidAlphaProvider
     */
    public function test_that_alpha_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validAlphaDashProvider
     */
    public function test_that_alpha_dash_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaDashValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidAlphaDashProvider
     */
    public function test_that_alpha_dash_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaDashValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validAlNumProvider
     */
    public function test_that_alpha_num_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaNumValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidAlNumProvider
     */
    public function test_that_alpha_num_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaNumValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validAlNumDashProvider
     */
    public function test_that_alpha_num_dash_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaNumDashValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidAlNumDashProvider
     */
    public function test_that_alpha_num_dash_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addAlphaNumDashValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validContainsProvider
     */
    public function test_that_contains_validation_passes_when_expected($value, $substring)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addContainsValidation('foo', 'Error', $substring);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidContainsProvider
     */
    public function test_that_contains_validation_fails_when_expected($value, $substring)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addContainsValidation('foo', 'Error', $substring);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validDateProvider
     */
    public function test_that_date_validation_passes_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDateValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidDateProvider
     */
    public function test_that_date_validation_fails_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDateValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validDateTimeProvider
     */
    public function test_that_date_time_validation_passes_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDateTimeValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidDateTimeProvider
     */
    public function test_that_date_time_validation_fails_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDateTimeValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validDigitsProvider
     */
    public function test_that_digits_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDigitsValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidDigitsProvider
     */
    public function test_that_digits_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addDigitsValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validEmailProvider
     */
    public function test_that_email_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEmailValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidEmailProvider
     */
    public function test_that_email_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEmailValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validEndsWithProvider
     */
    public function test_that_ends_with_validation_passes_when_expected($value, $searchString)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEndsWithValidation('foo', 'Error', $searchString);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidEndsWithProvider
     */
    public function test_that_ends_with_validation_fails_when_expected($value, $searchString)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEndsWithValidation('foo', 'Error', $searchString);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validExactCountProvider
     */
    public function test_that_exact_count_validation_passes_when_expected($value, $count)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactCountValidation('foo', 'Error', $count);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidExactCountProvider
     */
    public function test_that_exact_count_validation_fails_when_expected($value, $count)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactCountValidation('foo', 'Error', $count);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validExactLengthProvider
     */
    public function test_that_exact_length_validation_passes_when_expected($value, $length)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactLengthValidation('foo', 'Error', $length);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidExactLengthProvider
     */
    public function test_that_exact_length_validation_fails_when_expected($value, $length)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactLengthValidation('foo', 'Error', $length);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validExactNumberProvider
     */
    public function test_that_exact_number_validation_passes_when_expected($value, $match)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactNumberValidation('foo', 'Error', $match);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidExactNumberProvider
     */
    public function test_that_exact_number_validation_fails_when_expected($value, $match)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addExactNumberValidation('foo', 'Error', $match);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validIpAddressProvider
     */
    public function test_that_ip_address_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpAddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidIpAddressProvider
     */
    public function test_that_ip_address_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpAddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validIpV4AddressProvider
     */
    public function test_that_ip_v4_address_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpV4AddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidIpV4AddressProvider
     */
    public function test_that_ip_v4_address_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpV4AddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validIpV6AddressProvider
     */
    public function test_that_ip_v6_address_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpV6AddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidIpV6AddressProvider
     */
    public function test_that_ip_v6_address_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addIpV6AddressValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validJsonProvider
     */
    public function test_that_json_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addJsonValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidJsonProvider
     */
    public function test_that_json_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addJsonValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validKeyIssetProvider
     */
    public function test_that_key_isset_validation_passes_when_expected($value, $key)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addKeyIssetValidation('foo', 'Error', $key);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidKeyIssetProvider
     */
    public function test_that_key_isset_validation_fails_when_expected($value, $key)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addKeyIssetValidation('foo', 'Error', $key);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validKeyNotEmptyProvider
     */
    public function test_that_key_not_empty_validation_passes_when_expected($value, $key)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addKeyNotEmptyValidation('foo', 'Error', $key);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidKeyNotEmptyProvider
     */
    public function test_that_key_not_empty_validation_fails_when_expected($value, $key)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addKeyNotEmptyValidation('foo', 'Error', $key);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMaxCountProvider
     */
    public function test_that_max_count_validation_passes_when_expected($value, $maxCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxCountValidation('foo', 'Error', $maxCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMaxCountProvider
     */
    public function test_that_max_count_validation_fails_when_expected($value, $maxCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxCountValidation('foo', 'Error', $maxCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMaxLengthProvider
     */
    public function test_that_max_length_validation_passes_when_expected($value, $maxLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxLengthValidation('foo', 'Error', $maxLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMaxLengthProvider
     */
    public function test_that_max_length_validation_fails_when_expected($value, $maxLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxLengthValidation('foo', 'Error', $maxLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMaxNumberProvider
     */
    public function test_that_max_number_validation_passes_when_expected($value, $maxNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxNumberValidation('foo', 'Error', $maxNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMaxNumberProvider
     */
    public function test_that_max_number_validation_fails_when_expected($value, $maxNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMaxNumberValidation('foo', 'Error', $maxNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMinCountProvider
     */
    public function test_that_min_count_validation_succeeds_when_expected($value, $minCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinCountValidation('foo', 'Error', $minCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMinCountProvider
     */
    public function test_that_min_count_validation_fails_when_expected($value, $minCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinCountValidation('foo', 'Error', $minCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMinLengthProvider
     */
    public function test_that_min_length_validation_succeeds_when_expected($value, $minLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinLengthValidation('foo', 'Error', $minLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMinLengthProvider
     */
    public function test_that_min_length_validation_fails_when_expected($value, $minLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinLengthValidation('foo', 'Error', $minLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMinNumberProvider
     */
    public function test_that_min_number_validation_succeeds_when_expected($value, $minNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinNumberValidation('foo', 'Error', $minNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMinNumberProvider
     */
    public function test_that_min_number_validation_fails_when_expected($value, $minNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMinNumberValidation('foo', 'Error', $minNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNaturalNumberProvider
     */
    public function test_that_natural_number_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNaturalNumberValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function test_that_natural_number_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNaturalNumberValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotSameProvider
     */
    public function test_that_not_same_validation_succeeds_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addNotSameValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotSameProvider
     */
    public function test_that_not_same_validation_fails_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addNotSameValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNumericProvider
     */
    public function test_that_numeric_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNumericValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNumericProvider
     */
    public function test_that_numeric_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNumericValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validRangeCountProvider
     */
    public function test_that_range_count_validation_succeeds_when_expected($value, $minCount, $maxCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeCountValidation('foo', 'Error', $minCount, $maxCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidRangeCountProvider
     */
    public function test_that_range_count_validation_fails_when_expected($value, $minCount, $maxCount)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeCountValidation('foo', 'Error', $minCount, $maxCount);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validRangeLengthProvider
     */
    public function test_that_range_length_validation_succeeds_when_expected($value, $minLength, $maxLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeLengthValidation('foo', 'Error', $minLength, $maxLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidRangeLengthProvider
     */
    public function test_that_range_length_validation_fails_when_expected($value, $minLength, $maxLength)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeLengthValidation('foo', 'Error', $minLength, $maxLength);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validRangeNumberProvider
     */
    public function test_that_range_number_validation_succeeds_when_expected($value, $minNumber, $maxNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeNumberValidation('foo', 'Error', $minNumber, $maxNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidRangeNumberProvider
     */
    public function test_that_range_number_validation_fails_when_expected($value, $minNumber, $maxNumber)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addRangeNumberValidation('foo', 'Error', $minNumber, $maxNumber);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validSameProvider
     */
    public function test_that_same_validation_succeeds_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addSameValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidSameProvider
     */
    public function test_that_same_number_validation_fails_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addSameValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validStartsWithProvider
     */
    public function test_that_starts_with_validation_succeeds_when_expected($value, $subString)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addStartsWithValidation('foo', 'Error', $subString);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidStartsWithProvider
     */
    public function test_that_starts_with_validation_fails_when_expected($value, $subString)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addStartsWithValidation('foo', 'Error', $subString);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validTimeProvider
     */
    public function test_that_time_validation_passes_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTimeValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidTimeProvider
     */
    public function test_that_time_validation_fails_when_expected($value, $format)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTimeValidation('foo', 'Error', $format);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validTimezoneProvider
     */
    public function test_that_timezone_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTimezoneValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidTimezoneProvider
     */
    public function test_that_timezone_with_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTimezoneValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validUriProvider
     */
    public function test_that_uri_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUriValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidUriProvider
     */
    public function test_that_uri_with_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUriValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validUrnProvider
     */
    public function test_that_urn_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUrnValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidUrnProvider
     */
    public function test_that_urn_with_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUrnValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validUuidProvider
     */
    public function test_that_uuid_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUuidValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidUuidProvider
     */
    public function test_that_uuid_with_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addUuidValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validWholeNumberProvider
     */
    public function test_that_whole_number_validation_succeeds_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addWholeNumberValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidWholeNumberProvider
     */
    public function test_that_whole_number_with_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addWholeNumberValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validRequiredProvider
     */
    public function test_that_required_validation_passes_when_expected($key, $data)
    {
        $input = new InputData($data);

        $this->coordinator->addRequiredValidation($key, 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidRequiredProvider
     */
    public function test_that_required_validation_fails_when_expected($key, $data)
    {
        $input = new InputData($data);

        $this->coordinator->addRequiredValidation($key, 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validTrueProvider
     */
    public function test_that_true_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTrueValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidTrueProvider
     */
    public function test_that_true_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTrueValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validFalseProvider
     */
    public function test_that_false_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addFalseValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidFalseProvider
     */
    public function test_that_false_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addFalseValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validTruthyProvider
     */
    public function test_that_truthy_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTruthyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidTruthyProvider
     */
    public function test_that_truthy_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTruthyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validFalseyProvider
     */
    public function test_that_falsey_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addFalsyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidFalseyProvider
     */
    public function test_that_falsey_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addFalsyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validBlankProvider
     */
    public function test_that_blank_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addBlankValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidBlankProvider
     */
    public function test_that_blank_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addBlankValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotBlankProvider
     */
    public function test_that_not_blank_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotBlankValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotBlankProvider
     */
    public function test_that_not_blank_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotBlankValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validEmptyProvider
     */
    public function test_that_empty_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEmptyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidEmptyProvider
     */
    public function test_that_empty_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addEmptyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotEmptyProvider
     */
    public function test_that_not_empty_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotEmptyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotEmptyProvider
     */
    public function test_that_not_empty_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotEmptyValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNullProvider
     */
    public function test_that_null_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNullValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNullProvider
     */
    public function test_that_null_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNullValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotNullProvider
     */
    public function test_that_not_null_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotNullValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotNullProvider
     */
    public function test_that_not_null_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotNullValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validScalarProvider
     */
    public function test_that_scalar_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addScalarValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidScalarProvider
     */
    public function test_that_scalar_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addScalarValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotScalarProvider
     */
    public function test_that_not_scalar_validation_passes_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotScalarValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotScalarProvider
     */
    public function test_that_not_scalar_validation_fails_when_expected($value)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addNotScalarValidation('foo', 'Error');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validEqualsProvider
     */
    public function test_that_equals_validation_passes_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addEqualsValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidEqualsProvider
     */
    public function test_that_equals_validation_fails_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addEqualsValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validNotEqualsProvider
     */
    public function test_that_not_equals_validation_passes_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addNotEqualsValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidNotEqualsProvider
     */
    public function test_that_not_equals_validation_fails_when_expected($value1, $value2)
    {
        $input = new InputData(['foo' => $value1, 'bar' => $value2]);

        $this->coordinator->addNotEqualsValidation('foo', 'Error', 'bar');

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validTypeProvider
     */
    public function test_that_type_validation_passes_when_expected($value, $type)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTypeValidation('foo', 'Error', $type);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidTypeProvider
     */
    public function test_that_type_validation_fails_when_expected($value, $type)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addTypeValidation('foo', 'Error', $type);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validListOfProvider
     */
    public function test_that_list_of_validation_passes_when_expected($value, $type)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addListOfValidation('foo', 'Error', $type);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidListOfProvider
     */
    public function test_that_list_of_validation_fails_when_expected($value, $type)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addListOfValidation('foo', 'Error', $type);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validMatchProvider
     */
    public function test_that_match_validation_passes_when_expected($value, $pattern)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMatchValidation('foo', 'Error', $pattern);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidMatchProvider
     */
    public function test_that_match_validation_fails_when_expected($value, $pattern)
    {
        $input = new InputData(['foo' => $value]);

        $this->coordinator->addMatchValidation('foo', 'Error', $pattern);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }

    /**
     * @dataProvider validOneOfProvider
     */
    public function test_that_in_list_validation_passes_when_expected($value, $list)
    {
        $input = new InputData(['foo' => $value]);

        $args = array_merge(['foo', 'Error'], $list);
        call_user_func_array([$this->coordinator, 'addInListValidation'], $args);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isPassed());
    }

    /**
     * @dataProvider invalidOneOfProvider
     */
    public function test_that_in_list_validation_fails_when_expected($value, $list)
    {
        $input = new InputData(['foo' => $value]);

        $args = array_merge(['foo', 'Error'], $list);
        call_user_func_array([$this->coordinator, 'addInListValidation'], $args);

        $result = $this->coordinator->validate($input);

        $this->assertTrue($result->isFailed());
    }
}
