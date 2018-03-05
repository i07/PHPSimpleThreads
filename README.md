# PHPSimpleThreads

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://secure.php.net/)

A very simplistic approach on parallel processing with PHP.

PHPSimpleThreads offers a simple way of starting and handling multiple php-cli processes within your PHP application.

## Installation

Installation via composer:
   
   composer install i07/php-simple-threads
   
## Usage

PHPSimpleThreads will execute 'workers' from the workers folder in your project root folder. A basic worker should have the following structure.

```php
include(__DIR__."/../vendor/autoload.php");

class myWorker extends \SimpleThreads\Factory\WorkerFactory {

    public function doWork($arguments) {
    
        //do some work here and return the result of the task
        $mywork = $this->myWorkFunction($arguments);
        return $mywork;
    
    }
    
    private function myWorkFunction($arguments) {
        
        //do stuff based on the $arguments
        
        return $result;
        
    }

}

new myWorker($myDescription);
```
save the file as myWorker.php in the workers directory.

Example on how to start the workers:

index.php
```php
include("vendor/autoload.php");

$my_workers = [
    [
        "id" => "Worker1",
        "command" => "myWorker",
        "payload" => "argument-string"
    ],
    [
        "id" => "Worker2",
        "command" => "myWorker",
        "payload" => [
            "option1" => "value1",
            "argument" => "array"
        ]
    ]
];

$myPool = new \SimpleThreads\Pool($my_workers);
$myPool->run();

// SimpleThreads will hold an object with all workers results, the get the results of all workers:
$results = $myPool->getOutput();

var_dump($results);
```
$results will be an array of objects with all data needed to process the response from each worker.