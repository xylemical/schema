<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\IntType;
use Xylemical\Schema\Type\StringType;
use Xylemical\Schema\Type\UnionType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\UnionType.
 */
#[CoversClass(UnionType::class)]
#[UsesClass(Context::class)]
#[UsesClass(IntType::class)]
#[UsesClass(StringType::class)]
#[UsesClass(Validation::class)]
class UnionTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $union_error = new Error("Value does not match any of the specified types.", ["test"]);
    return [
      [NULL, TRUE, [$union_error]],
      [TRUE, TRUE, [$union_error]],
      [(object) [], TRUE, [$union_error]],
      [["a" => 0], TRUE, [$union_error]],
      [0, FALSE, []],
      ["abc", FALSE, []],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($value, $has_errors, $errors): void {
    $type = new UnionType([new IntType(), new StringType()]);

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
      [(object) [], NULL],
      [[], NULL],
      ["", ""],
      [0, 0],
      ["0", 0],
      ["abc", "abc"],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($data, $expected): void {
    $type = new UnionType([new IntType(), new StringType()]);

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

}
