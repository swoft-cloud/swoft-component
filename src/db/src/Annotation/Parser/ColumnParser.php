<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\EntityRegister;

/**
 * Class ColumnParser
 *
 * @AnnotationParser(Column::class)
 * @since 2.0
 */
class ColumnParser extends Parser
{
    /**
     * @param int    $type
     * @param Column $annotationObject
     *
     * @return array
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \ReflectionException
     */
    public function parse(int $type, $annotationObject): array
    {
        $type   = $this->getPropertyType();
        $name   = $annotationObject->getName();
        $prop   = $annotationObject->getProp();
        $hidden = $annotationObject->isHidden();
        $name   = empty($name) ? $this->propertyName : $name;
        $prop   = empty($prop) ? $this->propertyName : $prop;

        EntityRegister::registerColumn($this->className, $this->propertyName, $name, $prop, $hidden, $type);
        return [];
    }

    /**
     * Get property `@var` type
     *
     * @return string
     * @throws \ReflectionException
     */
    private function getPropertyType(): string
    {
        // Parse php document
        $reflectProperty = new \ReflectionProperty($this->className, $this->propertyName);
        $document        = $reflectProperty->getDocComment();

        if (!preg_match('/@var\s+([^\s]+)/', $document, $matches)) {
            return '';
        }

        $type = $matches[1] ?? '';
        $type = explode('|', $type);

        return trim($type[0]);
    }
}
