# Upgrade 0.4 to 1.0

- Change all used model classes namespace to 'SmartEmailing\v3\Models'
(for example `SmartEmailing\v3\Request\Import\Contact` is now `SmartEmailing\v3\Models\Contact`)
- Change references to namespace `SmartEmailing\v3\Request` to `SmartEmailing\v3\Endpoints`
- Rename `SmartEmailing\v3\Request\CustomFields\CustomField` to `SmartEmailing\v3\Models\CustomFieldDefinition`
- Rename `SmartEmailing\v3\Request\Import\CustomField` to `SmartEmailing\v3\Models\CustomFieldValue`
- Rename `SmartEmailing\v3\Request\Import\Contactlist` to `SmartEmailing\v3\Models\ContactlistStatus`
- Rename `SmartEmailing\v3\Request\Import\Settings` to `SmartEmailing\v3\Models\ImportContactsSettings`
- Endpoints `/api/v3/orders` (`$api->eshopOrders()`) and `/api/v3/orders-bulk` (`$api->eshopOrdersBulk()`) are deprecated because SmartEmailing deprecated them in favour of `/api/v3/import-orders`
- If you still want to use these deprecated endpoints change `SmartEmailing\v3\Request\Eshops\Model\Order` to `SmartEmailing\v3\Models\OrderWithFeedItems`
- For `Search` requests use `setPage($page, $limit)` instead of `setPage($page)->limit($limit)`
- For `Search` requests use getters `getPage()`, `getOffset()` and `getLimit()` instead of properties
- Array `CustomFieldDefinition->options` is no longer available. USe `->options()` instead to get proper `Holder` object
- For custom field options use `CustomFieldOption` object

- `CustomRequest` now returns `CustomResponse` with parsed data using `->data()`

- All `Api` and `Endpoint` methods now follows these conventions:
  - `something()` sends request immediately and returns `Response` or data (`Model` or `Model[]`)
  - `somethingRequest()` returns request object that cen be modified and then send by calling `$request->send()`
  - `get($id): ?Model` - returns Model class by id
  - `getBySomething($something): ?Model` - returns Model class by id
  - `list($page = null, $limit = null): array` - returns `Model[]` without filtering
  - `searchRequest($page = null, $limit = null): Request` - returns search `Request` that can be modified and then send by calling `$request->send()`
  - `create($data): void` - sends request to create new item
  - `createRequest($data): Request` - returns create `Request` that can be modified and then send by calling `$request->send()`

- This convetions means for example that now:
  - `$api->ping()->send()` is just `$api->ping()`
  - `$api->import()` is `$api->importRequest()`
  - `$api->customFields()->create()` return `Models\CustomFieldDefinition`
  - `$api->customFields()->exists()` is `$api->customFields()->getByName()`
  - for simple list you can use `$api->customFields()->list()`
  - `$api->contactlists()->lists()` is `$api->contactlists()->list()` and returns `Models\ContactList[]`
  - `$api->contactlists()->truncate($id)->send()` is `$api->contactlists()->truncate($id)`
  - `$api->contactlists()->search()` is `$api->contactlists()->searchRequest()`
