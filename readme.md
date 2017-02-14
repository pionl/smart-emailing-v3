# Smart Emailing API v3
API wrapper for [Smart emailing](http://smartemailing.cz) API. Currenlty in development.

[![Total Downloads](https://poser.pugx.org/pion/smart-emailing-v3/downloads?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Stable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/stable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Unstable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/unstable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)

* [Installation](#installation)
* [Usage](#usage)
* [Supports](#supports)
* [Changelog](#changelog)
* [Contribution or overriding](#contribution-or-overriding)
* [Suggested frontend libs](#suggested-frontend-libs)

## Installation

**Install via composer**

```
composer require pion/smart-emailing-v3
```

## Usage

Create an Api instance with your username and apiKey.

```php
use SmartEmailing\v3\Api;

...
$api = new Api('username', 'api-key');

```

then use the `$api` with desired method/component.

```php
// Creates a new instance
$api->import()->addContact(new Contact('test@test.cz'))->send();
```

or 

```php
// Creates a new instance
$import = $api->import();
$contact = new Contact('test@test.cz');
$contact->setName('Martin')->setNameDay('2017-12-11 11:11:11');
$import->addContact($contact);

// Create new contact that will be inserted in the contact list
$contact2 = $import->newContact('test2@test.cz');
$contact2->setName('Test');

// Create new contact that will be inserted in the contact list
$import->newContact('test3@test.cz')->setName('Test');
$import->send();
```

## Supports

* [x] [Import](https://app.smartemailing.cz/docs/api/v3/index.html#api-Import-Import_contacts)`$api->import()` or `new Import($api)`
* [x] [Ping](https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Aliveness_test) `$api->ping()` or `new Ping($api)`
* [x] [Credentials](https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Login_test_with_GET) `$api->credentials()` or `new Credentials($api)`
* [ ] [Contactlist](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contactlists-Get_Contactlists) Retrieve list `$api->contactlist()->lists()` or detail `$api->contactlist()->get($id)` - wrapper for 2 Request objects
* [ ] [Customfields](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) Similar concept as contact-list
* [ ] [Customfiels options](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfield_Options)
* [ ] [Contacts](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts) Similar concept as contact-list
* [ ] [Contacts in list](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts_in_lists) Similar concept as contact-list
* [ ] [Custom emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_emails)
* [ ] [Emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Emails)
* [ ] [Newsletter](https://app.smartemailing.cz/docs/api/v3/index.html#api-Newsletter)
* [ ] [Webhooks](https://app.smartemailing.cz/docs/api/v3/index.html#api-Webhooks)

## Changelog

none

## Contribution or overriding
See [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute changes. All contributions are welcome.

## Copyright and License

[smart-emailing-v3](https://github.com/pionl/smart-emailing-v3)
was written by [Martin Kluska](http://kluska.cz) and is released under the 
[MIT License](LICENSE.md).

Copyright (c) 2016 Martin Kluska