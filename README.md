# messagenet-sms-php
Client for messagenet sms gateway

# Prerequisites

You need a https://messagenet.com account for use this library.

# Installation

```bash
composer require alexmanno/messagenet-sms-php
```

# Usage

```php
$client = new \AlexManno\Messagenet\Client\MessageNetClient(
    'YOUR-USER-ID',
    'YOUR-PASSWORD'
);

$message = new \AlexManno\Messagenet\Model\SmsMessage(
    ['DESTINATION-NUMBER'], 
    'TEXT'
);

$client->sendSms($message);
```
