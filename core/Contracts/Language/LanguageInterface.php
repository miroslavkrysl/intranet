<?php


namespace Core\Contracts\Language;


/**
 * Interface for language handling.
 */
interface LanguageInterface
{
    /**
     * Get the translation.
     * @param string $key
     * @param string|null $locale
     * @param string|null $fallback
     * @return string
     */
    public function get(string $key, string $locale = null, string $fallback = null): string;

    /**
     * Determine if the translation exists.
     * @param string $key
     * @param string $locale
     * @return bool
     */
    public function has(string $key, string $locale): bool;

    /**
     * Get or set the default locale.
     * @return string|null
     */
    public function locale(string $new = null);

    /**
     * Determine if the locale exists.
     * @param string $locale
     * @return bool
     */
    public function hasLocale(string $locale): bool;
}