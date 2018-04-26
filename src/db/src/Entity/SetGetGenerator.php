<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Db\Entity;

use Swoft\Helper\StringHelper;

class SetGetGenerator
{
    /**
     * @var Schema $schema schema对象
     */
    private $schema;

    /**
     * @var string $folder 模板目录
     */
    public $folder = 'Stub';

    /**
     * @var string $modelStub ModelStub
     */
    private $modelStubFile = 'Model.stub';

    /**
     * @var string $PropertyStubFile PropertyStub
     */
    private $propertyStubFile = 'Property.stub';

    /**
     * @var string $setterStub SetterStub
     */
    private $setterStubFile = 'Setter.stub';

    /**
     * @var string $getterStub GetterStub
     */
    private $getterStubFile = 'Getter.stub';

    /**
     * @var string $propertyStub 需要替换property的内容
     */
    private $propertyStub = '';

    /**
     * @var string $setterStub 需要替换setter的内容
     */
    private $setterStub = '';

    /**
     * @var string $prefix 别名前缀
     */
    private $prefix = 'alias';

    /**
     * @var string $getterStub 需要替换的getter的内容
     */
    private $getterStub = '';

    public function __construct()
    {
        $this->folder = __DIR__ . '/' . $this->folder . '/';
    }

    /**
     * __invoke
     *
     * @param Schema $schema      schema对象
     * @param array  $uses        需要use的类
     * @param string $extends     实体基类
     * @param string $entity      实体
     * @param mixed  $entityName  实体中文名
     * @param string $entityClass 实体类
     * @param string $entityDate  实体生成日期
     * @param array  $fields      字段
     */
    public function __invoke(
        Schema $schema,
        array $uses,
        string $extends,
        string $entity,
        $entityName,
        string $entityClass,
        string $entityDate,
        array $fields
    )
    {
        $this->schema = $schema;
        $entityStub   = $this->generateModel();
        $usesContent  = '';
        foreach ($uses as $useClass) {
            $usesContent .= "use {$useClass};" . PHP_EOL;
        }

        $this->parseFields($fields);
        $namespace = preg_replace('/.+a(pp)(.*)(\/?)$/', 'A${1}${2}', alias('@entityPath'));
        $namespace = str_replace('/', '\\', $namespace);

        $entityFile = str_replace([
            '{{namespace}}',
            '{{uses}}',
            '{{extends}}',
            '{{entity}}',
            "{{entityName}}\n",
            '{{entityClass}}',
            '{{entityDate}}',
            '{{property}}',
            '{{setter}}',
            '{{getter}}',
        ], [
            $namespace,
            $usesContent,
            $extends,
            $entity,
            !empty($entityName) ? " * {$entityName}\n\n" : '',
            $entityClass,
            $entityDate,
            $this->propertyStub,
            $this->setterStub,
            $this->getterStub,
        ], $entityStub);

        file_put_contents(alias('@entityPath') . "/{$entityClass}.php", $entityFile);
    }

    /**
     * 开始解析字段信息
     *
     * @param array $fields 字段
     */
    private function parseFields(array $fields)
    {
        $propertyStub = $this->generateProperty();
        $setterStub   = $this->generateSetter();
        $getterStub   = $this->generateGetter();
        foreach ($fields as $fieldInfo) {
            $this->parseProperty($propertyStub, $fieldInfo);
            $this->parseSetter($setterStub, $fieldInfo);
            $this->parseGetter($getterStub, $fieldInfo);
        }
    }

    /**
     * 解析Property
     *
     * @param string $propertyStub 属性模板
     * @param array  $fieldInfo    字段信息
     *
     */
    private function parseProperty(string $propertyStub, array $fieldInfo)
    {
        $property      = $fieldInfo['name'];
        $aliasProperty = StringHelper::camel($property);
        $primaryKey    = $fieldInfo['key'] === 'PRI';
        $required      = $primaryKey ? false : ($fieldInfo['nullable'] === 'NO');
        $default       = $fieldInfo['default'] ?? '';
        $dbType        = $this->schema->dbSchema[$fieldInfo['type']] ?? '';
        $phpType       = $this->schema->phpSchema[$fieldInfo['type']] ?? 'mixed';
        $length        = $fieldInfo['length'];
        $columnType    = $fieldInfo['column_type'];
        $comment       = $fieldInfo['column_comment'];
        $isEnum        = strpos($columnType, 'enum') !== false;
        if ($isEnum) {
            preg_match_all("/enum\((.*?)\)/", $columnType, $matches);
            $enumParam = $matches[1][0];
            $enumParam = explode(',', str_replace('\'', '', $enumParam));
            // TODO $enumParam never use ?
        }

        //字段类型
        $dbType = !empty($dbType) ? $dbType : ($isEnum ? '"feature-enum"' : (\is_int($default) ? '"int"' : '"string"'));

        $default = $this->setDefault($dbType, $default, $primaryKey);

        $this->checkAliasProperty($aliasProperty);

        $formatComment = "     * @var {$phpType} \${$aliasProperty} {$comment}\n";

        $this->propertyStub .= PHP_EOL . str_replace([
                "{{comment}}\n",
                "{{@Id}}\n",
                '{{property}}',
                '{{aliasProperty}}',
                '{{type}}',
                '{{length}}',
                "{{@Required}}\n",
                '{{hasDefault}}',
            ], [
                $formatComment,
                $primaryKey ? "     * @Id()\n" : '',
                $property,
                $aliasProperty,
                $dbType,
                $length !== null ? ", length={$length}" : '',
                $required ? "     * @Required()\n" : '',
                $default === '\'\'' ? ';' : " = {$default};"
            ], $propertyStub);
    }

    /**
     * 解析Setter
     *
     * @param string $setterStub setter模板
     * @param array  $fieldInfo  字段信息
     *
     */
    private function parseSetter(string $setterStub, array $fieldInfo)
    {
        $property      = $fieldInfo['name'];
        $comment       = $fieldInfo['column_comment'];
        $aliasProperty = StringHelper::camel($property);
        $this->checkAliasProperty($aliasProperty);
        $function         = StringHelper::camel($aliasProperty);
        $function         = 'set' . ucfirst($function);
        $primaryKey       = $fieldInfo['key'] === 'PRI';
        $type             = $this->schema->phpSchema[$fieldInfo['type']] ?? 'mixed';
        $this->setterStub .= PHP_EOL . str_replace([
                "{{comment}}\n",
                '{{function}}',
                '{{attribute}}',
                '{{type}}',
                '{{hasReturnType}}',
            ], [
                !empty($comment) ? "     * {$comment}\n" : '',
                $function,
                $aliasProperty,
                $type !== 'mixed' ? "{$type} " : '',
                $primaryKey ? '' : ': self',
            ], $setterStub);
    }

    /**
     * 解析Getter
     *
     * @param string $getterStub getter模板
     * @param array  $fieldInfo  字段信息
     *
     */
    private function parseGetter(string $getterStub, array $fieldInfo)
    {
        $property      = $fieldInfo['name'];
        $comment       = $fieldInfo['column_comment'];
        $primaryKey    = $fieldInfo['key'] === 'PRI';
        $aliasProperty = StringHelper::camel($property);
        $this->checkAliasProperty($aliasProperty);
        $function   = StringHelper::camel($aliasProperty);
        $function   = 'get' . ucfirst($function);
        $default    = $fieldInfo['default'] ?? '';
        $dbType     = $this->schema->dbSchema[$fieldInfo['type']] ?? '';
        $returnType = $this->schema->phpSchema[$fieldInfo['type']] ?? 'mixed';

        //字段类型
        $dbType = !empty($dbType) ? $dbType : \is_int($default) ? '"int"' : '"string"';

        $default = $this->setDefault($dbType, $default, $primaryKey);

        $this->getterStub .= PHP_EOL . str_replace([
                "{{comment}}\n",
                '{{function}}',
                '{{attribute}}',
                '{{coReturnType}}',
                '{{returnType}}',
            ], [
                !empty($comment) ? "     * {$comment}\n" : '',
                $function,
                $aliasProperty,
                $returnType !== 'mixed' && !$primaryKey && $default !== '\'\'' ? $returnType : 'mixed',
                $returnType !== 'mixed' && !$primaryKey && $default !== '\'\'' ? ": {$returnType}" : '',
            ], $getterStub);
    }

    /**
     * 检查别名属性
     *
     * @param string $aliasProperty alias ref
     *
     * @return bool
     */
    private function checkAliasProperty(string &$aliasProperty)
    : bool
    {
        preg_match_all('/\w+/', $aliasProperty, $match);
        $aliasProperty = implode('', $match[0]);

        if (!preg_match('/^([A-z]|_]+)/', $aliasProperty)) {
            $aliasProperty = $this->prefix . $aliasProperty;
        }

        return true;
    }

    /**
     * 创建Model模板
     *
     * return string
     */
    private function generateModel()
    : string
    {
        return file_get_contents($this->folder . $this->modelStubFile);
    }

    /**
     * 创建Setter模版
     *
     * return string
     */
    private function generateSetter()
    : string
    {
        return file_get_contents($this->folder . $this->setterStubFile);
    }

    /**
     * 创建Getter模板
     *
     * @return string
     */
    private function generateGetter()
    : string
    {
        return file_get_contents($this->folder . $this->getterStubFile);
    }

    /**
     * 创建Property模板
     *
     * @return string
     */
    private function generateProperty()
    : string
    {
        return file_get_contents($this->folder . $this->propertyStubFile);
    }

    /**
     * 设置默认值
     *
     * @param string $dbType     数据库类型
     * @param mixed  $default    默认值
     * @param bool   $primaryKey 主键
     *
     * @return miexed
     */
    private function setDefault($dbType, $default, $primaryKey)
    {
        if ($primaryKey) {
            return '\'\'';
        }

        if (in_array(strtolower($default), ['\'\'', '""', 'null']) || empty($default)) {
            switch ($dbType) {
                case "Types::INT":
                case "Types::NUMBER":
                case "Types::BOOLEAN":
                    $default = '0';
                    break;
                case "Types::FLOAT":
                    $default = '0.0';
                    break;
                case "Types::DATETIME":
                    $default = '\'' . date('Y-m-d H:i:s') . '\'';
                    break;
                default:
                    $default = '\'\'';
                    break;
            }
        } else {
            $default = trim($default);
            switch ($dbType) {
                case "Types::INT":
                case "Types::NUMBER":
                case "Types::BOOLEAN":
                case "Types::FLOAT":
                    $default = $default;
                    break;
                default:
                    $default = json_encode($default);
                    break;
            }
        }

        return $default;
    }
}