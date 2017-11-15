<?php


namespace Core\Language;


use Core\Contracts\Language\LanguageInterface;
use Core\DotArray\DotArray;
use Core\Language\Exception\LanguageException;

/**
 * LanguageInterface implementation for handling language translations.
 */
class Language implements LanguageInterface
{
    /**
     * The language translations.
     * @var DotArray
     */
    private $translations;

    /**
     * The default locale.
     * @var string
     */
    private $locale;

    /**
     * The default fallback locale.
     * @var string
     */
    private $fallback;

    /**
     * Language constructor.
     */
    public function __construct(string $langDir, string $locale, string $fallback)
    {
        $this->translations = new DotArray($this->loadTranslationsFromDir($langDir));

        if (!$this->hasLocale($fallback)) {
            throw new LanguageException('The fallback locale ' . $fallback . ' does not exist.');
        }

        $this->locale = $locale;
        $this->fallback = $fallback;
    }

    /**
     * Load the translations from the directory. The directory contains locales subdirectories.
     * @param string $dir
     * @return array
     */
    private function loadTranslationsFromDir(string $dir): array
    {
        $result = [];
        $dirs = \glob($dir . '/*', \GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $locale = \basename($dir);
            $result[$locale] = $this->loadDirRecursively($dir);
        }

        return $result;
    }

    /**
     * Load the languages files recursively from directory and subdirectories.
     * The subdirectory name is used as a key.
     * @param string $dir
     * @return array
     */
    private function loadDirRecursively(string $dir): array
    {
        $result = [];
        $files = \glob($dir . '/*');

        foreach ($files as $file) {
            $key = \basename($file, '.php');

            if (\is_dir($file)) {
                $result[$key] = $this->loadDirRecursively($file);
            }
            else {
                $result[$key] = require($file);
            }
        }

        return $result;
    }

    /**
     * Get the translation.
     * @param string $key
     * @param array $replace
     * @param int|null $count
     * @param string|null $locale
     * @return string
     * @throws LanguageException
     */
    public function get(string $key, array $replace = [], int $count = null, string $locale = null): string
    {
        $translation = "";
        $locale = $locale ?: $this->locale;

        if ($this->has($key, $locale)) {
            $translation = $this->translations->get($locale . '.' . $key);
        }
        else if ($this->has($key, $this->fallback)) {
            $translation = $this->translations->get($this->fallback . '.' . $key);
        }
        else {
            throw new LanguageException('The translation for ' . $key .
                ' does not exist neither in the locale ' . $locale . ' nor in the fallback locale ' . $this->fallback);
        }

        if (\is_array($translation)) {
            if (\is_null($count)) {
                throw new LanguageException(\sprintf('In translation %s you must define count.'), $key);
            }

            $translation = $this->selectByCount($translation, $count);

            if (\is_null($translation)) {
                throw new LanguageException(\sprintf('The translation %s is not defined for count %d', $key, $count));
            }
        }

        return $this->replace($translation, $replace);
    }

    /**
     * Determine if the translation exists.
     * @param string $key
     * @param string $locale
     * @return bool
     */
    public function has(string $key, string $locale): bool
    {
        return $this->translations->has($locale . '.' . $key);
    }

    /**
     * Get or set the default locale.
     * @param string|null $new
     * @return null|string
     */
    public function locale(string $new = null)
    {
        if (\is_null($new)) {
            return $this->locale;
        }

        $this->locale = $new;
        return null;
    }

    /**
     * Determine if the locale exists.
     * @param string $locale
     * @return bool
     */
    public function hasLocale(string $locale): bool
    {
        return \array_key_exists($locale, $this->translations->get());
    }

    /**
     * Replace placeholders in form with replacements.
     * @param string $string
     * @param array $replace
     * @return mixed|string
     */
    private function replace(string $string, array $replace = [])
    {
        if (!$replace) {
            return $string;
        }

        foreach ($replace as $key => $value) {
            $string = str_replace(
                [':' . $key, ':' . \strtoupper($key), ':' . \ucfirst($key)],
                [$value, \strtoupper($value), \ucfirst($value)],
                $string
            );
        }

        return $string;
    }

    /**
     * Select translation from array by definitions in keys equaling the $count.
     * @param array $translation
     * @param int $count
     */
    private function selectByCount(array $translation, int $count)
    {
        foreach ($translation as $key => $string) {
            if (\preg_match('/^(?<a>[0-9]+)-(?<b>[0-9]+)$/', $key, $m) and
                $count >= $m['a'] and
                $count <= $m['b']) {
                return $string;
            }
            if (\preg_match('/^(?<a>[0-9]+)-\*$/', $key, $m) and
                $count >= $m['a']) {
                return $string;
            }
            if (\preg_match('/^\*-(?<a>[0-9]+)$/', $key, $m) and
                $count <= $m['a']) {
                return $string;
            }
            if (\preg_match('/^[0-9]+(\s*,\s*[0-9]+)*$/', $string)) {
                preg_match_all('/(?<a>[0-9]+)/', $string, $m);
                foreach ($m['a'] as $num) {
                    if ($num == $count) {
                        return $string;
                    }
                }
            }
        }
        return null;
    }
}