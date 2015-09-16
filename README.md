## Installation
You can install **runthered-php** via composer or by downloading the source.

The Packagist URL is https://packagist.org/packages/runthered/runthered

## Overview

The Run The Red PHP API wrapper libraries allow you to call RTR's public APIs via simple PHP requests.

## Examples

Push API send MT and query DLR:
```php
<?php
// require __DIR__ . '/vendor/autoload.php'; if using Composer, require_once('runthered/push_api.php'); otherwise
//require_once('runthered/push_api.php');
require __DIR__ . '/vendor/autoload.php';
use RtrPushApi\PushApi;
use RtrPushApi\PushApiException;

try{
        $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
        $pushApi = new PushApi($username,$password,$service_key);

        $to = '6421859582';
        $from = '8222';
        $body = 'Hello World!';
        $id = 12345;
        $response = $pushApi->pushMessage($body, $to, $from, $id);
        echo "The msg_id is $response->msg_id\n";
        echo "The status is $response->status\n";
        echo "The id is $response->id\n";

        $msg_id = '55f8d226e13823069edbdfe2';
        $dlr_id = 12346;
        $dlr_response = $pushApi->queryDlr($msg_id,$dlr_id);
        echo "The status is $dlr_response->status\n";
        echo "The reason is $dlr_response->reason_code\n";
        echo "The dlr id is $dlr_response->id\n";
} catch (PushApiException $e){
        echo 'Caught exception: ',  $e->getMessage(), " and code ", $e->getCode(), "\n";
}

?>

```
HTTP Gateway send MT and query DLR status:
```php
<?php
// require __DIR__ . '/vendor/autoload.php'; if using Composer, require_once('runthered/http_gateway.php'); otherwise
//require_once('runthered/http_gateway.php');
require __DIR__ . '/vendor/autoload.php';
use RtrHttpGateway\HttpGatewayApi;
use RtrHttpGateway\HttpGatewayException;

try{
        $username = 'snoop7';
        $password = 'snoop7';
        $service_key = 'snop7';
        $httpGatewayApi = new HttpGatewayApi($username,$password,$service_key);

        $to = '6421859582';
        $from = '2059';
        $message = 'Hello World!';

        $response = $httpGatewayApi->pushMessage($message, $to, $from);
        echo "The msg_id is $response\n";

        $msg_id = '55f8d193e13823069edbdfdd';
        $dlr_response = $httpGatewayApi->queryDlr($msg_id);
        echo "The status is $dlr_response->status\n";
        echo "The reason is $dlr_response->reason_code\n";
        echo "The dlr id is $dlr_response->id\n";
} catch (HttpGatewayException $e){
        echo 'Caught exception: ',  $e->getMessage(), " and code ", $e->getCode(), "\n";
}

?>

```
## Prerequisites
* PHP >= 5.3.0
