<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\AnyType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\AnyType.
 */
#[CoversClass(AnyType::class)]
#[UsesClass(Context::class)]
#[UsesClass(Validation::class)]
class AnyTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    return [
      [TRUE, NULL, FALSE, []],
      [TRUE, TRUE, FALSE, []],
      [TRUE, [], FALSE, []],
      [TRUE, (object) [], FALSE, []],
      [FALSE, NULL, TRUE, [new Error("Value cannot be null.", ["test"])]],
      [FALSE, TRUE, FALSE, []],
      [FALSE, [], FALSE, []],
      [FALSE, (object) [], FALSE, []],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($nullable, $value, $has_errors, $errors): void {
    $type = new AnyType($nullable);

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
      [FALSE, NULL, NULL],
      [FALSE, TRUE, TRUE],
      [FALSE, (object)[], (object)[]],
      [FALSE, [], []],
      [TRUE, NULL, NULL],
      [TRUE, TRUE, TRUE],
      [TRUE, (object)[], (object)[]],
      [TRUE, [], []],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($nullable, $data, $expected): void {
    $type = new AnyType($nullable);

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
