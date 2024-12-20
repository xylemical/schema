<?php

namespace Xylemical\Schema\Validation;

/**
 * A generic implementation of the error interface.
 */
class Error implements ErrorInterface {

  /**
   * The error message.
   *
   * @var string
   */
  protected string $message;

  /**
   * The error path.
   *
   * @var string[]
   */
  protected array $path;

  /**
   * Constructs an Error.
   *
   * @param string $message
   *   The error message.
   * @param array $path
   *   The error path.
   */
  public function __construct(string $message, array $path) {
    $this->message = $message;
    $this->path = $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getPath(): array {
    return $this->path;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessage(): string {
    return $this->message;
  }

}
