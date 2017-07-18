<?php

namespace Novuso\Test\Common\Domain\Type;

use Novuso\Common\Domain\Type\StringObject;
use Novuso\System\Collection\Api\ListInterface;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Type\StringObject
 * @covers \Novuso\Common\Domain\Type\Traits\StringOffsets
 */
class StringObjectTest extends UnitTestCase
{
    public function test_that_constructor_takes_string_value()
    {
        $string = new StringObject('hello');
        $this->assertSame('hello', $string->value());
    }

    public function test_that_create_returns_expected_instance()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello', $string->value());
    }

    public function test_that_is_empty_returns_true_for_empty_string()
    {
        $string = StringObject::create('');
        $this->assertTrue($string->isEmpty());
    }

    public function test_that_is_empty_returns_false_for_non_empty_string()
    {
        $string = StringObject::create('hello');
        $this->assertFalse($string->isEmpty());
    }

    public function test_that_length_returns_expected_value()
    {
        $string = StringObject::create('hello');
        $this->assertSame(5, $string->length());
    }

    public function test_that_count_returns_expected_value()
    {
        $string = StringObject::create('hello');
        $this->assertSame(5, count($string));
    }

    public function test_that_get_returns_character_at_pos_index()
    {
        $string = StringObject::create('hello');
        $this->assertSame('e', $string->get(1));
    }

    public function test_that_get_returns_character_at_neg_index()
    {
        $string = StringObject::create('hello');
        $this->assertSame('o', $string->get(-1));
    }

    public function test_that_has_returns_true_for_valid_index()
    {
        $string = StringObject::create('hello');
        $this->assertTrue($string->has(3));
    }

    public function test_that_has_returns_false_for_invalid_index()
    {
        $string = StringObject::create('hello');
        $this->assertFalse($string->has(5));
    }

    public function test_that_offset_get_returns_character_at_pos_index()
    {
        $string = StringObject::create('hello');
        $this->assertSame('e', $string[1]);
    }

    public function test_that_offset_get_returns_character_at_neg_index()
    {
        $string = StringObject::create('hello');
        $this->assertSame('o', $string[-1]);
    }

    public function test_that_offset_exists_returns_true_for_valid_index()
    {
        $string = StringObject::create('hello');
        $this->assertTrue(isset($string[3]));
    }

    public function test_that_offset_exists_returns_false_for_invalid_index()
    {
        $string = StringObject::create('hello');
        $this->assertFalse(isset($string[5]));
    }

    public function test_that_chars_returns_list_instance()
    {
        $string = StringObject::create('hello');
        $this->assertInstanceOf(ListInterface::class, $string->chars());
    }

    public function test_that_contains_returns_true_for_valid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->contains('wo'));
    }

    public function test_that_contains_returns_true_for_case_insensitive_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->contains('WO', false));
    }

    public function test_that_contains_returns_false_for_invalid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertFalse($string->contains('WO'));
    }

    public function test_that_contains_returns_false_for_empty_value()
    {
        $string = StringObject::create('');
        $this->assertFalse($string->contains(''));
    }

    public function test_that_contains_returns_true_for_empty_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->contains(''));
    }

    public function test_that_starts_with_returns_true_for_valid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->startsWith('hello'));
    }

    public function test_that_starts_with_returns_true_for_case_insensitive_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->startsWith('Hello', false));
    }

    public function test_that_starts_with_returns_false_for_invalid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertFalse($string->startsWith('Hello'));
    }

    public function test_that_starts_with_returns_false_for_empty_value()
    {
        $string = StringObject::create('');
        $this->assertFalse($string->startsWith(''));
    }

    public function test_that_starts_with_returns_true_for_empty_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->startsWith(''));
    }

    public function test_that_ends_with_returns_true_for_valid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->endsWith('world'));
    }

    public function test_that_ends_with_returns_true_for_case_insensitive_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->endsWith('World', false));
    }

    public function test_that_ends_with_returns_false_for_invalid_search()
    {
        $string = StringObject::create('hello world');
        $this->assertFalse($string->endsWith('World'));
    }

    public function test_that_ends_with_returns_false_for_empty_value()
    {
        $string = StringObject::create('');
        $this->assertFalse($string->endsWith(''));
    }

    public function test_that_ends_with_returns_true_for_empty_search()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->endsWith(''));
    }

    public function test_that_index_of_returns_index_no_start_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(2, $string->indexOf('l'));
    }

    public function test_that_index_of_returns_index_pos_start_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(7, $string->indexOf('o', 5));
    }

    public function test_that_index_of_returns_index_neg_start_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(7, $string->indexOf('o', -5));
    }

    public function test_that_index_of_returns_index_pos_start_case_insensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(7, $string->indexOf('O', 5, false));
    }

    public function test_that_index_of_returns_index_neg_start_case_insensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(7, $string->indexOf('O', -5, false));
    }

    public function test_that_index_of_returns_neg_one_for_unmatched_search()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(-1, $string->indexOf('hey'));
    }

    public function test_that_index_of_returns_neg_one_for_empty_value()
    {
        $string = StringObject::create('');
        $this->assertSame(-1, $string->indexOf(''));
    }

    public function test_that_index_of_returns_start_for_empty_search()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(3, $string->indexOf('', 3));
    }

    public function test_that_last_index_of_returns_index_no_stop_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(9, $string->lastIndexOf('l'));
    }

    public function test_that_last_index_of_returns_index_pos_stop_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(4, $string->lastIndexOf('o', 5));
    }

    public function test_that_last_index_of_returns_index_neg_stop_case_sensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(4, $string->lastIndexOf('o', -5));
    }

    public function test_that_last_index_of_returns_index_pos_stop_case_insensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(4, $string->lastIndexOf('O', 5, false));
    }

    public function test_that_last_index_of_returns_index_neg_stop_case_insensitive()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(4, $string->lastIndexOf('O', -5, false));
    }

    public function test_that_last_index_of_returns_neg_one_for_unmatched_search()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(-1, $string->lastIndexOf('hey'));
    }

    public function test_that_last_index_of_returns_neg_one_for_empty_value()
    {
        $string = StringObject::create('');
        $this->assertSame(-1, $string->lastIndexOf(''));
    }

    public function test_that_last_index_of_returns_stop_for_empty_search()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(3, $string->lastIndexOf('', 3));
    }

    public function test_that_append_returns_instance_with_string_appended()
    {
        $string = StringObject::create('hello');
        $newstr = $string->append(' world');
        $this->assertSame('hello world', $newstr->toString());
    }

    public function test_that_prepend_returns_instance_with_string_prepended()
    {
        $string = StringObject::create('world');
        $newstr = $string->prepend('hello ');
        $this->assertSame('hello world', $newstr->toString());
    }

    public function test_that_insert_returns_instance_with_string_inserted()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->insert(6, 'to the ');
        $this->assertSame('hello to the world', $newstr->toString());
    }

    public function test_that_slice_returns_instance_from_start_to_end()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->slice(6);
        $this->assertSame('world', $newstr->toString());
    }

    public function test_that_slice_returns_instance_from_start_to_stop()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->slice(6, 8);
        $this->assertSame('wo', $newstr->toString());
    }

    public function test_that_slice_returns_instance_from_start_to_neg_stop()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->slice(6, -3);
        $this->assertSame('wo', $newstr->toString());
    }

    public function test_that_slice_returns_instance_from_start_to_exceeding_stop()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->slice(6, 20);
        $this->assertSame('world', $newstr->toString());
    }

    public function test_that_substr_returns_instance_from_start_to_end()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->substr(6);
        $this->assertSame('world', $newstr->toString());
    }

    public function test_that_substr_returns_instance_from_start_with_length()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->substr(6, 2);
        $this->assertSame('wo', $newstr->toString());
    }

    public function test_that_substr_returns_instance_from_start_to_neg_length()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->substr(6, -3);
        $this->assertSame('wo', $newstr->toString());
    }

    public function test_that_substr_returns_instance_from_start_to_exceeding_length()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->substr(6, 20);
        $this->assertSame('world', $newstr->toString());
    }

    public function test_that_split_returns_list_split_on_whitespace_by_default()
    {
        $string = StringObject::create('hello world');
        $parts = $string->split();
        $this->assertSame(2, count($parts));
    }

    public function test_that_split_returns_list_with_limit()
    {
        $string = StringObject::create('foo:bar:baz');
        $parts = $string->split(':', 2);
        $this->assertSame(2, count($parts));
    }

    public function test_that_chunk_returns_list_of_expected_size()
    {
        $string = StringObject::create('hello world');
        $parts = $string->chunk(2);
        $this->assertSame(6, count($parts));
    }

    public function test_that_chunk_returns_single_item_if_size_greater_than_length()
    {
        $string = StringObject::create('hello world');
        $parts = $string->chunk(20);
        $this->assertSame(1, count($parts));
    }

    public function test_that_replace_returns_expected_string()
    {
        $string = StringObject::create('hello world');
        $newstr = $string->replace('hello', 'goodbye');
        $this->assertSame('goodbye world', $newstr->toString());
    }

    public function test_that_trim_removes_outside_whitespace_by_default()
    {
        $string = StringObject::create('   hello   ');
        $this->assertSame('hello', $string->trim()->toString());
    }

    public function test_that_trim_removes_outside_chars_from_mask()
    {
        $string = StringObject::create('--hello--');
        $this->assertSame('hello', $string->trim('-')->toString());
    }

    public function test_that_trim_left_removes_left_whitespace_by_default()
    {
        $string = StringObject::create('   hello   ');
        $this->assertSame('hello   ', $string->trimLeft()->toString());
    }

    public function test_that_trim_left_removes_left_chars_from_mask()
    {
        $string = StringObject::create('--hello--');
        $this->assertSame('hello--', $string->trimLeft('-')->toString());
    }

    public function test_that_trim_right_removes_right_whitespace_by_default()
    {
        $string = StringObject::create('   hello   ');
        $this->assertSame('   hello', $string->trimRight()->toString());
    }

    public function test_that_trim_right_removes_right_chars_from_mask()
    {
        $string = StringObject::create('--hello--');
        $this->assertSame('--hello', $string->trimRight('-')->toString());
    }

    public function test_that_repeat_return_instance_with_repeated_string()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hellohellohello', $string->repeat(3)->toString());
    }

    public function test_that_surround_returns_instance_with_wrapped_string()
    {
        $string = StringObject::create('hello');
        $this->assertSame('###hello###', $string->surround('###')->toString());
    }

    public function test_that_pad_returns_instance_of_padded_string()
    {
        $string = StringObject::create('hello');
        $this->assertSame('       hello        ', $string->pad(20)->toString());
    }

    public function test_that_pad_returns_instance_of_padded_string_special_char()
    {
        $string = StringObject::create('hello');
        $this->assertSame('%%%%%%%hello%%%%%%%%', $string->pad(20, '%')->toString());
    }

    public function test_that_pad_returns_instance_same_string_when_padding_not_needed()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello', $string->pad(4)->toString());
    }

    public function test_that_pad_left_returns_instance_of_padded_string()
    {
        $string = StringObject::create('hello');
        $this->assertSame('               hello', $string->padLeft(20)->toString());
    }

    public function test_that_pad_left_returns_instance_of_padded_string_special_char()
    {
        $string = StringObject::create('hello');
        $this->assertSame('%%%%%%%%%%%%%%%hello', $string->padLeft(20, '%')->toString());
    }

    public function test_that_pad_left_returns_instance_same_string_when_padding_not_needed()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello', $string->padLeft(4)->toString());
    }

    public function test_that_pad_right_returns_instance_of_padded_string()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello               ', $string->padRight(20)->toString());
    }

    public function test_that_pad_right_returns_instance_of_padded_string_special_char()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello%%%%%%%%%%%%%%%', $string->padRight(20, '%')->toString());
    }

    public function test_that_pad_right_returns_instance_same_string_when_padding_not_needed()
    {
        $string = StringObject::create('hello');
        $this->assertSame('hello', $string->padRight(4)->toString());
    }

    public function test_that_truncate_returns_instance_same_string_when_not_needed()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello world', $string->truncate(12)->toString());
    }

    public function test_that_truncate_returns_instance_expected_string_with_append()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello...', $string->truncate(8, '...')->toString());
    }

    public function test_that_truncate_returns_instance_expected_string_without_append()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello wo', $string->truncate(8)->toString());
    }

    public function test_that_truncate_words_returns_instance_same_string_when_not_needed()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello world', $string->truncateWords(12)->toString());
    }

    public function test_that_truncate_words_returns_instance_expected_string_with_append()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello...', $string->truncateWords(8, '...')->toString());
    }

    public function test_that_truncate_words_does_not_fail_when_one_word_remains()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hell...', $string->truncateWords(7, '...')->toString());
    }

    public function test_that_truncate_words_returns_instance_expected_string_without_append()
    {
        $string = StringObject::create('hello world');
        $this->assertSame('hello', $string->truncateWords(8)->toString());
    }

    public function test_that_expand_tabs_returns_instance_expected_string()
    {
        $string = StringObject::create("\t\tpublic function name();");
        $this->assertSame('    public function name();', $string->expandTabs(2)->toString());
    }

    public function test_that_to_lower_case_returns_instance_expected_string()
    {
        $string = StringObject::create('GET');
        $this->assertSame('get', $string->toLowerCase()->toString());
    }

    public function test_that_to_upper_case_returns_instance_expected_string()
    {
        $string = StringObject::create('get');
        $this->assertSame('GET', $string->toUpperCase()->toString());
    }

    public function test_that_to_first_lower_case_returns_instance_expected_string()
    {
        $string = StringObject::create('GET');
        $this->assertSame('gET', $string->toFirstLowerCase()->toString());
    }

    public function test_that_to_first_lower_case_does_not_fail_with_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toFirstLowerCase()->toString());
    }

    public function test_that_to_first_lower_case_does_not_fail_with_single_char()
    {
        $string = StringObject::create('A');
        $this->assertSame('a', $string->toFirstLowerCase()->toString());
    }

    public function test_that_to_first_upper_case_returns_instance_expected_string()
    {
        $string = StringObject::create('get');
        $this->assertSame('Get', $string->toFirstUpperCase()->toString());
    }

    public function test_that_to_first_upper_case_does_not_fail_with_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toFirstUpperCase()->toString());
    }

    public function test_that_to_first_upper_case_does_not_fail_with_single_char()
    {
        $string = StringObject::create('a');
        $this->assertSame('A', $string->toFirstUpperCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_camel_case_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('testStringForConversionMethods', $string->toCamelCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_string_with_single_caps()
    {
        $string  = StringObject::create('testAStringTestAStringTestAStringForThis');
        $this->assertSame('TestAStringTestAStringTestAStringForThis', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_pascal_case_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('TestStringForConversionMethods', $string->toPascalCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_snake_case_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('test_string_for_conversion_methods', $string->toSnakeCase()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_lower_hyphenated_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('test-string-for-conversion-methods', $string->toLowerHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_upper_hyphenated_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('TEST-STRING-FOR-CONVERSION-METHODS', $string->toUpperHyphenated()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_lower_underscored_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('test_string_for_conversion_methods', $string->toLowerUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_empty_string()
    {
        $string = StringObject::create('');
        $this->assertSame('', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_camel_case()
    {
        $string = StringObject::create('testStringForConversionMethods');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_pascal_case()
    {
        $string = StringObject::create('TestStringForConversionMethods');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_snake_case()
    {
        $string = StringObject::create('test_string_for_conversion_methods');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_lower_hyphenated()
    {
        $string = StringObject::create('test-string-for-conversion-methods');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_upper_hyphenated()
    {
        $string = StringObject::create('TEST-STRING-FOR-CONVERSION-METHODS');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_upper_underscored_returns_expected_from_upper_underscore()
    {
        $string = StringObject::create('TEST_STRING_FOR_CONVERSION_METHODS');
        $this->assertSame('TEST_STRING_FOR_CONVERSION_METHODS', $string->toUpperUnderscored()->toString());
    }

    public function test_that_to_slug_returns_expected_string()
    {
        $string = StringObject::create('February 14th, 2007');
        $this->assertSame('february-14th-2007', (string) $string->toSlug());
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $string = StringObject::create('hello world');
        $this->assertTrue($string->equals($string));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $string1 = StringObject::create('hello world');
        $string2 = StringObject::create('hello world');
        $this->assertTrue($string1->equals($string2));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $string1 = StringObject::create('hello world');
        $string2 = StringObject::create('Hello World');
        $this->assertFalse($string1->equals($string2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $string = StringObject::create('hello world');
        $this->assertFalse($string->equals('hello world'));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $string = StringObject::create('hello world');
        $this->assertSame(0, $string->compareTo($string));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $string1 = StringObject::create('hello world');
        $string2 = StringObject::create('hello world');
        $this->assertSame(0, $string1->compareTo($string2));
    }

    public function test_that_compare_to_returns_pos_one_for_greater_value()
    {
        $string1 = StringObject::create('hello');
        $string2 = StringObject::create('goodbye');
        $this->assertSame(1, $string1->compareTo($string2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $string1 = StringObject::create('goodbye');
        $string2 = StringObject::create('hello');
        $this->assertSame(-1, $string1->compareTo($string2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $value = 'hello';
        $string = StringObject::create($value);
        $this->assertSame($value, $string->hashValue());
    }

    public function test_that_it_is_json_encodable()
    {
        $string = StringObject::create('hello');
        $data = ['greeting' => $string];
        $expected = '{"greeting":"hello"}';
        $this->assertSame($expected, json_encode($data));
    }

    public function test_that_it_is_iterable()
    {
        $string = StringObject::create('hello');
        $count = 0;
        foreach ($string as $char) {
            $count++;
        }
        $this->assertSame(5, $count);
    }

    /**
     * @expectedException \Novuso\System\Exception\IndexException
     */
    public function test_that_get_throws_exception_for_invalid_index()
    {
        StringObject::create('hello')->get(7);
    }

    /**
     * @expectedException \Novuso\System\Exception\ImmutableException
     */
    public function test_that_offset_set_throws_exception_when_called()
    {
        $string = StringObject::create('hello');
        $string[2] = 'y';
    }

    /**
     * @expectedException \Novuso\System\Exception\ImmutableException
     */
    public function test_that_offset_unset_throws_exception_when_called()
    {
        $string = StringObject::create('hello');
        unset($string[2]);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_index_of_throws_exception_for_invalid_start()
    {
        $string = StringObject::create('hello');
        $string->indexOf('l', 13);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_substr_throws_exception_for_invalid_length()
    {
        $string = StringObject::create('hello');
        $string->substr(0, -10);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_split_throws_exception_for_invalid_delimiter()
    {
        $string = StringObject::create('hello');
        $string->split('');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_slice_throws_exception_for_invalid_stop()
    {
        $string = StringObject::create('hello');
        $string->slice(4, 2);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_throws_exception_for_invalid_strlen()
    {
        $string = StringObject::create('hello');
        $string->pad(-1);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_throws_exception_for_invalid_char()
    {
        $string = StringObject::create('hello');
        $string->pad(1, '');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_left_throws_exception_for_invalid_strlen()
    {
        $string = StringObject::create('hello');
        $string->padLeft(-1);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_left_throws_exception_for_invalid_char()
    {
        $string = StringObject::create('hello');
        $string->padLeft(1, '');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_right_throws_exception_for_invalid_strlen()
    {
        $string = StringObject::create('hello');
        $string->padRight(-1);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_pad_right_throws_exception_for_invalid_char()
    {
        $string = StringObject::create('hello');
        $string->padRight(1, '');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_truncate_throws_exception_for_invalid_strlen()
    {
        $string = StringObject::create('hello');
        $string->truncate(0);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_truncate_throws_exception_for_invalid_append()
    {
        $string = StringObject::create('hello');
        $string->truncate(5, 'A string with more than 5 characters');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_truncate_words_throws_exception_for_invalid_strlen()
    {
        $string = StringObject::create('hello');
        $string->truncateWords(0);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_truncate_words_throws_exception_for_invalid_append()
    {
        $string = StringObject::create('hello');
        $string->truncateWords(5, 'A string with more than 5 characters');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_repeat_throws_exception_for_invalid_multiplier()
    {
        $string = StringObject::create('hello');
        $string->repeat(0);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_chunk_throws_exception_for_invalid_size()
    {
        $string = StringObject::create('hello');
        $string->chunk(0);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_expand_tabs_throws_exception_for_invalid_tabsize()
    {
        $string = StringObject::create('hello');
        $string->expandTabs(-1);
    }

    /**
     * @expectedException \AssertionError
     */
    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $string = StringObject::create('hello');
        $string->compareTo('hello');
    }
}
