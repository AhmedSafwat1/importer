# Task for simple import form excel file 
Simple Project to handle import , build it native and follow up solid


## Requirements
- php version >=8.0
- sqlite extension for php

## installation 
```bash
  composer i
```

## Running Tests

To run tests, run the following command

```bash
  composer run-script test
```


## Use command lin for import example

```bash
php importCommand.php /home/safwat/Work/Tasks/Importer/files/data.xlsx
```

## Run server to test api 

```
php -S localhost:8000  index.php
```

- open `http://127.0.0.1:8000/?type=json`
- can change type to xml
- api support two type xml,json default value is json # Task for simple import form excel file 



## Authors

- [@Ahmed safwat](https://github.com/AhmedSafwat1)