<?php

namespace Xylemical\Schema\Validation;

/**
 * A generic implementation of ValidationInterface.
 */
class Validation implements ValidationInterface {

  /**
   * The validation errors.
   *
   * @var \Xylemical\Schema\Validation\Error[]
   */
  protected array $errors = [];

  /**
   * {@inheritdoc}
   */
  public function add(string $message, ContextInterface $context): void {
    $this->errors[] = new Error($message, $context->getCurrent());
  }

  /**
   * {@inheritdoc}
   */
  public function hasErrors(): bool {
    return count($this->errors) > 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors(): array {
    return $this->errors;
  }

  /**
   * {@inheritdoc}
   */
  public function mergeErrors(ValidationInterface $errors): void {
    $this->errors = array_merge($this->errors, $errors->getErrors());
  }

  /**
   * {@inheritdoc}
   */
  public function toJson(): string {
    $results = [];
    foreach ($this->errors as $error) {
      $results[implode('.', $error->getPath())] = $error->getMessage();
    }
    return json_encode($results);
  }

}
