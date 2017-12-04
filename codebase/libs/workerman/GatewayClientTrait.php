<?php
namespace CallCenter\Page;

use GatewayClient\Gateway;

trait GatewayClientTrait
{

    public function __construct()
    {
        Gateway::$registerAddress = \Conf::get('gateway.register.address', '');
    }


    public function bindUid(string $clientId, $uid = null)
    {
        Gateway::bindUid($clientId, $uid ?? $this->uid);
    }

    public function setSession(string $clientId, $session = null)
    {
        Gateway::setSession($clientId, $session ?? $this->user);
    }

    public function joinGroup(string $clientId, $group = null)
    {
        Gateway::joinGroup($clientId, $group ?? $this->user['user_level']);
    }

    public function sendToUid($message, $type = '')
    {
        $message = self::buildSendMessage($message, $type);
        Gateway::sendToUid($this->uid, $message);
    }

    public function sendToGroup($groupId, $message, $type = '')
    {
        $message = self::buildSendMessage($message, $type);
        Gateway::sendToGroup($groupId, $message);
    }

    public function sendToAll(string $message, array $clientId = [], $type = '')
    {
        $message = self::buildSendMessage($message, $type);
    }

    public function getAllClientCount()
    {
        return self::buildGetMessage(Gateway::getAllClientCount());
    }

    //拼装发送给客户端的数据  TODO 简单包一层
    public static function buildSendMessage($message, $type = 'controller')
    {
        return json_encode(['data' => $message, 'type' => $type]);
    }

    //拼装获取Gateway的数据  TODO 简单包一层
    public static function buildGetMessage($data)
    {
        return $data;
    }
}