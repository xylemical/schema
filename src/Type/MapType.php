<?php

namespace Xylemical\Schema\Type;

use Xylemical\Schema\TypeInterface;
use Xylemical\Schema\TypeMetadataTrait;
use Xylemical\Schema\Validation\Context;
use Xylemical\Schema\Validation\ContextInterface;
use Xylemical\Schema\Validation\Validation;
use Xylemical\Schema\Validation\ValidationInterface;

/**
 * Provides a map type.
 */
class MapType implements TypeInterface {

  use TypeMetadataTrait;

  /**
   * The map entries
   *
   * @var \Xylemical\Schema\Type\MapTypeEntry[]
   */
  protected array $map = [];

  /**
   * Allows unknown keys.
   *
   * @var bool
   */
  protected bool $allowsUnknown = FALSE;

  /**
   * Checks if the mapping allows unknown keys.
   *
   * @return bool
   *   The result.
   */
  public function allowsUnknown(): bool {
    return $this->allowsUnknown;
  }

  /**
   * Sets flag allowing unknowns.
   *
   * @param bool $allowsUnknown
   *   The flag.
   *
   * @return $this
   */
  public function setAllowsUnknown(bool $allowsUnknown): self {
    $this->allowsUnknown = $allowsUnknown;
    return $this;
  }

  /**
   * Adds a key/value to the map.
   *
   * @param \Xylemical\Schema\Type\MapTypeEntry $entry
   *   The map entry.
   *
   * @return $this
   */
  public function add(MapTypeEntry $entry): self {
    $this->map[] = $entry;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, ValidationInterface $validation, ContextInterface $context): void {
    if (!is_array($value) && !is_object($value)) {
      $validation->add("Value is not a map.", $context);
      return;
    }

    $value = (array) $value;
    [$mapped, $unmapped, $unknown] = $this->matchKeys($value);
    foreach ($mapped as $key => $entry) {
      $context->push($key);
      $entry->matchesValue($value[$key], $validation, $context);
      $context->pop();
    }

    // Validate the unmapped entries.
    foreach ($unmapped as $entry) {
      if (!$entry->isOptional()) {
        $keyType = $entry->getKey();
        $key = $keyType->isConstant() ? $keyType->getConstant() : NULL;
        $validation->add("Missing required key ({$key}).", $context);
      }
    }

    // Validate against unknown keys.
    if (!$this->allowsUnknown() && count($unknown) > 0) {
      $keys = implode(', ', $unknown);
      $validation->add("Value contains unknown keys ({$keys}).", $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cast(mixed $value): array {
    $results = [];

    $value = (array) $value;
    [$mapped, $unmapped, $unknown] = $this->matchKeys($value);
    foreach ($mapped as $key => $entry) {
      $results[$key] = $entry->getValue()->cast($value[$key]);
    }

    foreach ($unmapped as $entry) {
      if (!$entry->isOptional() || !is_null($entry->getDefault())) {
        $keyType = $entry->getKey();
        $key = $keyType->isConstant() ? $keyType->getConstant() : NULL;
        if (!is_null($key)) {
          $results[$key] = $entry->getValue()->cast($entry->getDefault());
        }
      }
    }

    foreach ($unknown as $key) {
      $results[$key] = $value[$key];
    }

    return $results;
  }

  /**
   * Matches the key types to value keys.
   *
   * @param array $value
   *   The value.
   *
   * @return array<\Xylemical\Schema\Type\MapTypeEntry[], \Xylemical\Schema\Type\MapTypeEntry[],
   *   array> The mappings that match, that don't match and unmapped entries.
   */
  protected function matchKeys(array $value): array {
    $context = new Context();

    $mapped_entries = [];
    $unmapped_entries = [];

    $mapped = $value;

    // Map key types to value entry.
    foreach ($this->map as $entry) {
      $validation = new Validation();
      foreach (array_keys($mapped) as $key) {
        if ($entry->matchesKey($key, $validation, $context)) {
          $mapped_entries[$key] = $entry;
          unset($mapped[$key]);
          break;
        }
      }

      if (!in_array($entry, $mapped_entries)) {
        $unmapped_entries[] = $entry;
      }
    }

    return [$mapped_entries, $unmapped_entries, array_keys($mapped)];
  }

}
