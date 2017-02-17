# Smart Emailing API v3
API wrapper for [Smart emailing](http://smartemailing.cz) API. Currenlty in development.

[![Total Downloads](https://poser.pugx.org/pion/smart-emailing-v3/downloads?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Stable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/stable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Unstable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/unstable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)

* [Installation](#installation)
* [Usage](#usage)
* [Supports](#supports)
* [Advanced docs](#advanced-docs)
* [Changelog](#changelog)
* [Contribution or overriding](#contribution-or-overriding)
* [Copyright and License](#copyright-and-license)
* [Smart emailing API](https://app.smartemailing.cz/docs/api/v3/index.html)

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
### Error handling

When using the `send()` method in any request class you can catch the error exception `RequestException`.

```php
use SmartEmailing\v3\Exceptions\RequestException;

try {
    $api->ping()->send();
} catch (RequestException $exception) {
    $exception->response(); // to get the real response, will hold status and message (also data if provided)
    $exception->request(); // Can be null if the request was 200/201 but API returned error status text
}
```

## Supports

* [x] [Import](https://app.smartemailing.cz/docs/api/v3/index.html#api-Import-Import_contacts)`$api->import()` or `new Import($api)`
* [x] [Ping](https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Aliveness_test) `$api->ping()` or `new Ping($api)`
* [x] [Credentials](https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Login_test_with_GET) `$api->credentials()` or `new Credentials($api)`
* [ ] [Contactlist](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contactlists-Get_Contactlists) Retrieve list `$api->contactlist()->lists()` or detail `$api->contactlist()->get($id)` - wrapper for 2 Request objects
* [x] [Customfields - create](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) create request `$api->customFields()->createRequest()` or send request `$api->customFields()->create(new CustomField('test', CustomField::TEXT))`
* [ ] [Customfields - rest](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) Similar concept as contact-list - already started
* [ ] [Customfiels options](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfield_Options)
* [ ] [Contacts](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts) Similar concept as contact-list
* [ ] [Contacts in list](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts_in_lists) Similar concept as contact-list
* [ ] [Custom emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_emails)
* [ ] [Emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Emails)
* [ ] [Newsletter](https://app.smartemailing.cz/docs/api/v3/index.html#api-Newsletter)
* [ ] [Webhooks](https://app.smartemailing.cz/docs/api/v3/index.html#api-Webhooks)

## Advanced docs

## Import

The import holds 2 main data points:
1. Settings `$import->settings()->setUpdate(true)`
2. Contacts `$import->newContact() : Contact`, `$import->contacts() : array` and `$import->addContact() : $this`

Example of usage is above.

### [Contact](./src/Request/Import/Contact.php)

The import holds 3 main data points:
1. All data accessible via public properties. Fluent set method has basic validation and date convert logic
2. CustomFields `$contact->customFields()` for adding new fields
3. ContactLists `$contact->contactLists()` for adding new contact list

See source code for all methods/properties that you can use

#### [CustomFields](./src/Request/Import/Holder/CustomFields.php) and [ContactLists](./src/Request/Import/Holder/ContactLists.php)

Uses a data holder with `create`/`add`/`get`/`isEmpty`/`toArray`/`jsonSerialize` methods.

## CustomFields

The customFields uses a wrapper for each request related to custom-fields. To create a new instance call `$api->customFields()`.
On this object you can create any request that is currently implemented. See below.

### Create

Quick way that will create request with required customField
```php
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Responses\Response;

...
// Create the new customField and send the request now.
$response = $api->customFields()->create(new CustomField('test', CustomField::TEXT));
    
 // Get the customField in data
$customFieldId = $response->data()->id;
```

or 

```php
$request = $api->customFields()->createRequest(); // You can pass the customField object

// Setup customField
$customField = new CustomField();
$request->setCustomField($customField);

// Setup data
$customField->setType(CustomField::RADIO)->setName('test');

// Send the request
$response = $request->send();
```

## Changelog

### 0.1

* Added Custom-fields create request

## Contribution or overriding
See [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute changes. All contributions are welcome.

## Copyright and License

[smart-emailing-v3](https://github.com/pionl/smart-emailing-v3)
was written by [Martin Kluska](http://kluska.cz) and is released under the 
[MIT License](LICENSE.md).

Copyright (c) 2016 Martin Kluska