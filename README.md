# extension-order-state-module
Module used in shop extentions to read/load default credentials

## How to setup

Using composer to install the package

````
composer require wirecard/extentions-order-state-module
````

### Directory Structure

```

```

### Public API


### Tests

The tests should serve as executable documentation.

There are two types of test doubles which narrow down the scope of each test: the shop system and the persona.

## Personas

The test 

- Mallory - is a malicious user who never pays

## Shop Systems

- AlwaysFailingShopSystem - no matter what you do, it fails
- PurchaseShopSystem - it goes along with any order, but it is set up to purchase
- AuthorizationShopSystem - it does along with any order, but it is set up to authorization payments
- CustomShopSystem - a configurable shop system which you can set to either purchase or authorization, but to which you can also add custom states