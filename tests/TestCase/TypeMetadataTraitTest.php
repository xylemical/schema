<?php

namespace Xylemical\Schema;

use PHPUnit\Framework\TestCase;

/**
 * Test the type metadata trait.
 */
class TypeMetadataTraitTest extends TestCase {

  /**
   * Tests the metadata trait behaviour.
   */
  public function testMetadata(): void {
    $trait = new class {
      use TypeMetadataTrait;
    };
    $this->assertNull($trait->getMeta());

    $metadata = new Metadata();
    $trait->setMeta($metadata);
    $this->assertEquals($metadata, $trait->getMeta());
  }

}
