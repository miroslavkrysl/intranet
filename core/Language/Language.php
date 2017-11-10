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
    public function loadDirRecursively(string $dir): array
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
     * @param string|null $locale
     * @param string|null $fallback
     * @return string
     * @throws LanguageException
     */
    public function get(string $key, string $locale = null, string $fallback = null): string
    {
        $locale = $locale ?: $this->locale;
        if ($this->has($key, $locale)) {
            return $this->translations->get($locale . '.' . $key);
        }

        $fallback = $fallback ?: $this->fallback;
        if ($this->has($key, $fallback)) {
            return $this->translations->get($fallback . '.' . $key);
        }

        throw new LanguageException('The translation for ' . $key .
            ' does not exist neither in the locale ' . $locale . ' nor in the fallback locale ' . $fallback);
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
}