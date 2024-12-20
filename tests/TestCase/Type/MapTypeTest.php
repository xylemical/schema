<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\IntType;
use Xylemical\Schema\Type\MapType;
use Xylemical\Schema\Type\MapTypeEntry;
use Xylemical\Schema\Type\RegexType;
use Xylemical\Schema\Type\StringType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\MapType.
 */
#[CoversClass(MapType::class)]
#[UsesClass(Context::class)]
#[UsesClass(IntType::class)]
#[UsesClass(StringType::class)]
#[UsesClass(RegexType::class)]
#[UsesClass(Validation::class)]
class MapTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $map_error = new Error("Value is not a map.", ["test"]);
    $a_error = new Error("Missing required key (a).", ["test"]);
    $b_error = new Error("Missing required key (b).", ["test"]);
    return [
      [[], NULL, TRUE, [$map_error]],
      [[], '0', TRUE, [$map_error]],
      [[], 0, TRUE, [$map_error]],
      [[], (object) [], TRUE, [$a_error, $b_error]],
      [[], ['a' => 1], TRUE, [$b_error]],
      [[], ['a' => 1, 'b' => 'test'], FALSE, []],
      [[], ['a' => 1, 'b' => 'test', 'c' => TRUE], TRUE, [new Error("Value contains unknown keys (c).", ["test"])]],
      [['unknown' => TRUE], ['a' => 1, 'b' => 'test', 'c' => TRUE], FALSE, []],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($options, $value, $has_errors, $errors): void {
    $type = (new MapType())
      ->add(new MapTypeEntry(new StringType("a"), new IntType()))
      ->add((new MapTypeEntry(new StringType("b"), new RegexType("/\w+/")))->setDefault('test'))
      ->add((new MapTypeEntry(new RegexType("/c\d+/"), (new StringType())->setMin(1)))->setOptional(TRUE))
      ->add((new MapTypeEntry(new StringType("d"), new IntType()))->setOptional(TRUE)->setDefault(100));
    if (isset($options['unknown'])) {
      $type->setAllowsUnknown($options['unknown']);
      $this->assertEquals($options['unknown'], $type->allowsUnknown());
    }

    $context = new Context(["test"]);
    $validation = new Validation();

    $type->validate($value, $validation, $context);
    $this->assertEquals($has_errors, $validation->hasErrors());
    $this->assertEquals($errors, $validation->getErrors());
  }

  /**
   * Provides test data for testCase().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerCast(): array {
    return [
      [[], NULL, ['a' => 0, 'b' => 'test', 'd' => 100]],
      [[], (object) [], ['a' => 0, 'b' => 'test', 'd' => 100]],
      [[], ['a' => '1', 'b' => 'test'], ['a' => 1, 'b' => 'test', 'd' => 100]],
      [['unknown' => TRUE], ['a' => '1', 'b' => 'test', 'e' => 'e'], ['a' => 1, 'b' => 'test', 'd' => 100, 'e' => 'e']],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($options, $data, $expected): void {
    $type = (new MapType())
      ->add(new MapTypeEntry(new StringType("a"), new IntType()))
      ->add((new MapTypeEntry(new StringType("b"), new RegexType("/\w+/")))->setDefault('test'))
      ->add((new MapTypeEntry(new RegexType("/c\d+/"), (new StringType())->setMin(1)))->setOptional(TRUE))
      ->add((new MapTypeEntry(new StringType("d"), new IntType()))->setOptional(TRUE)->setDefault(100));
    if (isset($options['unknown'])) {
      $type->setAllowsUnknown($options['unknown']);
      $this->assertEquals($options['unknown'], $type->allowsUnknown());
    }

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
