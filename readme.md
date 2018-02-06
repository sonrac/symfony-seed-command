[![Build Status](https://travis-ci.org/sonrac/symfony-seed-command.svg?branch=master)](https://travis-ci.org/sonrac/symfony-seed-command) 
[![StyleCI](https://styleci.io/repos/105322873/shield?branch=master&style=flat)](https://styleci.io/repos/105322873)
    
![Scrutinizer Build](https://scrutinizer-ci.com/g/sonrac/symfony-seed-command/badges/build.png?b=master)
![Scrutinizer](https://scrutinizer-ci.com/g/sonrac/symfony-seed-command/badges/quality-score.png?b=master)
![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/sonrac/symfony-seed-command/badges/coverage.png?b=master)
[![codecov](https://codecov.io/gh/sonrac/symfony-seed-command/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/symfony-seed-command)
![Packagist](https://poser.pugx.org/sonrac/symfony-seed-command/v/stable.svg)
[![Latest Unstable Version](https://poser.pugx.org/sonrac/symfony-seed-command/v/unstable)](https://packagist.org/packages/sonrac/symfony-seed-command)
![License](https://poser.pugx.org/sonrac/symfony-seed-command/license.svg)
[![Total Downloads](https://poser.pugx.org/sonrac/symfony-seed-command/downloads)](https://packagist.org/packages/sonrac/symfony-seed-command)
[![Monthly Downloads](https://poser.pugx.org/sonrac/symfony-seed-command/d/monthly)](https://packagist.org/packages/sonrac/symfony-seed-command)
[![Daily Downloads](https://poser.pugx.org/sonrac/symfony-seed-command/d/daily)](https://packagist.org/packages/sonrac/symfony-seed-command)
[![composer.lock](https://poser.pugx.org/sonrac/symfony-seed-command/composerlock)](https://packagist.org/packages/sonrac/symfony-seed-command)

## Install

```bash
composer require sonrac/symfony-seed-command
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


