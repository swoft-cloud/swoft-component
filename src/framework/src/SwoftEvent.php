<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 15:33
 */

namespace Swoft\Event;

/**
 * Class SwoftEvent
 * @package Swoft\Event
 */
class SwoftEvent
{
    // public const ON_ANNOTATION_LOADED = 'swoft.annotation.loaded';
    // public const ON_CONFIG_LOADED     = 'swoft.config.loaded';

    public const BEAN_INIT_BEFORE = 'swoft.bean.beforeInit';
    public const BEAN_INIT_AFTER  = 'swoft.bean.afterInit';

    public const APP_INIT_BEFORE = 'swoft.app.afterInit';
    public const APP_INIT_AFTER  = 'swoft.app.afterInit';
}
