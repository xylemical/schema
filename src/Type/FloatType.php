<?php

namespace Xylemical\Schema\Type;

/**
 * Provides a floating point number type.
 */
class FloatType extends NumericType {

  /**
   * {@inheritdoc}
   */
  protected function mod(mixed $value, mixed $step): float {
    return fmod($this->cast($value), (float) $step);
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): float {
    return $this->value ?: floatval($value);
  }

}