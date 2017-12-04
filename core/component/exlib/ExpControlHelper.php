<?php
/**
 * by lll
 * EXP系统 从初始化登录到 发送和捕获
 */
namespace CallCenter\Exlib;

use Curl\Curl;

class ExpControlHelper
{

    const EPX_CONTROL_NAME = 'admin';
    const EPX_CONTROL_PWD = 'yundR82Gda';
    const EPX_API_ADDRESS = 'http://47.93.23.117/epbx/interface/uaeservice';

    public static $event = [];
    public static $response = [];
    public static $curl = null;
    public static $login = null;

    public static $object = null;

    public static $pubNumber = null;

    public static $tmpResult = [];

    // 工具类的初始化成全局的，CURL, 日志，登录信息等等
    public static function init()
    {
        if (empty(self::$curl)) {
            $curl = new Curl();
            $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 4);
            self::$curl = $curl;
        }

        if (empty(self::$login)) {
            //发送登录数据
            self::loginEpxControl();
        }
//        return new self(self::$curl, self::$logger, self::$login);
        if (empty(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    //去登录
    public static function loginEpxControl()
    {
        if (!empty(self::$login)) {
            return self::$login;
        }
        self::curlHandler(['method' => 'login','username' => self::EPX_CONTROL_NAME, 'password' => self::EPX_CONTROL_PWD]);
        $xml = self::parseSubXml(self::curlHandler());
        //获取socket的全部数据
        if (!empty($xml['response']['login'])) {    //轮询到了登录成功的响应，设置为登录
            echo 'login success' . PHP_EOL;
            self::$login = $xml['response']['login']['userid'];
            self::$tmpResult = $xml['response'];
        }
    }

    /**
     * 发送请求
     * @param \Closure $func
     * @param other mixed
     * @return
     *
     * 匿名函数用来处理监听后返回的业务逻辑
     * args用来传递请求参数，不传则默认发送监听全部event
     */
    public function sendRequest(\Closure $func, $other = null)
    {
        if (empty(self::$login)) {
            //去登录，然后查看登录信息
            echo 'goto login', PHP_EOL;
            self::loginEpxControl();
        }

        if (empty(self::$login)) {
            echo 'login fail', PHP_EOL;
            return false;
        }
        //如果没有订阅就默认订阅全部事件
        if (empty(self::$pubNumber)) {
            echo 'goto sub', PHP_EOL;
            self::subscribe();
        }
        $response = [];
        if ($other === null) {
            $response = self::parseSubXml(self::curlHandler());
        } elseif (is_array($other)) {    //目前只实现了数组
            //字符串就调用类内的方法
            self::requestApi($other);
            $response = self::parseSubXml(self::curlHandler());
        } else {
            echo 'unknow other var', PHP_EOL;
        }

        if (!empty($sendResponse['events'])) {
            $response['events'] = $sendResponse['events'];
        }

        if (!empty(self::$tmpResult) && empty($response['response'])) {
            $response['response'] = self::$tmpResult;
            self::$tmpResult = [];
        }
        //装载请求结果
//        $response['events'] = !empty($sendResponse['events']) ? $sendResponse['events'] : $response['events'];
//        $response['response'] = !empty($sendResponse['response']) ? $sendResponse['response'] : $response['response'];
        // 先试着拿一次结果
        if (!empty($response['events']) || !empty($response['response'])) {
            self::beforeSendRequest($response);
//            $func($response['events'], $response['response']);
            call_user_func_array($func, $response);
            self::affterSendRequest($response);
        }
    }

    public static function requestApi($arr)
    {
        //['method' => 'eavesdrop', 'username' => self::$login, 'exten' => $extNumber, 'callee' => $calleeNumber]
        $arr['username'] = self::$login;
        self::curlHandler($arr);
    }

    /**
     * 订阅所有事件
     */
    public static function subscribe($extNumber = 'all')
    {
        //如果当前没有订阅或者订阅号码不同，就发送一次订阅请求
        if (empty(self::$pubNumber) || self::$pubNumber != $extNumber) {
            self::curlHandler(['method' => 'subscribe', 'username' => self::$login, 'exten' => $extNumber]);
        }
        $response = self::parseSubXml(self::curlHandler());
        if (!empty($response['response']['subscribe']) && $response['response']['subscribe']['retCode'] == 0) {
            self::$pubNumber = $extNumber;
            self::$tmpResult = $response['response'];
        } else {
            echo 'unkunw subscribe';
        }
    }

    /**
     * 监听功能
     * @param $extNumber  监听者分机号
     * @param $calleeNumber  被监听分机号
     */
    public static function eavesdrop($extNumber, $calleeNumber) {
        self::curlHandler(['method' => 'eavesdrop', 'username' => self::$login, 'exten' => $extNumber, 'callee' => $calleeNumber]);
    }

    private static function beforeSendRequest(array $xmlArr)
    {
        //结果集去重之类的
        // 是否记录日志等, 组合response信息，是否提示给前端
//        print_r($xmlArr['events']);
//        file_put_contents(LOG_ROOT . 'events.events', var_export($xmlArr['events'], true), FILE_APPEND);
    }

    private static function affterSendRequest(array $xmlArr)
    {
//        echo PHP_EOL;
//        echo 'affter';
//        echo PHP_EOL;
    }

    private static function curlHandler(array $args = [])
    {
        $curl = self::$curl;
        if (empty($args)) {
            $params = ['method' => 'receive', 'MACHINE_SN' => 'uae8001'];
        } else {
            $params = array_merge(['method' => 'receive', 'MACHINE_SN' => 'uae8001'], $args);
        }
        $curl->get(self::EPX_API_ADDRESS, $params);
        if ($curl->error) {
            //记录日志，并且打印出细节
            echo 'Curl Request Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . PHP_EOL;
            return '';
        }
        $response = $curl->response;
        if (empty($response)) {
            return '';
        }
        return $response;
    }

    //字符串的各种操作,没有固定结构体，很无奈

    /**
    "null({"[2017-09-25 17:10:52]<response>\n\t<cmdType>subscribe<\/cmdType>\n\t<reason><\/reason>\n\t<retCode>0<\/retCode>\n\t<userid>admin<\/userid>\n<\/response>BEGIN_MSG[2017-09-25 17:10:52]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>3040@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>BEGIN_MSG[2017-09-25 17:10:52]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>3023@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>BEGIN_MSG[2017-09-25 17:10:53]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>2004@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>":"aaa"})"
     */
    private static function parseSubXml($xmlStr, $eventType = null)
    {
        $xmlStr = trim($xmlStr);
        if (empty($xmlStr)) {
            return [
                'events' => [],
                'response' => [],
            ];
        }
        $xmlStr = str_replace('null({"', '', $xmlStr);
        $xmlStr = str_replace('":"aaa"})', '', $xmlStr);
        $xmlStr = str_replace('\n', '', $xmlStr);
        $xmlStr = str_replace('\t', '', $xmlStr);
        $xmlStr = stripslashes($xmlStr);
        if (empty($xmlStr)) {
            return [
                'events' => [],
                'response' => [],
            ];
        }
        $logs = explode('BEGIN_MSG', $xmlStr);
        self::$event = self::$response = [];
        foreach ($logs as $log) {
            $time = substr($log, strpos($log, '['), strpos($log, ']') + 1);
            $xml = substr($log, strpos($log, ']') + 1);
            $params = (array) simplexml_load_string($xml);
            $params['time'] = $time;
            if (strpos($xml, '<response>') === 0) {     //响应事件
                self::fillResponse($params);
            } else {        //监听后返回的事件
                self::fillEvent($params);
            }
        }
        return [
            'events' => self::$event,
            'response' => self::$response,
        ];
        /**
         * "null({"[2017-09-25 17:10:52]<response>\n\t<cmdType>subscribe<\/cmdType>\n\t<reason><\/reason>\n\t<retCode>0<\/retCode>\n\t<userid>admin<\/userid>\n<\/response>BEGIN_MSG[2017-09-25 17:10:52]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>3040@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>BEGIN_MSG[2017-09-25 17:10:52]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>3023@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>BEGIN_MSG[2017-09-25 17:10:53]<event>\n\t<cmdType>register<\/cmdType>\n\t<ext>2004@1<\/ext>\n\t<userid>admin<\/userid>\n<\/event>":"aaa"})"
         */
        //结果为空
//        return self::notify($xml, $event);
    }

    private static function fillResponse($requestArr)
    {
        //目前只接 login 响应
        $reuqestType = $requestArr['cmdType'];
        self::$response[$reuqestType] = $requestArr;
    }

    private static function fillEvent($eventArr)
    {
        $eventType = $eventArr['cmdType'];
        self::$event[$eventType][] = $eventArr;
    }


    public static function buildResponse($response, $responseName = null)
    {
        return $response;
    }


    public static function buildEvent($event, $eventName = null)
    {

        if (empty($event)) {
            return [];
        }
        $return = [];
        if (empty($eventName)) {
            foreach ($event as $key => $eventGroup) {
                if (empty($eventGroup)) {
                    continue;
                }
                $data = array_filter(self::buildEventGroup($eventGroup));
                if (empty($data)) {
                    continue;
                }
                $return[$key] = $data;
            }
        } else {
            $data = array_filter(self::buildEventGroup($event));
            if (empty($data)) {
                return $return;
            }
            $return = $data;
        }

        return $return;
    }

    private static function buildEventGroup(array $eventGroup)
    {
        foreach ($eventGroup as $item) {
            $tmp = [];
            $extTel = explode('@', $item['ext'])[0]; //分机号码
            $time = substr($item['time'], 1, -1); //事件触发时间戳
            $tmp['ext_number'] = $extTel;
            $tmp['event_time'] = $time;
            if (!empty($item['uuid'])) {
                $tmp['event_uuid'] = $item['uuid'];
            }
            switch ($item['cmdType']) {
                case 'register':    //分机上线
                    $tmp = [];
                    break;
                    /**
                    < event >
                    <userid>admin</userid> <cmdType>register</cmdType> <ext>1000@1</ext>
                    </ event >
                     */
                    /**
                    < event >
                    <userid>admin</userid> <cmdType>unregister</cmdType> <ext>1000@1</ext>
                    </ event >
                     */
                    // no break
                case 'unregister':    //分机下线
                    $tmp = [];
                    break;
//                    $tmp['event_desc'] = $item['cmdType'] == 'register' ? '分机上线' : '分机下线';
                case 'hangup':      //挂断事件
                    /**
                    <event>
                    <userid>admin</userid>
                    <cmdType>hangup</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext>
                    <caller>1001</caller> <hangup_reason>NORMAL_CLEARING</hangup_reason>
                    19
                    </event>
                    其中 hangup_reason 代表挂断原因
                     */
                    $tmp['event_desc'] = '电话挂断';
                    $tmp['caller'] = $item['caller'];
                    $tmp['hangup_reason'] = $item['hangup_reason'];
                    break;
                case 'ring':        //振铃事件
                    /**
                    <event>
                    <userid>admin</userid>
                    <cmdType>ring</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext>
                    <caller>1001</caller>
                    <ringback>0<ringback>
                    </event>
                    其中,uuid 为振铃者的 uuid
                    ext 为振铃者的分机信息
                    caller 为主叫
                    ringback 为 0 时代表一般的振铃事件，为 1 时代表回铃事件
                     */
                    $tmp['event_desc'] = $item['ringback'] == 0 ? '分机来电' : '分机呼出';
                    $tmp['caller'] = $item['caller'];   //来电主叫|呼出号码
                    break;
                case 'answer':      //接听事件
                    /**
                    <event>
                    <userid>admin</userid>
                    <cmdType>answer</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext>
                    <caller>1001</caller>
                    </event>
                     */
                    $tmp['event_desc'] = '电话接通';
                    $tmp['caller'] = $item['caller'];   //主叫号
                    break;
                case 'conf_add_mem':        //进入会议室事件
                    /**
                    < event >
                    <userid>admin</userid>
                    <cmdType>conf_add_mem</cmdType> <conf_name>test</conf_name>
                    <mem_id>1<mem_id> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@2</ext>
                    </ event >
                    其中 conf_name 为会议室名称 mem_id 为进入会议室的人员 ID uuid 为进入会议室的人员 uuid ext 为进入会议室的人员分机信息
                     */
                    // no break
                case 'conf_del_mem':        //离开会议室事件
                    /**
                    < event >
                    <userid>admin</userid> <cmdType>conf_del_mem</cmdType> <conf_name>test</conf_name>
                    <mem_id>1<mem_id> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@2</ext>
                    </ event >
                    其中 conf_name 为会议室名称 mem_id 为离开会议室的人员 ID uuid 为离开会议室的人员 uuid ext 为离开会议室的人员分机信息
                     */
                    // no break
                case 'conf_mute_mem':        //会议室静音事件
                    /**
                    < event >
                    <userid>admin</userid> <cmdType>conf_mute_mem</cmdType> <conf_name>test</conf_name>
                    <mem_id>1<mem_id> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@2</ext>
                    20

                    </ event >
                    其中 conf_name 为会议室名称 mem_id 为被静音的人员 ID uuid 为被静音的人员 uuid
                    ext 为被静音的人员分机信息
                     */
                    // no break
                case 'conf_unmute_mem':        //会议取消室静音事件
                    /**
                    < event >
                    <userid>admin</userid> <cmdType>conf_unmute_mem</cmdType> <conf_name>test</conf_name>
                    <mem_id>1<mem_id> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@2</ext>
                    </ event >
                    其中 conf_name 为会议室名称 mem_id 为被静音的人员 ID uuid 为被静音的人员 uuid
                    ext 为被静音的人员分机信息
                     */
                    // no break
                case 'hold':        //保持事件
                    /**
                    <event>
                    <userid>admin</userid>
                    <cmdType>hold</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext>
                    </event>
                     */
                    // no break
                case 'unhold':
                    /**
                    <event>
                    <userid>admin</userid>
                    <cmdType>unhold</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext>
                    </event>
                     */
                    // no break
                case 'bridge':      //bridge事件 接通
                    /**
                    bridge 事件在一个 leg 和另一个 leg 相连时产生 <event>
                    <userid>admin</userid>
                    <cmdType>bridge</cmdType> <uuid>5dae38b6-7e74-11e3-93e9-a93462613292</uuid> <ext>1002@1</ext> <a_leg>5dae38b6-7e74-11e3-93e9-a93462613292<a_leg> <b_leg>909dc31f-d181-46f6-9337-2a9544acf5f0<b_leg>
                    </event>
                     */
                    // 这种事件不理会
//                    $tmp['event_desc'] = '接听成功';
//                    $tmp['a_leg'] = $item['a_leg'];
//                    $tmp['b_leg'] = $item['b_leg'];
                    // no break
                    $tmp = [];
                    break;
                default:
                    // no break
                    break;
            }
            /**
            一通呼叫有两个 leg 即两个通道，只找 ring 事件中 ringback 为 0 的，其实就以被叫 leg 为准。
            通过Socket去呼叫，其实就是通过系统回拨的方式实现通话，通话双方其实在系统中 都属于被叫，
            这个时候一通电话会有两个 leg 都是 ringback 为 0 的，作为业务系统肯定 不希望记录两条通话记录，这个时候就要去除第一条(去除规则:如果主叫被叫都是一 样的，就不受理去除掉)。
            呼叫事件主要有 ring、answer、hangup，处理事件流程如下:
            ring 事件:只处理 ringback 为 0 的，主叫号取 caller，被叫号取 ext，如果 ext 有@1 证明 是分机。
            answer 事件:根据 uuid 查找前面 ring 记录的呼叫信息，并更新接通状态及时间。 hangup 事件:根据 uuid 查找前面 ring 记录的呼叫信息，并更新挂断状态及时间。
            通过Socket呼叫示例如下，caller_displaynum一定要写自己分机号，这样才能根据ring 事件去除多出来的通话记录，callee_displaynum 建议写自己分机号，
            如果一定要设置透 显号码如 02151827790，则 ring 事件中要去进行转换，把收到 caller 的转成相应分机， 如把 02151827790 转成 805(转换思路:业务系统去记录把 02151827790 分配给了 805)。
             */
            $temp[] = $tmp;
        }
        return $temp;
    }
}