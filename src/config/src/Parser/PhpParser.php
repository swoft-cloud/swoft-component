<?php declare(strict_types=1);

namespace Swoft\Config\Parser;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Config;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\DirectoryHelper;

/**
 * Class PhpParser
 *
 * @Bean("phpParser")
 */
class PhpParser extends Parser
{
    /**
     * Parse php files
     *
     * @param Config $config
     *
     * @return array
     */
    public function parse(Config $config): array
    {
        $base     = $config->getBase();
        $path     = $config->getPath();
        $baseFile = sprintf('%s.%s', $base, Config::TYPE_PHP);

        $iterator = DirectoryHelper::iterator($path);

        $baseConfig  = [];
        $otherConfig = [];

        /* @var \SplFileInfo $splFileInfo */
        foreach ($iterator as $splFileInfo) {

            // Ingore other extension file
            $ext = $splFileInfo->getExtension();
            $ext = strtolower($ext);

            if ($ext != Config::TYPE_PHP) {
                continue;
            }

            $fileName = $splFileInfo->getFilename();
            $fileName = strtolower($fileName);
            $filePath = $splFileInfo->getPathname();

            // Base config
            if ($fileName == $baseFile) {
                $baseConfig = require $filePath;
                continue;
            }

            $dirKey = \str_replace([$path, '/'], ['', '.'], $splFileInfo->getPath());
            $dirKey = \ltrim($dirKey, '.');

            // Other config
            [$key] = explode('.', $fileName);
            if (!empty($dirKey)) {
                $key = sprintf('%s.%s', $dirKey, $key);
            }

            $data = require $filePath;
            ArrayHelper::set($otherConfig, $key, $data);
        }

        return ArrayHelper::merge($baseConfig, $otherConfig);
    }
}