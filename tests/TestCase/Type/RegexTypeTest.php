<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\RegexType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Type\RegexType.
 */
#[CoversClass(RegexType::class)]
#[UsesClass(Context::class)]
#[UsesClass(Validation::class)]
class RegexTypeTest extends TestCase {

  /**
   * The data provider for testValidation().
   *
   * @return array[]
   *   The test data.
   */
  public static function providerValidation(): array {
    $string_error = new Error("Value needs to be a string.", ["test"]);
    return [
      ["/a/", NULL, TRUE, [$string_error]],
      ["/a/", TRUE, TRUE, [$string_error]],
      ["/a/", [], TRUE, [$string_error]],
      ["/a/", (object) [], TRUE, [$string_error]],
      ["/a/", "a", FALSE, []],
      [
        "/a/",
        "b",
        TRUE,
        [new Error("Value does not match required pattern.", ["test"])],
      ],
    ];
  }

  /**
   * Test the validation.
   */
  #[DataProvider('providerValidation')]
  public function testValidation($default, $value, $has_errors, $errors): void {
    $type = new RegexType($default);
    $this->assertFalse($type->isConstant());
    $this->assertEquals(NULL, $type->getConstant());

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
      [NULL, ""],
      [TRUE, "1"],
      [(object) [], ""],
      [[], ""],
      ["", ""],
      [0, "0"],
    ];
  }

  /**
   * Test the casting process.
   */
  #[DataProvider('providerCast')]
  public function testCast($data, $expected): void {
    $type = new RegexType("//");

    $result = $type->cast($data);
    $this->assertEquals($expected, $result);
  }

  /**
   * Tests the exception when constructing the RegexType.
   */
  public function testInvalidPattern(): void {
    $this->expectException(\InvalidArgumentException::class);
    new RegexType("");
  }

}
