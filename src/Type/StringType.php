<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\ScalarTypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a generic string type.
 */
class StringType implements ScalarTypeInterface {

  use TypeMetadataTrait;

  /**
   * The value restriction for the string.
   *
   * @var string|null
   */
  protected ?string $value;

  /**
   * The minimum length constraint.
   *
   * @var int|null
   */
  protected ?int $min = NULL;

  /**
   * The maximum length constraint.
   *
   * @var int|null
   */
  protected ?int $max = NULL;

  /**
   * Constructs a StringType.
   *
   * @param string|null $value
   *   The specific string the type matches, or NULL.
   */
  public function __construct(?string $value = NULL) {
    $this->value = $value;
  }

  /**
   * Get the minimum length constraint.
   *
   * @return int|null
   *   The minimum length or NULL.
   */
  public function getMin(): ?int {
    return $this->min;
  }

  /**
   * Set the minimum length constraint.
   *
   * @param int|null $min
   *   The minimum length constraint or NULL.
   *
   * @return $this
   */
  public function setMin(?int $min): self {
    $this->min = $min;
    return $this;
  }

  /**
   * Get the maximum length constraint.
   *
   * @return int|null
   *   The maximum length constraint or NULL.
   */
  public function getMax(): ?int {
    return $this->max;
  }

  /**
   * Set the maximum length constraint.
   *
   * @param int|null $max
   *   The maximum length constraint or NULL.
   *
   * @return $this
   */
  public function setMax(?int $max): self {
    $this->max = $max;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!is_string($value)) {
      $validation->add("Value needs to be a string.", $context);
      return;
    }

    if (!is_null($this->value) && $value !== $this->value) {
      $validation->add("Value does not match required value.", $context);
    }

    if (!is_null($this->min) && strlen($value) < $this->min) {
      $validation->add("Value must be at least {$this->min} characters.", $context);
    }

    if (!is_null($this->max) && strlen($value) > $this->max) {
      $validation->add("Value cannot exceed {$this->max} characters.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): string {
    if (is_object($value) && !method_exists($value, '__toString')) {
      return "";
    }
    elseif (is_array($value)) {
      return "";
    }
    return $this->value ?: (string) $value;
  }

  /**
   * {@inheritdoc}
   */
  public function isConstant(): bool {
    return !is_null($this->value) && $this->value !== "";
  }

  /**
   * {@inheritdoc}
   */
  public function getConstant(): ?string {
    return $this->value;
  }

}
