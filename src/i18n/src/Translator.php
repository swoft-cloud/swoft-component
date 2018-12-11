<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\I18n;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Helper\ArrayHelper;

/**
 * @Bean
 */
class Translator
{
    /**
     * Source languages
     *
     * @var string
     * @Value(name="${config.translator.languageDir}", env="${TRANSLATOR_LANG_DIR}")
     */
    public $languageDir = '@resources/languages/';

    /**
     * All loaded languages
     *
     * @var []string
     */
    private $languages = [];

    /**
     * Translation messages
     *
     * @var array
     */
    private $messages = [];

    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @Value(name="${config.translator.defaultCategory}", env="${TRANSLATOR_DEFAULT_CATEGORY}")
     * @var string
     */
    private $defaultCategory = 'default';

    /**
     * @Value(name="${config.translator.defaultLanguage}", env="${TRANSLATOR_DEFAULT_LANG}")
     * @var string
     */
    private $defaultLanguage = 'en';

    /**
     * @return void
     * @throws \RuntimeException
     */
    public function init()
    {
        $sourcePath = \alias($this->languageDir);

        if (!$sourcePath || !\file_exists($sourcePath)) {
            return;
        }

        if (!\is_readable($sourcePath)) {
            throw new \RuntimeException(sprintf('%s dir is not readable', $sourcePath));
        }

        $this->loadLanguages($sourcePath);
    }

    /**
     * Translate
     *
     * @param string $key "category.key" or "locale.category.key"
     * @param array  $params
     * @param string $locale
     * @return string
     * @throws \InvalidArgumentException
     */
    public function translate(string $key, array $params, string $locale = null): string
    {
        $realKey = $this->getRealKey($key, $locale);

        $message = ArrayHelper::get($this->messages, $realKey);

        // not exist, return key
        if (!\is_string($message)) {
            return $key;
        }

        // no params
        if (!$params) {
            return $message;
        }

        return $this->formatMessage($message, $params);
    }

    /**
     * get message data by key
     * @return mixed
     */
    public function get(string $key, string $locale = null)
    {
        $realKey = $this->getRealKey($key, $locale);

        return ArrayHelper::get($this->messages, $realKey);
    }

    /**
     * get messages
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * get languages
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param string $sourcePath
     * @return void
     */
    protected function loadLanguages(string $sourcePath)
    {
        if ($this->loaded === false) {
            $languages = [];
            $iterator = new \RecursiveDirectoryIterator($sourcePath);
            $files = new \RecursiveIteratorIterator($iterator);

            /** @var \SplFileInfo $file */
            foreach ($files as $file) {
                // Only load php file
                // TODO add .mo .po support
                if (pathinfo((string)$file, PATHINFO_EXTENSION) !== 'php') {
                    continue;
                }

                $messages = str_replace([$sourcePath, '.php'], '', $file);
                list($language, $category) = explode('/', $messages);

                $languages[$language] = 1;
                $this->messages[$language][$category] = require $file;
            }

            $this->loaded = true;
            $this->languages = \array_keys($languages);
        }
    }

    /**
     * @param string      $key
     * @param string|null $locale
     *
     * @return string
     */
    private function getRealKey(string $key, string $locale = null): string
    {
        if (!$locale) {
            $locale = $this->defaultLanguage;
        }

        if (\strpos($key, '.') === false) {
            $key = implode([$this->defaultCategory, $key], '.');
        }

        return implode('.', [$locale, $key]);
    }

    /**
     * Format message
     *
     * @param string $message
     * @param array  $params
     * @return string
     */
    private function formatMessage(string $message, array $params): string
    {
        $params = \array_values($params);
        \array_unshift($params, $message);

        return \sprintf(...$params);
    }
}
