<?php

namespace Xylemical\Schema;

/**
 * Provides metadata support for a type.
 */
trait TypeMetadataTrait {

  /**
   * Get the associated metadata.
   *
   * @var \Xylemical\Schema\MetadataInterface|null
   */
  protected ?MetadataInterface $metadata = NULL;

  /**
   * Get the metadata associated with the type.
   *
   * @return \Xylemical\Schema\MetadataInterface|null
   *   The metadata.
   */
  public function getMeta(): ?MetadataInterface {
    return $this->metadata;
  }

  /**
   * Set the metadata associated with the type.
   *
   * @param \Xylemical\Schema\MetadataInterface $metadata
   *   The metadata.
   */
  public function setMeta(MetadataInterface $metadata): void {
    $this->metadata = $metadata;
  }

}