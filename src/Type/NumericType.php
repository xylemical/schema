<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\ScalarTypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a base class for numeric types.
 */
abstract class NumericType implements ScalarTypeInterface {

  use TypeMetadataTrait;

  /**
   * The value to match against.
   *
   * @var mixed
   */
  protected mixed $value = NULL;

  /**
   * The minimum constraint for a number.
   *
   * @var float|int|null
   */
  protected mixed $min = NULL;

  /**
   * The maximum constraint for a number.
   *
   * @var float|int|null
   */
  protected mixed $max = NULL;

  /**
   * The step constraint for a number.
   *
   * @var float|int|null
   */
  protected mixed $step = NULL;

  /**
   * Constructs a NumericType.
   *
   * @param mixed|NULL $value
   *   The value to match against.
   */
  public function __construct(mixed $value = NULL) {
    $this->value = !is_null($value) ? $this->cast($value) : NULL;
  }

  /**
   * Get the minimum value constraint.
   *
   * @return mixed
   *   The minimum.
   */
  public function getMin(): mixed {
    return $this->min;
  }

  /**
   * Set the minimum value constraint.
   *
   * @param mixed $min
   *   The minimum value.
   *
   * @return $this
   */
  public function setMin(mixed $min): self {
    $this->min = $min;
    return $this;
  }

  /**
   * Get the maximum value constraint.
   *
   * @return mixed
   *   The maximum value.
   */
  public function getMax(): mixed {
    return $this->max;
  }

  /**
   * Set the maximum value constraint.
   *
   * @param mixed $max
   *   The maximum value.
   *
   * @return $this
   */
  public function setMax(mixed $max): self {
    $this->max = $max;
    return $this;
  }

  /**
   * Get the value modulus constraint.
   *
   * @return mixed
   *   The modulus.
   */
  public function getStep(): mixed {
    return $this->step;
  }

  /**
   * Set the value modulus constraint.
   *
   * @param mixed $step
   *   The modulus.
   *
   * @return $this
   */
  public function setStep(mixed $step): self {
    $this->step = $step;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!is_numeric($value)) {
      $validation->add("Value is not a number.", $context);
      return;
    }

    if (!is_null($this->value) && abs($this->value - $value) > PHP_FLOAT_MIN) {
      $validation->add("Value does not match.", $context);
    }
    if (!is_null($this->min) && $value < $this->min) {
      $validation->add("Value less than the minimum allowed value.", $context);
    }
    if (!is_null($this->max) && $value > $this->max) {
      $validation->add("Value more than the maximum allowed value.", $context);
    }
    if (!is_null($this->step) && $this->mod($value, $this->step) > PHP_FLOAT_EPSILON) {
      $validation->add("Value is not a multiple of the step value.", $context);
    }
  }

  /**
   * Perform a modulus against a value.
   *
   * @param mixed $value
   *   The value.
   * @param mixed $step
   *   The step.
   *
   * @return mixed
   *   The modulo.
   */
  abstract protected function mod(mixed $value, mixed $step): mixed;

  /**
   * {@inheritdoc}
   */
  public function isConstant(): bool {
    return !is_null($this->value);
  }

  /**
   * {@inheritdoc}
   */
  public function getConstant(): mixed {
    return $this->value;
  }

}