<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a type that covers any type.
 */
class AnyType implements TypeInterface {

  use TypeMetadataTrait;

  /**
   * The type can be null.
   *
   * @var bool
   */
  protected bool $nullable;

  /**
   * Constructs an AnyType.
   *
   * @param bool $nullable
   *   The nullable flag.
   */
  public function __construct(bool $nullable = TRUE) {
    $this->nullable = $nullable;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!$this->nullable && is_null($value)) {
      $validation->add("Value cannot be null.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): mixed {
    return $value;
  }

}