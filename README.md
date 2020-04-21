# SMS 4 Connect SMS API Wrapper for PHP

<a href="https://travis-ci.org/developifynet/sms4connect-php"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/developifynet/sms4connect-php"><img src="https://poser.pugx.org/developifynet/sms4connect-php/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/developifynet/sms4connect-php"><img src="https://poser.pugx.org/developifynet/sms4connect-php/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/developifynet/sms4connect-php"><img src="https://poser.pugx.org/developifynet/sms4connect-php/license.svg" alt="License"></a>

This composer package offers a quick SMS setup for your php or Laravel applications.

## Installation

Begin by pulling in the package through Composer.

```bash
composer require developifynet/sms4connect-php
```

## Laravel Framework Usage

Within your controllers, you can call Sms4Connect facade and can send quick SMS.

#### Send SMS

##### Send SMS for Single Number
```php
use Developifynet\Sms4Connect\Sms4Connect;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'to' => '923XXXXXXXXX',                             // You can provide single number as string or an array of numbers
        'msg' => '<PUT_YOUR_MESSAGE_HERE>',                 // Message string you want to send to provided number(s)
        'mask' => '<PUT_YOUR_MASK_HERE>',                   // Use a registered mask with SMS 4 Connect
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
     );
    
    $response = Sms4Connect::SendSMS($SMSObj);
}
```

##### Send SMS for Multiple Number
```php
use Developifynet\Sms4Connect\Sms4Connect;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'to' => ['923XXXXXXXXX', '923XXXXXXXXX'],,          // You can provide single number as string or an array of numbers
        'msg' => '<PUT_YOUR_MESSAGE_HERE>',                 // Message string you want to send to provided number(s)
        'mask' => '<PUT_YOUR_MASK_HERE>',                   // Use a registered mask with SMS 4 Connect
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
     );
    
    $response = Sms4Connect::SendSMS($SMSObj);
}
```


#### Check Delivery Status

##### Check Status for Single Transaction

```php
use Developifynet\Sms4Connect\Sms4Connect;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'transaction' => 'XXXXXXXXX',                       // You can provide single sms transaction id as string or an array of numbers
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS::checkDeliveryStatus($SMSObj);
}
```

##### Check Status for Multiple Transaction

```php
use Developifynet\Sms4Connect\Sms4Connect;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'transaction' => ['XXXXXXXXX', 'XXXXXXXXX'],        // You can provide single sms transaction id as string or an array of numbers
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS::checkDeliveryStatus($SMSObj);
}
```

#### Check Account Balance

```php
use Developifynet\Sms4Connect\Sms4Connect;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS::checkBalance($SMSObj);
}
```

## Other Usage

Within your controllers, you can call Sms4ConnectSMS Object and can send quick SMS.

#### Send SMS

##### Send SMS for Single Number
```php
use \Developifynet\Sms4Connect\Sms4ConnectSMS;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'to' => '923XXXXXXXXX',                             // You can provide single number as string or an array of numbers
        'msg' => '<PUT_YOUR_MESSAGE_HERE>',                 // Message string you want to send to provided number(s)
        'mask' => '<PUT_YOUR_MASK_HERE>',                   // Use a registered mask with SMS 4 Connect
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS();
    $response = $sms4connect->SendSMS($SMSObj);
}
```

##### Send SMS for Multiple Number
```php
use \Developifynet\Sms4Connect\Sms4ConnectSMS;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'to' => ['923XXXXXXXXX', '923XXXXXXXXX'],,          // You can provide single number as string or an array of numbers
        'msg' => '<PUT_YOUR_MESSAGE_HERE>',                 // Message string you want to send to provided number(s)
        'mask' => '<PUT_YOUR_MASK_HERE>',                   // Use a registered mask with SMS 4 Connect
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
     );
    
    $sms4connect = new Sms4ConnectSMS();
    $response = $sms4connect->SendSMS($SMSObj);
}
```

#### Check Delivery Status

##### Check Status for Single Transaction

```php
use \Developifynet\Sms4Connect\Sms4ConnectSMS;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'transaction' => 'XXXXXXXXX',                       // You can provide single sms transaction id as string or an array of numbers
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS();
    $response = $sms4connect->checkDeliveryStatus($SMSObj);
}
```

##### Check Status for Multiple Transaction

```php
use \Developifynet\Sms4Connect\Sms4ConnectSMS;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'transaction' => ['XXXXXXXXX', 'XXXXXXXXX'],        // You can provide single sms transaction id as string or an array of numbers
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS();
    $response = $sms4connect->checkDeliveryStatus($SMSObj);
}
```

#### Check Account Balance

```php
use \Developifynet\Sms4Connect\Sms4ConnectSMS;
public function index()
{
    $SMSObj = array(
        'id' => '<PUT_YOUR_ACCOUNT_ID_HERE>',               // Use your account id here
        'password' => '<PUT_YOUR_ACCOUNT_PASSWORD_HERE>',   // Use your account password here
        'test_mode' => '0',                                 // 0 for Production, 1 for Mocking as Test
    );
    
    $sms4connect = new Sms4ConnectSMS();
    $response = $sms4connect->checkBalance($SMSObj);
}
```

### Note
Provided numbers should start with Country code. A Pakistani number you have to write down as 923XXXXXXXXX