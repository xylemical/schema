<?php

namespace Xylemical\Schema\TestCase\Validation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Validation\Error;

/**
 * Test coverage for \Xylemical\Schema\Validation\Error.
 */
#[CoversClass(Error::class)]
class ErrorTest extends TestCase {

  /**
   * Test the base error class.
   */
  public function testError(): void {
    $error = new Error("test", ["path"]);
    $this->assertEquals(["path"], $error->getPath());
    $this->assertEquals("test", $error->getMessage());
  }

}
