<?php
class Collection
{
    private $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function first()
    {
        return reset($this->elements);
    }

    protected function createFrom(array $elements)
    {
        return new static($elements);
    }

    public function last()
    {
        return end($this->elements);
    }

    public function key()
    {
        return key($this->elements);
    }

    public function next()
    {
        return next($this->elements);
    }

    public function current()
    {
        return current($this->elements);
    }

    public function remove($key)
    {
        if (
            !isset($this->elements[$key]) &&
            !array_key_exists($key, $this->elements)
        ) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    public function removeElement($element): bool
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    public function containsKey($key): bool
    {
        return isset($this->elements[$key]) ||
            array_key_exists($key, $this->elements);
    }

    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function exists(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    public function get($key)
    {
        return $this->elements[$key] ?? null;
    }

    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    public function getValues(): array
    {
        return array_values($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function set($key, $value): void
    {
        $this->elements[$key] = $value;
    }

    public function add($element): bool
    {
        $this->elements[] = $element;

        return true;
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function map(Closure $func): Collection
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    public function filter(Closure $p): Collection
    {
        return $this->createFrom(
            array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH)
        );
    }

    public function forAll(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function partition(Closure $p): array
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    public function __toString(): string
    {
        return self::class . '@' . spl_object_hash($this);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function slice(int $offset, ?int $length = null): array
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    
}
?>
