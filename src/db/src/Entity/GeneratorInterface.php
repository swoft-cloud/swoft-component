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

/**
 * 生成实体操作接口
 *
 * @uses      GeneratorInterface
 * @version   2017年11月06日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface GeneratorInterface
{
    /**
     * 执行入口
     *
     * @param Schema $schema schema对象
     *
     * @return void
     */
    public function execute(Schema $schema);

    /**
     * 获取当前db的所有表
     *
     * @return array
     */
    public function getSchemaTables(): array;

    /**
     * 获取当前表的所有字段信息
     *
     * @param string $table 表名
     *
     * @return array
     */
    public function getTableColumns(string $table): array;
}
