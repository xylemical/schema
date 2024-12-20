<?php

namespace Xylemical\Schema\Validation;

/**
 * Define the error structure.
 */
interface ErrorInterface {

  /**
   * Get the context path for the error.
   *
   * @return string[]
   *   The context path.
   */
  public function getPath(): array;

  /**
   * Get the human-readable message for the error.
   *
   * @return string
   *   The message.
   */
  public function getMessage(): string;

}
