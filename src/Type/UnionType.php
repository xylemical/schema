<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\Validation;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a union of types.
 */
class UnionType implements TypeInterface {

  use TypeMetadataTrait;

  /**
   * The possible types.
   *
   * @var \Xylemical\Schema\TypeInterface[]
   */
  protected array $types = [];

  /**
   * Constructs a UnionType.
   *
   * @param \Xylemical\Schema\TypeInterface[] $types
   *   The types.
   */
  public function __construct(array $types = []) {
    foreach ($types as $type) {
      assert($type instanceof TypeInterface);
      $this->types[] = $type;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!$this->getMatchedType($value)) {
      $validation->add("Value does not match any of the specified types.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): mixed {
    $type = $this->getMatchedType($value);
    return $type?->cast($value);
  }

  /**
   * Matches a type against the value.
   *
   * @param mixed $value
   *   The value.
   *
   * @return \Xylemical\Schema\TypeInterface|null
   *   The matched type or NULL.
   */
  public function getMatchedType(mixed $value): ?TypeInterface {
    $context = new Context();
    foreach ($this->types as $type) {
      $validation = new Validation();
      $type->validate($value, $validation, $context);
      if (!$validation->hasErrors()) {
        return $type;
      }
    }
    return NULL;
  }

}