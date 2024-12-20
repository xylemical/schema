<?php

namespace Xylemical\Schema\TestCase\Validation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Validation\Validation.
 */
#[CoversClass(Validation::class)]
#[UsesClass(Error::class)]
class ValidationTest extends TestCase {

  /**
   * Test the base validation class.
   */
  public function testValidation(): void {
    $validation = new Validation();
    $this->assertFalse($validation->hasErrors());
    $this->assertEquals([], $validation->getErrors());

    $validation->add("test", new Context(["test", "path"]));
    $this->assertTrue($validation->hasErrors());
    $this->assertEquals([new Error("test", ["test", "path"])], $validation->getErrors());
    $this->assertEquals("{\"test.path\":\"test\"}", $validation->toJson());

    $merger = new Validation();
    $merger->add("test", new Context(["test"]));
    $validation->mergeErrors($merger);
    $this->assertTrue($validation->hasErrors());
    $this->assertEquals([new Error("test", ["test", "path"]), new Error("test", ["test"])], $validation->getErrors());
    $this->assertEquals("{\"test.path\":\"test\",\"test\":\"test\"}", $validation->toJson());
  }

}
