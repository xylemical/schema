<?php

namespace Xylemical\Schema\Type;

/**
 * Provides an integer type.
 */
class IntType extends NumericType {

  /**
   * {@inheritdoc}
   */
  protected function mod(mixed $value, mixed $step): int {
    return intval($value) % (int) $step;
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): int {
    if (is_object($value)) {
      return 0;
    }
    return $this->value ?: intval($value);
  }

}
