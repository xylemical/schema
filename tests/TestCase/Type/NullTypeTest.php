<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\NullType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\NullType.
 */
#[CoversClass(NullType::class)]
#[UsesClass(Context::class)]
#[UsesClass(Validation::class)]
class NullTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $error = new Error("Value is not null.", ["test"]);
    return [
      [NULL, FALSE, []],
      [TRUE, TRUE, [$error]],
      [[], TRUE, [$error]],
      [(object) [], TRUE, [$error]],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($value, $has_errors, $errors): void {
    $type = new NullType();

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
      [NULL, NULL],
      [TRUE, NULL],
      [(object)[], NULL],
      [[], NULL],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($data, $expected): void {
    $type = new NullType();

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
