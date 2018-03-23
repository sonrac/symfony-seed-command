[![Build Status](https://travis-ci.org/sonrac/functional-traits-for-coverage.svg?branch=master)](https://travis-ci.org/sonrac/functional-traits-for-coverage) 
[![StyleCI](https://styleci.io/repos/105322873/shield?branch=master&style=flat)](https://styleci.io/repos/105322873)
    
![Scrutinizer Build](https://scrutinizer-ci.com/g/sonrac/functional-traits-for-coverage/badges/build.png?b=master)
![Scrutinizer](https://scrutinizer-ci.com/g/sonrac/functional-traits-for-coverage/badges/quality-score.png?b=master)
![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/sonrac/functional-traits-for-coverage/badges/coverage.png?b=master)
[![codecov](https://codecov.io/gh/sonrac/functional-traits-for-coverage/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/functional-traits-for-coverage)
![Packagist](https://poser.pugx.org/sonrac/functional-traits-for-coverage/v/stable.svg)
[![Latest Unstable Version](https://poser.pugx.org/sonrac/functional-traits-for-coverage/v/unstable)](https://packagist.org/packages/sonrac/functional-traits-for-coverage)
![License](https://poser.pugx.org/sonrac/functional-traits-for-coverage/license.svg)
[![composer.lock](https://poser.pugx.org/sonrac/functional-traits-for-coverage/composerlock)](https://packagist.org/packages/sonrac/functional-traits-for-coverage)

## Install

```bash
composer require sonrac/functional-traits-for-coverage
```

## Introduction

The package is designed to fill the database with data bypassing migrations.

## Usages for silex or symfony 

Add to console application command `sonrac\SeedCommand`, as example, for silex:

```php

$app->add(new sonrac\SimpleSeed\SeedCommand(null, $app->get('db')));

```

## Create seed:

Seed class must be implement `sonrac\SimpleSeed\SeedInterface`

## Predefined seed classes

* `sonrac\SimpleSeed\SimpleSeed`

Simple seed for data insert. Define `getTable` for table name and `getData` 
for get data.

Insert would be run automatically

* `sonrac\SimpleSeed\SimpleSeedWithCheckExists`

Seed with check exists inserted data before insert. 
Define `getTable` for table name and `getData` 
for get data. Also, define `getWhereForRow` which is where filter for select data 
before insert

Insert would be run automatically


