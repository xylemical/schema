<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\StringType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\StringType.
 */
#[CoversClass(StringType::class)]
#[UsesClass(Context::class)]
#[UsesClass(Validation::class)]
class StringTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $string_error = new Error("Value needs to be a string.", ["test"]);
    return [
      ["a", [], NULL, TRUE, [$string_error]],
      ["a", [], TRUE, TRUE, [$string_error]],
      ["a", [], [], TRUE, [$string_error]],
      ["a", [], (object) [], TRUE, [$string_error]],
      ["a", [], "a", FALSE, []],
      ["a", [], "b", TRUE, [new Error("Value does not match required value.", ["test"])]],
      [NULL, ['min' => 1], "", TRUE, [new Error("Value must be at least 1 characters.", ["test"])]],
      [NULL, ['min' => 1], "a", FALSE, []],
      [NULL, ['max' => 1], "a", FALSE, []],
      [NULL, ['max' => 1], "ab", TRUE, [new Error("Value cannot exceed 1 characters.", ["test"])]],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($default, $options, $value, $has_errors, $errors): void {
    $type = new StringType($default);
    if (isset($options['min'])) {
      $type->setMin($options['min']);
      $this->assertEquals($options['min'], $type->getMin());
    }
    if (isset($options['max'])) {
      $type->setMax($options['max']);
      $this->assertEquals($options['max'], $type->getMax());
    }
    $this->assertEquals(!is_null($default), $type->isConstant());
    $this->assertEquals($default, $type->getConstant());

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
      ["", NULL, ""],
      ["", TRUE, "1"],
      ["", (object)[], ""],
      ["", [], ""],
      ["", "", ""],
      ["", 0, "0"],
      ["a", "", "a"],
      ["a", "b", "a"],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($string, $data, $expected): void {
    $type = new StringType($string);

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
