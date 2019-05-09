<?php declare(strict_types=1);


namespace Swoft\I18n;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\I18n\Exception\I18nException;
use Swoft\Log\Debug;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\DirectoryHelper;

/**
 * Class I18n
 *
 * @since 2.0
 *
 * @Bean(name="i18n")
 */
class I18n
{
    /**
     * Default lang
     */
    public const DEFAULT_LANG = 'en';

    /**
     * Source languages
     *
     * @var string
     */
    protected $resourcePath = '@resource/language/';

    /**
     * All loaded languages
     *
     * @var []string
     */
    protected $languages = [];

    /**
     * Translation messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * @var string
     */
    protected $defualtCategory = 'default';

    /**
     * @var string
     */
    protected $defaultLanguage = self::DEFAULT_LANG;

    /**
     * @throws I18nException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function init()
    {
        $sourcePath = \Swoft::getAlias($this->resourcePath);

        if (!$sourcePath || !file_exists($sourcePath)) {
            Debug::log(
                \sprintf('Resource i18n(%s) is not exist!', $sourcePath)
            );
            return;
        }
        if (!\is_readable($sourcePath)) {
            throw new I18nException(
                sprintf(' I18n resources(%s) is not readable', $sourcePath)
            );
        }

        $this->loadLanguages($sourcePath);
    }

    /**
     * @param string $sourcePath
     *
     * @return void
     */
    protected function loadLanguages(string $sourcePath)
    {
        $languages = [];
        $files     = DirectoryHelper::recursiveIterator($sourcePath);

        /* @var \SplFileInfo $splFileInfo */
        foreach ($files as $splFileInfo) {
            $file = $splFileInfo->__toString();
            if (\pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            $messages = str_replace([$sourcePath, '.php'], '', $file);
            list($language, $category) = explode('/', $messages);

            $languages[$language]                 = 1;
            $this->messages[$language][$category] = require $file;
        }

        $this->languages = \array_keys($languages);
    }

    /**
     * Translate
     *
     * @param string $key "category.key" or "locale.category.key"
     * @param array  $params
     * @param string $locale
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function translate(string $key, array $params, string $locale = self::DEFAULT_LANG): string
    {
        $realKey = $this->getRealKey($key, $locale);
        $message = ArrayHelper::get($this->messages, $realKey);
        // Not exist, return key
        if (!\is_string($message)) {
            return $key;
        }

        // Not params
        if (!$params) {
            return $message;
        }

        return $this->formatMessage($message, $params);
    }

    /**
     * @param string $key
     * @param string $locale
     *
     * @return array
     */
    public function get(string $key, string $locale = self::DEFAULT_LANG): array
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
     * @param string $key
     * @param string $locale
     *
     * @return string
     */
    private function getRealKey(string $key, string $locale): string
    {
        if (\strpos($key, '.') === false) {
            $key = \implode([$this->defualtCategory, $key], '.');
        }

        return implode('.', [$locale, $key]);
    }

    /**
     * Format message
     *
     * @param string $message
     * @param array  $params
     *
     * @return string
     */
    private function formatMessage(string $message, array $params): string
    {
        $search  = [];
        $replace = [];

        foreach ($params as $name => $value) {
            $search[]  = \sprintf('{%s}', $name);
            $replace[] = $value;
        }

        return \str_replace($search, $replace, $message);
    }
}