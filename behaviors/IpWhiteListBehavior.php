<?php

namespace trylife\ipAccess\behaviors;

use trylife\ipAccess\models\IpAccess;
use Yii;
use yii\base\Behavior;
use yii\base\Controller;
use yii\web\HttpException;

/**
 * Class IpWhiteListBehavior
 * @package common\access
 */
class IpWhiteListBehavior extends Behavior
{
    public $open = false;
    public $allowIps = ['127.0.0.1'];
    public $allowRoutes = [
        'tools/ip-access/ping',
        'wechat-server/index',
    ];
	
	public $errorMessage = 'You do not have access rights!';
	public $errorCode = 403;

    public function events()
    {
        return [
            'beforeAction' => Controller::EVENT_BEFORE_ACTION
        ];
    }

    public function beforeAction()
    {
        $route = Yii::$app->requestedAction ? Yii::$app->requestedAction->getUniqueId() : Yii::$app->requestedRoute;
        $ip = Yii::$app->getRequest()->getUserIP();
        if (!IpAccess::hasIpAccess() && !in_array($ip, $this->allowIps) && !in_array($route, $this->allowRoutes)) {
            throw new HttpException($this->errorMessage, $this->errorCode);
        }
    }
}
