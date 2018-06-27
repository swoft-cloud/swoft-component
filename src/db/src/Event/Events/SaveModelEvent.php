<?php

namespace Swoft\Db\Event\Events;

use Swoft\Db\Model;
use Swoft\Event\Event;

/**
 * 模型保存事件源
 *
 * @uses      ModelEvent
 * @version   2018年06月27日
 * @author    limx <715557344@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SaveModelEvent extends Event
{
    /**
     * 模型实体
     *
     * @var Model
     */
    private $model;

    /**
     * SaveModelEvent constructor.
     * @param null|string $name
     * @param Model       $model
     */
    public function __construct($name = null, Model $model)
    {
        parent::__construct($name);

        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}