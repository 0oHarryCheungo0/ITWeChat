<?php
/**
 * @link https://github.com/borodulin/yii2-services
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-services/blob/master/LICENSE.md
 */

namespace conquer\services;

/**
 * IWebServiceProvider interface may be implemented by Web service provider classes.
 *
 * If this interface is implemented, the provider instance will be able
 * to intercept the remote method invocation (e.g. for logging or authentication purpose).
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.base
 * @since 1.0
 */
interface IWebServiceProvider
{
    /**
     * This method is invoked before the requested remote method is invoked.
     * @param WebService $service the currently requested Web service.
     * @return boolean whether the remote method should be executed.
     */
    public function beforeWebMethod($service);
    /**
     * This method is invoked after the requested remote method is invoked.
     * @param WebService $service the currently requested Web service.
    */
    public function afterWebMethod($service);
}