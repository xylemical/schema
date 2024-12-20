<?php

namespace Xylemical\Schema\Validation;

/**
 * Define the validation behaviour.
 */
interface ValidationInterface {

  /**
   * Add an error using context.
   *
   * @param string $message
   *   The error message.
   * @param \Xylemical\Schema\Validation\ContextInterface $context
   *   The context of the error.
   */
  public function add(string $message, ContextInterface $context): void;

  /**
   * Check if there were errors registered.
   *
   * @return bool
   *   The result.
   */
  public function hasErrors(): bool;

  /**
   * Get a list of the errors.
   *
   * @return \Xylemical\Schema\Validation\ErrorInterface[]
   *   The errors.
   */
  public function getErrors(): array;

  /**
   * Merges validation errors from another validation into this one.
   *
   * @param \Xylemical\Schema\ValidationInterface $errors
   *   The other errors.
   */
  public function mergeErrors(ValidationInterface $errors): void;

  /**
   * Converts the errors into JSON.
   *
   * @return string
   *   The JSON formatted errors.
   */
  public function toJson(): string;

}