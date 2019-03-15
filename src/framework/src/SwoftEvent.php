<?php declare(strict_types=1);

namespace Swoft;

/**
 * Class SwoftEvent
 * @since 2.0
 */
class SwoftEvent
{
    // public const ON_ANNOTATION_LOADED = 'swoft.annotation.loaded';
    // public const ON_CONFIG_LOADED     = 'swoft.config.loaded';

    // public const BEAN_INIT_BEFORE = 'swoft.bean.beforeInit';
    // public const BEAN_INIT_AFTER  = 'swoft.bean.afterInit';

    // public const APP_INIT_BEFORE = 'swoft.app.init.before';
    public const APP_INIT_AFTER  = 'swoft.app.init.after';
}
