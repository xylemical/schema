<?php

namespace Xylemical\Schema\TestCase\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\XmlConfiguration\Validator;
use Xylemical\Schema\Type\IntType;
use Xylemical\Schema\Type\MapTypeEntry;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test the map type entries.
 */
#[CoversClass(MapTypeEntry::class)]
#[UsesClass(Context::class)]
#[UsesClass(IntType::class)]
#[UsesClass(Validator::class)]
class MapTypeEntryTest extends TestCase {

  /**
   * Tests the map entry behaviour.
   */
  public function testEntry(): void {
    $key_type = new IntType(10);
    $value_type = new IntType();

    $entry = new MapTypeEntry($key_type, $value_type);
    $this->assertEquals($key_type, $entry->getKey());
    $this->assertEquals($value_type, $entry->getValue());
    $this->assertNull($entry->getDefault());
    $this->assertFalse($entry->isOptional());

    $new_key_type = new IntType(20);
    $entry->setKey($new_key_type);
    $this->assertEquals($new_key_type, $entry->getKey());
    $this->assertNotEquals($key_type, $entry->getKey());

    $new_value_type = new IntType(100);
    $entry->setValue($new_value_type);
    $this->assertEquals($new_value_type, $entry->getValue());
    $this->assertNotEquals($value_type, $entry->getValue());

    $entry->setDefault(1);
    $this->assertEquals(100, $entry->getDefault());

    $entry->setValue($value_type);
    $entry->setDefault(1);
    $this->assertEquals(1, $entry->getDefault());

    $entry->setOptional(true);
    $this->assertTrue($entry->isOptional());
  }

  /**
   * Provides the test data for testMatchesKey.
   */
  public static function providerMatchesKey(): array {
    return [
      [0, TRUE, []],
      ['a', FALSE, [new Error("Value is not a number.", ["test"])]],
    ];
  }

  /**
   * Test validation matches.
   */
  #[DataProvider('providerMatchesKey')]
  public function testMatchesKey($value, $expected, $errors): void {
    $type = new MapTypeEntry(new IntType(), new IntType(10));

    $context = new Context(["test"]);
    $validation = new Validation();
    $result = $type->matchesKey($value, $validation, $context);
    $this->assertEquals($expected, $result);
    $this->assertEquals(!$expected, $validation->hasErrors());
    $this->assertEquals($errors, $validation->getErrors());
  }

  /**
   * Provides the test data for testMatchesValue.
   */
  public static function providerMatchesValue(): array {
    return [
      [10, TRUE, []],
      [0, FALSE, [new Error("Value does not match.", ["test"])]],
      ['a', FALSE, [new Error("Value is not a number.", ["test"])]],
    ];
  }

  /**
   * Test validation matches.
   */
  #[DataProvider('providerMatchesValue')]
  public function testMatchesValue($value, $expected, $errors): void {
    $type = new MapTypeEntry(new IntType(), new IntType(10));

    $context = new Context(["test"]);
    $validation = new Validation();
    $result = $type->matchesValue($value, $validation, $context);
    $this->assertEquals($expected, $result);
    $this->assertEquals(!$expected, $validation->hasErrors());
    $this->assertEquals($errors, $validation->getErrors());
  }
}
