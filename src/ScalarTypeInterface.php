<?php

namespace Xylemical\Schema;

/**
 * Defines the scalar type structure used for being able to index a map.
 */
interface ScalarTypeInterface extends TypeInterface {

  /**
   * Check if the scalar type is a constant.
   *
   * @return bool
   *   The result.
   */
  public function isConstant(): bool;

  /**
   * Get the constant value the scalar type represents.
   *
   * @return mixed
   *   The value.
   */
  public function getConstant(): mixed;

}
