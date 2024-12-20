<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\IntType;
use Xylemical\Schema\Type\SequenceType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\SequenceType.
 */
#[CoversClass(SequenceType::class)]
#[UsesClass(Context::class)]
#[UsesClass(IntType::class)]
#[UsesClass(Validation::class)]
class SequenceTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $sequence_error = new Error("Value is not a sequence.", ["test"]);
    return [
      [[], NULL, TRUE, [$sequence_error]],
      [[], TRUE, TRUE, [$sequence_error]],
      [[], (object) [], TRUE, [$sequence_error]],
      [[], ["a" => 0], TRUE, [$sequence_error]],
      [[], [], FALSE, []],
      [['min' => 1], [], TRUE, [new Error("Value does not contain enough items.", ["test"])]],
      [['min' => 1], ["0"], FALSE, []],
      [['max' => 1], [], FALSE, []],
      [['max' => 1], ["0", "1"], TRUE, [new Error("Value contains too many items.", ["test"])]],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($options, $value, $has_errors, $errors): void {
    $type = new SequenceType(new IntType());
    if (isset($options['min'])) {
      $type->setMin($options['min']);
      $this->assertEquals($options['min'], $type->getMin());
    }
    if (isset($options['max'])) {
      $type->setMax($options['max']);
      $this->assertEquals($options['max'], $type->getMax());
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
      [NULL, []],
      [TRUE, []],
      [(object)[], []],
      [[], []],
      ["", []],
      [0, []],
      [["0"], [0]],
      [["0.1", "0", "-1"], [0, 0, -1]],
      [["a", []], [0, 0]],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($data, $expected): void {
    $type = new SequenceType(new IntType());

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
