<?php declare(strict_types=1);


namespace Swoft\Config\Parser;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Config;
use Swoft\Config\Exception\ConfigException;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\DirectoryHelper;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlParser
 *
 * @since 2.0
 *
 * @Bean(name="yamlParser")
 */
class YamlParser extends Parser
{
    /**
     * @param Config $config
     *
     * @return array
     * @throws ConfigException
     */
    public function parse(Config $config): array
    {
        if (!\class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new ConfigException('You must to composer require symfony/yaml');
        }

        $base     = $config->getBase();
        $path     = $config->getPath();
        $baseFile = sprintf('%s.%s', $base, Config::TYPE_YAML);

        $iterator = DirectoryHelper::iterator($path);

        $baseConfig  = [];
        $otherConfig = [];

        /* @var \SplFileInfo $splFileInfo */
        foreach ($iterator as $splFileInfo) {

            // Ingore other extension file
            $ext = $splFileInfo->getExtension();
            $ext = strtolower($ext);

            if ($ext != Config::TYPE_YAML) {
                continue;
            }

            $fileName = $splFileInfo->getFilename();
            $fileName = strtolower($fileName);
            $filePath = $splFileInfo->getPathname();

            // Base config
            if ($fileName == $baseFile) {
                $baseConfig = Yaml::parseFile($filePath);
                continue;
            }

            $dirKey = \str_replace([$path, '/'], ['', '.'], $splFileInfo->getPath());
            $dirKey = \ltrim($dirKey, '.');

            // Other config
            [$key] = explode('.', $fileName);
            if (!empty($dirKey)) {
                $key = sprintf('%s.%s', $dirKey, $key);
            }

            $data = Yaml::parseFile($filePath);
            if (empty($data)) {
                continue;
            }

            ArrayHelper::set($otherConfig, $key, $data);
        }

        return ArrayHelper::merge($baseConfig, $otherConfig);
    }
}