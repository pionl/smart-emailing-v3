# Smart Emailing API v3
API wrapper for [Smart emailing](http://smartemailing.cz) API. Currenlty in development.

[![Total Downloads](https://poser.pugx.org/pion/smart-emailing-v3/downloads?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Stable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/stable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Latest Unstable Version](https://poser.pugx.org/pion/smart-emailing-v3/v/unstable?format=flat)](https://packagist.org/packages/pion/smart-emailing-v3)
[![Build Status](https://travis-ci.org/pionl/smart-emailing-v3.svg?branch=master)](https://travis-ci.org/pionl/smart-emailing-v3)

* [Installation](#installation)
* [Usage](#usage)
* [Supports](#supports)
* [Advanced docs](#advanced-docs)
* [Changelog](#changelog)
* [Contribution or overriding](#contribution-or-overriding)
* [Copyright and License](#copyright-and-license)
* [Smart emailing API](https://app.smartemailing.cz/docs/api/v3/index.html)

## Installation

**Requirements**

This package requires PHP 7.3 and higher.

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
* [x] [Contactlist](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contactlists-Get_Contactlists) Retrieve list `$api->contactlist()->lists()` or detail `$api->contactlist()->get($id)` - wrapper for 2 Request objects
* [x] CustomFields - exists: A quick way how to get custom field by it's name. `$api->customFields()->exists('name') : CustomField|bool`
* [x] [Customfields - create](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) create request `$api->customFields()->createRequest()` or send create request `$api->customFields()->create(new CustomField('test', CustomField::TEXT))`
* [x] [Customfields - search / list](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) search request `$api->customFields()->searchRequest($page = 1, $limit = 100)` or send search request `$api->customFields()->search($page = 1, $limit = 100)`
* [ ] [Customfields - rest](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields) Similar concept as contact-list - already started
* [ ] [Customfiels options](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfield_Options)
* [ ] [Contacts](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts) Similar concept as contact-list
* [ ] [Contacts in list](https://app.smartemailing.cz/docs/api/v3/index.html#api-Contacts_in_lists) Similar concept as contact-list
* [ ] [Custom emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_emails)
* [x] [Emails](https://app.smartemailing.cz/docs/api/v3/index.html#api-Emails)
* [x] [Newsletter](https://app.smartemailing.cz/docs/api/v3/index.html#api-Newsletter)
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

```php
$field = $contact->customFields()->create(12, 'test')
$list = $contact->contactLists()->create(12, 'confirmed')
```

## CustomFields

The customFields uses a wrapper for each request related to custom-fields. To create a new instance call `$api->customFields()`.
On this object you can create any request that is currently implemented. See below.

### Create

Quick way that will create request with required customField
```php
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Create\Response;

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

### Search / List
[API DOCS](https://app.smartemailing.cz/docs/api/v3/index.html#api-Customfields-Get_Customfields)

Enables searching threw the custom fields with a filter/sort support. Results are limited by 100 per page. The response
returns meta data (MetaDataInterface) and an array of `CustomField\CustomField` by calling `$response->data()`.

#### Response

* data() returns an array `CustomField\CustomField`
* meta() returns a `stdClass` with properties (defined in `MetaDataInterface`)

#### Get a list without advanced setup
Creates a search request and setups only `$page` or `$limit`. The full response from api with `customfield_options_url` or
```php
$response = $api->customFields()->search($page = 1);

/** @var \SmartEmailing\v3\Request\CustomFields\CustomField $customField */
foreach ($response->data() as $customField) {
    echo $customField->id;
    echo $customField->name;
    echo $customField->type;
}
```

#### Advanced setup - filter/sort/etc

```php
$request = $api->customFields()->searchRequest(1);

// Search by name
$request->filter()->byName('test');
$request->sortBy('name');

// Send the request
$response = $request->send();
```
##### Request methods

* Getters are via public property
    * page
    * limit
    * select
    * expand
    * sort
* Fluent Setters (with a validation) - more below.
* `filter()` returns a Filters setup - more below

###### expandBy(string : $expand)
Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
expanded data. See examples below For more information see "/customfield-options" endpoint.

Allowed values: "customfield_options"

###### select(string : $select)
Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.

Allowed values: "id", "name", "type"

###### sortBy(string : $sort)
Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
"?sort=type,-name"

Allowed values: "id", "name", "type"

###### setPage(int : $page)
Sets the current page

###### limit(int : $limit)
Sets the limit of result in single query

###### filter()
Allows filtering custom fields with multiple filter conditions.

* Getters are via public property
    * name
    * type
    * id
* Fluent Setters (with a validation)
    * byName($value)
    * byType($value)
    * byId($value)
    
### Exists
Runs a search query with name filter and checks if the given name is found in customFields. Returns `false` or the `CustomFields\CustomField`.
Uses send logic (throws RequestException).

```php
// Can throw RequestException - uses send.
if ($customField = $api->customFields()->exists('name')) {
    return $customField->id;
} else {
    throw new Exception('Not found!', 404);
}
```

### Send / Transactional emails
The implementation of API call ``send/transactional-emails-bulk``: https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
## Full transactional email example
```php
$transactionEmail = new TransactionalEmails($api);

$credentials = new SenderCredentials();
$credentials->setFrom('from@example.com');
$credentials->setReplyTo('to@example.com');
$credentials->setSenderName('Jean-Luc Picard');

$recipient = new Recipient();
$recipient->setEmailAddress('kirk@example.com');

$replace1 = new Replace();
$replace1->setKey('key1');
$replace1->setContent('content1');

$replace2 = new Replace();
$replace2->setKey('key2');
$replace2->setContent('content2');

$templateVariable = new TemplateVariable();
$templateVariable->setCustomData([
    'foo' => 'bar',
    'products' => [
        ['name' => 'prod1', 'desc' => 'desc1'],
        ['name' => 'prod1', 'desc' => 'desc2']
    ]
]);

$attachment1 = new Attachment();
$attachment1->setContentType('image/png');
$attachment1->setFileName('picture.png');
$attachment1->setDataBase64('data1');

$attachment2 = new Attachment();
$attachment2->setContentType('image/gif');
$attachment2->setFileName('sun.gif');
$attachment2->setDataBase64('data2');

$task = new Task();
$task->setRecipient($recipient);
$task->addReplace($replace1);
$task->addReplace($replace2);
$task->setTemplateVariables($templateVariable);
$task->addAttachment($attachment1);
$task->addAttachment($attachment2);

$messageContents = new MessageContents();
$messageContents->setTextBody('text_body');
$messageContents->setHtmlBody('html_body');
$messageContents->setSubject('subject');

$transactionEmail->setTag('tag_tag');
$transactionEmail->setEmailId(5);
$transactionEmail->setSenderCredentials($credentials);
$transactionEmail->addTask($task);
$transactionEmail->setMessageContents($messageContents);

$transactionEmail->send();
```

### Send / Bulk custom emails
The implementation of API call ``send/custom-emails-bulk``: https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_emails
## Full custom email example
```php
$transactionEmail = new BulkCustomEmails($api);

$credentials = new SenderCredentials();
$credentials->setFrom('from@example.com');
$credentials->setReplyTo('to@example.com');
$credentials->setSenderName('Jean-Luc Picard');

$recipient = new Recipient();
$recipient->setEmailAddress('kirk@example.com');

$replace1 = new Replace();
$replace1->setKey('key1');
$replace1->setContent('content1');

$replace2 = new Replace();
$replace2->setKey('key2');
$replace2->setContent('content2');

$templateVariable = new TemplateVariable();
$templateVariable->setCustomData([
    'foo' => 'bar',
    'products' => [
        ['name' => 'prod1', 'desc' => 'desc1'],
        ['name' => 'prod1', 'desc' => 'desc2']
    ]
]);

$task = new Task();
$task->setRecipient($recipient);
$task->addReplace($replace1);
$task->addReplace($replace2);
$task->setTemplateVariables($templateVariable);

$transactionEmail->setTag('tag_tag');
$transactionEmail->setEmailId(5);
$transactionEmail->setSenderCredentials($credentials);
$transactionEmail->addTask($task);

$transactionEmail->send();
```

### Send / Bulk custom sms
The implementation of API call ``send/custom-sms-bulk``: https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_SMS
## Full send sms example
```php
$bulkCustomSms = new BulkCustomSms($api);

$recipient = new Recipient();
$recipient->setEmailAddress('kirk@example.com');
$recipient->setCellphone('+420777888777');

$replace1 = new Replace();
$replace1->setKey('key1');
$replace1->setContent('content1');

$replace2 = new Replace();
$replace2->setKey('key2');
$replace2->setContent('content2');

$task = new Task();
$task->setRecipient($recipient);
$task->addReplace($replace1);
$task->addReplace($replace2);

$bulkCustomSms->setTag('tag_tag');
$bulkCustomSms->setSmsId(5);
$bulkCustomSms->addTask($task);

$bulkCustomSms->send();
```

## Changelog

### 0.1.8

* Added purposes for contacts.

### 0.1.7

* Fix incorrect namespace for confirmation request.

### 0.1.6

* Added confirmation request to import settings.

### 0.1.5

* Removed deprecated API usage in Contact.php: `addContactList` and `newContactList`

### 0.1.4

* CustomFields can be imported only once (unique by id)

### 0.1.3

* Added automatic chunk send for contact import - when number of contacts exceeds 500, the `send()` method will send multiple request (chunk's the contact array)

### 0.1.2

* Added exists custom field request. A quick way how to get custom field by it's name. `$api->customFields()->exists('name') : CustomField|bool`
* Contacts list allows only unique id's (when already added ignores the value) 

### 0.1.1

* Removed deprecated methods for Import\Contact\CustomField (newCustomField, setCustomFields, addCustomField)
* Added `createValue` to `CustomFields\CustomField` to enable quick creating of CustomField for import.
* **Moved the CustomField `Create`**  request and response to its own namespace `SmartEmailing\v3\Request\CustomFields\Create` and renamed to only `Request` class
* **Changed the JSON structure** from `array` to `stdClass`. Update all the `json()` usage
* Added search request for custom fields

### 0.1

* Added Custom-fields create request

## Contribution or overriding
See [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute changes. All contributions are welcome.

## Copyright and License

[smart-emailing-v3](https://github.com/pionl/smart-emailing-v3)
was written by [Martin Kluska](http://kluska.cz) and is released under the 
[MIT License](LICENSE.md).

Copyright (c) 2016 Martin Kluska
