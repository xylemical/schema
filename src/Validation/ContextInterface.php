<?php

namespace Xylemical\Schema\Validation;

/**
 * Define the schema validation context.
 */
interface ContextInterface {

  /**
   * Push to the context path.
   *
   * @param string $path
   *   The path.
   */
  public function push(string $path): void;

  /**
   * Pop from the context path.
   *
   * @return string
   *   The last path of the context.
   */
  public function pop(): string;

  /**
   * Get the current context path.
   *
   * @return string[]
   *   The context path.
   */
  public function getCurrent(): array;

  /**
   * Get the context path using a separator.
   *
   * @param string $separator
   *   The separator.
   *
   * @return string
   *   The fully qualified context path.
   */
  public function getPath(string $separator): string;

}
