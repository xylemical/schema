<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\ScalarTypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a type that is defined by a regex pattern.
 */
class RegexType implements ScalarTypeInterface {

  use TypeMetadataTrait;

  /**
   * The regex pattern to validate against.
   *
   * @var string
   */
  protected string $pattern;

  /**
   * Constructs a RegexType.
   *
   * @param string $pattern
   *   The regex pattern.
   */
  public function __construct(string $pattern) {
    $this->pattern = $pattern;
    set_error_handler(function() {}, E_WARNING);
    $valid_pattern = preg_match($pattern, "") !== FALSE;
    restore_error_handler();
    if (!$valid_pattern) {
      throw new \InvalidArgumentException("Invalid regex pattern");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!is_string($value)) {
      $validation->add("Value needs to be a string.", $context);
      return;
    }

    if (!preg_match($this->pattern, $value)) {
      $validation->add("Value does not match required pattern.", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): string {
    if (is_object($value) && !method_exists($value, '__toString')) {
      return "";
    }
    elseif (is_array($value)) {
      return "";
    }
    return (string) $value;
  }

  /**
   * {@inheritdoc}
   */
  public function isConstant(): bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstant(): mixed {
    return NULL;
  }

}
