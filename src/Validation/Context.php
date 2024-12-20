<?php

namespace Xylemical\Schema\Validation;

/**
 * A basic implementation of ContextInterface.
 */
class Context implements ContextInterface {

  /**
   * The context stack.
   *
   * @var string[]
   */
  protected array $stack = [];

  /**
   * Constructs a Context.
   *
   * @param string[] $stack
   *   The existing context.
   */
  public function __construct(array $stack = []) {
    foreach ($stack as $item) {
      assert(is_string($item));
      $this->stack[] = $item;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function push(string $path): void {
    $this->stack[] = $path;
  }

  /**
   * {@inheritdoc}
   */
  public function pop(): string {
    return (string) array_pop($this->stack);
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrent(): array {
    return $this->stack;
  }

  /**
   * {@inheritdoc}
   */
  public function getPath(string $separator = '.'): string {
    return implode($separator, $this->stack);
  }

}