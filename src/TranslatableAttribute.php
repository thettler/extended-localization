<?php

declare(strict_types=1);

namespace Thettler\ExtendedLocalization;

use ArrayAccess;
use BackedEnum;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Stringable;
use Thettler\ExtendedLocalization\Casts\TranslatableAttributeCast;
use Thettler\ExtendedLocalization\Contracts\Language;
use Thettler\ExtendedLocalization\Exceptions\LanguageNotDefinedException;


/**
 * @template L of Language&BackedEnum
 */
final class TranslatableAttribute implements ArrayAccess, Arrayable, Stringable, Castable, \Iterator, Jsonable, \JsonSerializable, Renderable
{
    private $pointer = 0;

    /**
     * @param  class-string<L>  $languageEnum
     * @param  array<value-of<L>, mixed>  $translations
     */
    public function __construct(
        protected string $languageEnum,
        protected array $translations = []
    ) {
        if (!enum_exists($languageEnum) || !is_subclass_of($languageEnum, Language::class)) {
            throw new \InvalidArgumentException(
                "The given language enum does not exist or is not a subclass of ".Language::class
            );
        }

        $this->setTranslations($translations);
    }

    /**
     * @param  array<value-of<L>, mixed>  $translations
     */
    public static function new($translations = []): static
    {
        return app(static::class, $translations);
    }

    public function __invoke()
    {
        return $this->getTranslation();
    }

    public function __toString(): string
    {
        return (string)$this->getTranslation();
    }

    public function __get(string $language)
    {
        return $this->getTranslation($language);
    }

    public function __set(string $name, $value): void
    {
        $this->setTranslation($name, $value);
    }

    public function __unset(string $name): void
    {
        unset($this->translations[$name]);
    }

    public function doesTranslationExist(string|(BackedEnum&Language) $language): bool
    {
        $language = $this->parseLanguageCode($language);

        return isset($this->translations[$language->value]);
    }

    public function isTranslationEmpty(string|(BackedEnum&Language) $language): bool
    {
        $language = $this->parseLanguageCode($language);

        return empty($this->getTranslation($language));
    }

    public function setTranslation(string|(BackedEnum&Language) $language, mixed $value): static
    {
        $language = $this->parseLanguageCode($language);

        $this->translations[$language->value] = $value;

        return $this;
    }

    public function getTranslation(null|string|(BackedEnum&Language) $language = null): mixed
    {
        if (is_null($language)) {
            $language = app()->getLocale();
        }

        $language = $this->parseLanguageCode($language);

        return $this->translations[$language->value] ?? null;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->doesTranslationExist($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getTranslation($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setTranslation($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $language = $this->parseLanguageCode($offset);

        unset($this->translations[$language->value]);
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->translations);
    }

    protected function parseLanguageCode((BackedEnum&Language)|string $languageToParse): BackedEnum&Language
    {
        $language = $languageToParse instanceof Language ? $languageToParse : $this->languageEnum::tryFrom(
            $languageToParse
        );

        if (is_null($language)) {
            LanguageNotDefinedException::throw($languageToParse, $this->languageEnum);
        }

        if (!($language instanceof $this->languageEnum)) {
            $languageClass = $language::class;

            throw new \InvalidArgumentException(
                "The given language ({$languageClass}) does not match the language in the TranslatableAttribute ({$this->languageEnum})."
            );
        }
        return $language;
    }

    protected function setTranslations(array $translations): static
    {
        foreach ($translations as $locale => $translation) {
            $this->setTranslation($locale, $translation);
        }

        return $this;
    }

    public static function castUsing(array $arguments)
    {
        return TranslatableAttributeCast::class;
    }

    public function current(): mixed
    {
        return array_values($this->translations)[$this->pointer];
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function key(): mixed
    {
        return array_keys($this->translations)[$this->pointer];
    }

    public function valid(): bool
    {
        return $this->pointer < count($this->translations);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function toArray()
    {
        return $this->getTranslations();
    }

    public function render()
    {
        return $this->__toString();
    }

    public function jsonSerialize(): mixed
    {
        return $this->toJson();
    }
}
