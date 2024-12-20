<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a sequence type.
 */
class SequenceType implements TypeInterface {

  use TypeMetadataTrait;

  /**
   * The expected type within the sequence.
   *
   * @var \Xylemical\Schema\TypeInterface
   */
  protected TypeInterface $type;

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
   * Constructs a SequenceType.
   *
   * @param \Xylemical\Schema\TypeInterface $type
   *   The type of values of the sequence.
   */
  public function __construct(TypeInterface $type) {
    $this->type = $type;
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
   *   The minimum length or NULL.
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
   *   The maximum length or NULL.
   */
  public function getMax(): ?int {
    return $this->max;
  }

  /**
   * Set the maximum length constraint.
   *
   * @param int|null $max
   *   The maximum length or NULL.
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
    if (!is_array($value) || !$this->isSequence($value)) {
      $validation->add("Value is not a sequence.", $context);
      return;
    }

    $length = count($value);
    if (!is_null($this->min) && $length < $this->min) {
      $validation->add("Value does not contain enough items.", $context);
    }

    if (!is_null($this->max) && $length > $this->max) {
      $validation->add("Value contains too many items.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): array {
    $result = [];
    if (is_array($value)) {
      foreach ($value as $item) {
        $result[] = $this->type->cast($item);
      }
    }
    return $result;
  }

  /**
   * Check the array represents a sequence.
   *
   * @param array $value
   *   The value.
   *
   * @return bool
   *   The result.
   */
  protected function isSequence(array $value): bool {
    $count = count($value);
    if ($count > 0) {
      $keys = array_keys($value);
      $expected_keys = range(0, count($value) - 1);
      return count(array_diff($expected_keys, $keys)) === 0;
    }
    return TRUE;
  }

}