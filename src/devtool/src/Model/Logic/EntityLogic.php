<?php

namespace Swoft\Devtool\Model\Logic;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Db\Types;
use Swoft\Devtool\FileGenerator;
use Swoft\Devtool\Model\Data\SchemaData;

/**
 * EntityLogic
 * @Bean()
 */
class EntityLogic
{
    /**
     * @var SchemaData
     * @Inject()
     */
    private $schemaData;

    /**
     * @param array $params
     */
    public function generate(array $params)
    {
        list($db, $inc, $exc, $path, $driver, $tablePrefix, $fieldPrefix, $tplFile, $tplDir) = $params;
        $tableSchemas = $this->schemaData->getSchemaTableData($driver, $db, $inc, $exc, $tablePrefix);
        foreach ($tableSchemas as $tableSchema) {
            $this->generateClass($driver, $db, $tableSchema, $fieldPrefix, $path, $tplFile, $tplDir);
        }
    }

    /**
     * @param string $driver
     * @param string $db
     * @param array  $tableSchema
     * @param string $fieldPrefix
     * @param string $path
     * @param string $tplFile
     * @param string $tplDir
     */
    private function generateClass(string $driver, string $db, array $tableSchema, string $fieldPrefix, string $path, string $tplFile, string $tplDir)
    {
        $mappingClass = $tableSchema['mapping'];
        $config       = [
            'tplFilename' => $tplFile,
            'tplDir'      => $tplDir,
            'className'   => $mappingClass,
        ];

        $file = alias($path);
        $file .= sprintf('/%s.php', $mappingClass);

        $columnSchemas = $this->schemaData->getSchemaColumnsData($driver, $db, $tableSchema['name'], $fieldPrefix);

        $genSetters    = [];
        $genGetters    = [];
        $genProperties = [];
        $useRequired   = false;
        foreach ($columnSchemas as $columnSchema) {
            list($propertyCode, $required) = $this->generateProperties($columnSchema, $tplDir);
            $genProperties[] = $propertyCode;
            if (!empty($required) && !$useRequired) {
                $useRequired = true;
            }

            $genSetters[] = $this->generateSetters($columnSchema, $tplDir);
            $genGetters[] = $this->generateGetters($columnSchema, $tplDir);
        }

        $setterStr   = implode("\n", $genSetters);
        $getterStr   = implode("\n", $genGetters);
        $propertyStr = implode("\n", $genProperties);
        $methodStr   = sprintf("%s\n\n%s", $setterStr, $getterStr);
        $usespace    = (!$useRequired) ? '' : 'use Swoft\Db\Bean\Annotation\Required;';

        $data = [
            'properties' => $propertyStr,
            'methods'    => $methodStr,
            'tableName'  => $tableSchema['name'],
            'entityName' => $mappingClass,
            'namespace'  => 'App\\Models\\Entity',
            'usespace'   => $usespace,
        ];
        $gen  = new FileGenerator($config);
        $gen->renderas($file, $data);
    }

    /**
     * @param array  $colSchema
     * @param string $tplDir
     *
     * @return array
     */
    private function generateProperties(array $colSchema, string $tplDir): array
    {
        $entityConfig = [
            'tplFilename' => 'property',
            'tplDir'      => $tplDir,
        ];

        // id
        $id = !empty($colSchema['key']) ? '* @Id()' : '';

        // required
        $isRequired = $colSchema['nullable'] === 'NO' && $colSchema['default'] === null;
        $required   = !empty($colSchema['key']) ? false : $isRequired;
        $required   = ($required == true) ? '* @Required()' : '';

        // default
        $default = $this->transferDefaultType($colSchema['type'], $colSchema['key'], $colSchema['default']);
        $default = ($default !== null) ? sprintf(', default=%s', $default) : '';

        $data         = [
            'type'         => $colSchema['phpType'],
            'propertyName' => $colSchema['mappingVar'],
            'column'       => $colSchema['name'],
            'columnType'   => $colSchema['mappingType'],
            'default'      => $default,
            'required'     => $required,
            'id'           => $id,
        ];
        $gen          = new FileGenerator($entityConfig);
        $propertyCode = $gen->render($data);

        return [$propertyCode, $required];
    }

    /**
     * @param array  $colSchema
     * @param string $tplDir
     *
     * @return string
     */
    private function generateGetters(array $colSchema, string $tplDir): string
    {
        $getterName = sprintf('get%s', ucfirst($colSchema['mappingName']));

        $config = [
            'tplFilename' => 'getter',
            'tplDir'      => $tplDir,
        ];
        $data   = [
            'returnType' => $colSchema['phpType'],
            'methodName' => sprintf('get%s', $getterName),
            'property'   => $colSchema['mappingName'],
        ];

        $gen = new FileGenerator($config);

        return $gen->render($data);
    }

    /**
     * @param array  $colSchema
     * @param string $tplDir
     *
     * @return string
     */
    private function generateSetters(array $colSchema, string $tplDir): string
    {
        $setterName = sprintf('set%s', ucfirst($colSchema['mappingName']));

        $config = [
            'tplFilename' => 'setter',
            'tplDir'      => $tplDir,
        ];

        $data = [
            'type'       => $colSchema['phpType'],
            'methodName' => $setterName,
            'paramName'  => $colSchema['mappingVar'],
            'property'   => $colSchema['mappingName'],
        ];

        $gen = new FileGenerator($config);

        return $gen->render($data);
    }


    /**
     * @param string $type
     * @param string $primaryKey
     * @param mixed  $default
     *
     * @return bool|float|int|null|string
     */
    private function transferDefaultType(string $type, string $primaryKey, $default)
    {
        if (!empty($primaryKey)) {
            return null;
        }

        if ($default === null) {
            return null;
        }

        $default = trim($default);
        switch ($type) {
            case Types::INT:
            case Types::NUMBER:
                $default = (int)$default;
                break;
            case Types::BOOL:
                $default = (bool)$default;
                break;
            case Types::FLOAT:
                $default = (float)$default;
                break;
            default:
                $default = sprintf('"%s"', $default);
                break;
        }

        return $default;
    }
}