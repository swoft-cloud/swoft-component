<?php declare(strict_types=1);


namespace Swoft\Db\Migration;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Migration
 *
 * @since 2.0
 *
 * @Bean(name="migration")
 */
class Migration
{
    /**
     * @var string
     */
    private $migrationPath = '@app/Migration';

    /**
     * @var string
     */
    private $namespace = 'App\\Migration';

    /**
     * @var string
     */
    private $templateDir = '@base/vendor/swoft/devtool/resource/template';

    /**
     * @var string
     */
    private $templateFile = 'migration';

    /**
     * @return string
     */
    public function getMigrationPath(): string
    {
        return $this->migrationPath;
    }

    /**
     * @param string $migrationPath
     */
    public function setMigrationPath(string $migrationPath): void
    {
        $this->migrationPath = $migrationPath;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getTemplateDir(): string
    {
        return $this->templateDir;
    }

    /**
     * @param string $templateDir
     */
    public function setTemplateDir(string $templateDir): void
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @return string
     */
    public function getTemplateFile(): string
    {
        return $this->templateFile;
    }

    /**
     * @param string $templateFile
     */
    public function setTemplateFile(string $templateFile): void
    {
        $this->templateFile = $templateFile;
    }
}