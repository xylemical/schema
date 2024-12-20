<?php

namespace Xylemical\Schema;

use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Defines the type validation.
 */
interface TypeInterface {

  /**
   * Get the metadata associated with the type.
   *
   * @return \Xylemical\Schema\MetadataInterface|null
   *   The metadata.
   */
  public function getMeta(): ?MetadataInterface;

  /**
   * Set the metadata associated with the type.
   *
   * @param \Xylemical\Schema\MetadataInterface $metadata
   *   The metadata.
   */
  public function setMeta(MetadataInterface $metadata): void;

  /**
   * Validates a value against the type definition.
   *
   * @param mixed $value
   *   The input value.
   * @param \Xylemical\Schema\Validation\ValidationInterface $validation
   *   The validation handler.
   * @param \Xylemical\Schema\Validation\ContextInterface $context
   *   The validation context.
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void;

  /**
   * Casts input type to type.
   *
   * @param mixed $value
   *   The input value.
   *
   * @return mixed
   *   The type value.
   */
  public function cast(mixed $value): mixed;

}