<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\ScalarTypeInterface;
use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides handling of the map entries.
 */
class MapTypeEntry {

  /**
   * The key type.
   *
   * @var \Xylemical\Schema\ScalarTypeInterface
   */
  protected ScalarTypeInterface $key;

  /**
   * The value type.
   *
   * @var \Xylemical\Schema\TypeInterface
   */
  protected TypeInterface $value;

  /**
   * Determines whether the entry is optional or not.
   *
   * @var bool
   */
  protected bool $optional = FALSE;

  /**
   * The default value to use for the mapping.
   *
   * @var mixed
   */
  protected mixed $default = NULL;

  /**
   * Constructs a MapTypeEntry.
   *
   * @param \Xylemical\Schema\ScalarTypeInterface $key
   *   The key type.
   * @param \Xylemical\Schema\TypeInterface $value
   *   The value type.
   */
  public function __construct(ScalarTypeInterface $key, TypeInterface $value) {
    $this->key = $key;
    $this->value = $value;
  }

  /**
   * Get the key type.
   *
   * @return \Xylemical\Schema\ScalarTypeInterface
   *   The key type.
   */
  public function getKey(): ScalarTypeInterface {
    return $this->key;
  }

  /**
   * Set the key type.
   *
   * @param \Xylemical\Schema\ScalarTypeInterface $key
   *   The key type.
   *
   * @return $this
   */
  public function setKey(ScalarTypeInterface $key): self {
    $this->key = $key;
    return $this;
  }

  /**
   * Get the value type.
   *
   * @return \Xylemical\Schema\TypeInterface
   *   The value type.
   */
  public function getValue(): TypeInterface {
    return $this->value;
  }

  /**
   * Set the value type.
   *
   * @param \Xylemical\Schema\TypeInterface $value
   *   The value type.
   *
   * @return $this
   */
  public function setValue(TypeInterface $value): self {
    $this->value = $value;
    return $this;
  }

  /**
   * Check if the entry is optional.
   *
   * @return bool
   */
  public function isOptional(): bool {
    return $this->optional;
  }

  /**
   * Set the optional flag.
   *
   * @param bool $optional
   *   The optional flag.
   *
   * @return $this
   */
  public function setOptional(bool $optional): self {
    $this->optional = $optional;
    return $this;
  }

  /**
   * Get the default value for the entry.
   *
   * @return mixed
   *   The default value.
   */
  public function getDefault(): mixed {
    return $this->default;
  }

  /**
   * Set the default value.
   *
   * @param mixed $default
   *   The default value.
   *
   * @return $this
   */
  public function setDefault(mixed $default): self {
    $this->default = $this->value->cast($default);
    return $this;
  }

  /**
   * Check the key matches the key type.
   *
   * @param mixed $key
   *   The key.
   * @param \Xylemical\Schema\Validation\ValidationInterface $validation
   *   The validation.
   * @param \Xylemical\Schema\Validation\ContextInterface $context
   *   The context.
   *
   * @return bool
   *   The result.
   */
  public function matchesKey(mixed $key, ValidationInterface $validation, ContextInterface $context): bool {
    $this->key->validate($key, $validation, $context);
    return !$validation->hasErrors();
  }

  /**
   * Check the value matches the value type.
   *
   * @param mixed $value
   *   The value.
   * @param \Xylemical\Schema\Validation\ValidationInterface $validation
   *   The validation handler.
   * @param \Xylemical\Schema\Validation\ContextInterface $context
   *   The current context.
   *
   * @return bool
   *   The result.
   */
  public function matchesValue(mixed $value, ValidationInterface $validation, ContextInterface $context): bool {
    $this->value->validate($value, $validation, $context);
    return !$validation->hasErrors();
  }

}
