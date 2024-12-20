<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\FloatType;
use Xylemical\Schema\Type\NumericType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\FloatType.
 */
#[CoversClass(FloatType::class)]
#[CoversClass(NumericType::class)]
#[UsesClass(Context::class)]
#[UsesClass(Validation::class)]
class FloatTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    return [
      [NULL, NULL, TRUE, [new Error("Value is not a number.", ["test"])]],
      [NULL, '0', FALSE, []],
      [NULL, 0, FALSE, []],
      [['min' => 0], -1, TRUE, [new Error("Value less than the minimum allowed value.", ["test"])]],
      [['min' => 0], 0, FALSE, []],
      [['min' => 0], 1, FALSE, []],
      [['max' => 0], -1, FALSE, []],
      [['max' => 0], 0, FALSE, []],
      [['max' => 0], 1, TRUE, [new Error("Value more than the maximum allowed value.", ["test"])]],
      [['min' => 0, 'max' => 0], -1, TRUE, [new Error("Value less than the minimum allowed value.", ["test"])]],
      [['min' => 0, 'max' => 0], 0, FALSE, []],
      [['min' => 0, 'max' => 0], 1, TRUE, [new Error("Value more than the maximum allowed value.", ["test"])]],
      [['step' => 0.01], 0, FALSE, []],
      [['step' => 0.01], 0.01, FALSE, []],
      [['step' => 0.01], 0.015, TRUE, [new Error("Value is not a multiple of the step value.", ["test"])]],
      [['value' => 0.01], 0.01, FALSE, []],
      [['value' => 0.01], 0.015, TRUE, [new Error("Value does not match.", ["test"])]]
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($options, $value, $has_errors, $errors): void {
    $type = new FloatType($options['value'] ?? NULL);
    if (isset($options['min'])) {
      $type->setMin($options['min']);
      $this->assertEquals($options['min'], $type->getMin());
    }
    if (isset($options['max'])) {
      $type->setMax($options['max']);
      $this->assertEquals($options['max'], $type->getMax());
    }
    if (isset($options['step'])) {
      $type->setStep($options['step']);
      $this->assertEquals($options['step'], $type->getStep());
    }
    $constant = isset($options['value']);
    $this->assertEquals($constant, $type->isConstant());
    $this->assertEquals($options['value'] ?? NULL, $type->getConstant());

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
      [[], NULL, 0],
      [['value' => 0.1], 1.1, 0.1],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($options, $data, $expected): void {
    $type = new FloatType($options['value'] ?? NULL);

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
