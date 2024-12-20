<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a null type.
 */
class NullType implements TypeInterface {
  use TypeMetadataTrait;

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!is_null($value)) {
      $validation->add("Value is not null.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): mixed {
    return NULL;
  }

}