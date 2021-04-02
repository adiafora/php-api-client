php-api-client
=======================================

This package makes it very easy to access any API using both GET and POST requests. You can also send headers and parameters.

Installation
-----------------------------------

Run:

```php
    composer require "adiafora/php-api-client"
```

Configurations
-----------------------------------

You can set parameters for curl using global constants. 

`SET_CURLOPT_CONNECTTIMEOUT` - The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.	Default is 10.
`SET_CURLOPT_TIMEOUT` - The maximum number of seconds to allow cURL functions to execute. Default is 10.

Usage
-----------------------------------

You must create a class that extends the `Adiafora\ApiClient\AbstractApiClient` abstract class. And define the methods:

 `getHeaders()` - returns an array of headers.
 
 `getUrl()` - returns a string, the API url.
 
And in your project, you must refer to this class, which extends the `Adiafora\ApiClient\AbstractApiClient`.

When accessing your class, you can pass the GET or POST request type (by default GET) and an array of request parameters, if any, to the constructor method. if you execute a GET request without parameters, you don't need to pass anything to the constructor method.

```php
 //Example

 $api = new MyApiClient('POST', ['name' => 'Sergey']);
 // or
 $api = new MyApiClient();
 
```

To make a request to the API, call the `send()` method:

```php
 $response = $api->send();
```

The method returns an object of class `Adiafora\ApiClient\ApiResponse` that contains these methods: 

`code()` - return code of response.

`response()` - return response from the API decoded using json_decode(). Because most APIs return a response in json format. If you want to get the answer as it is, then use the dirty() method.

`dirty()` - return the answer as it is without transformations.

`error()` - return curl_error() for the curl.

`errno()` - return curl_errno() for the curl.

`requestParameters()` - return parameters of request.

`requestUrl()` - return url of request.

`requestHeaders()` - return headers of request.

`totalTime()` - return total transaction time in seconds for last transfer.

The API returns a file
----------------------------------

If the API shall to return a file, call the file() method with a single argument - an absolute path that includes the name of the file where you want to save the resulting file. And then call the send() method. In this case, the Response::response() Api class method will return the full name of the received file.

```php
 $api = new MyApiClient();

 $response = $api->file(__DIR__ . '/invoice/file.csv')
               ->send();
 
 echo $response->response(); // '/app/www/invoice/file.csv'
```


Real example
----------------------------------

For example, we need to connect to the Yandex.Webmaster API. 

> For more information, see the API documentation https://yandex.com/dev/webmaster/doc/dg/concepts/About.html/

We are creating a class `WebmasterApiClient`

```php
    use Adiafora\ApiClient\AbstractApiClient;
    
    class WebmasterApiClient extends AbstractApiClient
    {
        private $resource;    

        private $token;
    
        private $userId;
    
        public function __construct(string $resource, string $method, array $params = [])
        {
            parent::__construct($method, $params);
    
            $this->resource = $resource;
            $this->userId   = USER_ID;
            $this->token    = TOKEN;
        }
    
        public function getHeaders(): array
        {
            return [
                'Authorization: OAuth ' . $this->token,
                'Accept-Language: ru',
                'Content-Type: application/json; charset=utf-8'
            ];
        }
    
        public function getUrl(): string
        {
            return 'https://api.webmaster.yandex.net/v4/user/' . $this->userId . '/' . $this->resource;
        }
    }

```

We have implemented 2 required methods `getHeaders()` and `getUrl()`. Everything else, including the implementation of the constructor method, is only necessary for implementing a specific API.

Now we want to get a list of all hosts. There is nothing easier! Just:

```php
// @see https://yandex.com/dev/webmaster/doc/dg/reference/hosts-docpage/
$api = new WebmasterApiClient('hosts', Adiafora\ApiClient\Method::GET);
$response = $api->send();

$response->code(); // 200
$response->response(); // array()
```

Or getting the history of SQI changes using the parameters:

```php
// @see https://yandex.com/dev/webmaster/doc/dg/reference/sqi-history.html/
$api = new WebmasterApiClient('hosts/' . $hostId . '/sqi-history', Adiafora\ApiClient\Method::GET, [
            'date_from' => '2016-01-01T00:00:00,000+0300',
        ]);
$api->send(); 

```

License
-----------------------------------

The MIT License (MIT). Please see License File for more information.
