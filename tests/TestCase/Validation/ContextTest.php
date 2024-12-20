<?php

namespace Xylemical\Schema\TestCase\Validation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Xylemical\Schema\Type\AnyType;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\Error;
use Xylemical\Schema\Validation\Validation;

/**
 * Test coverage for \Xylemical\Schema\Validation\Context.
 */
#[CoversClass(Context::class)]
class ContextTest extends TestCase {

  /**
   * Test the base context class.
   */
  public function testContext(): void {
    $context = new Context();
    $this->assertEquals([], $context->getCurrent());
    $this->assertEquals("", $context->getPath());

    $context = new Context(["test", "class"]);
    $this->assertEquals(["test", "class"], $context->getCurrent());
    $this->assertEquals('test.class', $context->getPath());
    $this->assertEquals('test][class', $context->getPath(']['));

    $path = $context->pop();
    $this->assertEquals('class', $path);
    $this->assertEquals(['test'], $context->getCurrent());
    $this->assertEquals('test', $context->getPath());

    $context->push($path);
    $this->assertEquals(["test", "class"], $context->getCurrent());
    $this->assertEquals('test.class', $context->getPath());
    $this->assertEquals('test][class', $context->getPath(']['));
  }

}
