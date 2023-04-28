<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\CustomFields\Exists;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Search\Request as SearchRequest;

/**
 * Class Request
 *
 * Runs a search query for the given name and checks it was found
 */
class Request extends SearchRequest
{
    public function __construct(Api $api, $name)
    {
        parent::__construct($api, null, 1);

        $this->filter()
            ->byName(trim($name));
    }

    /**
     * Sends the request and tries to find the custom field by the name. If found, it will return the custom field. If
     * not returns false
     *
     * @return bool|CustomField
     */
    public function exists()
    {
        $response = $this->send();

        if ($response->isSuccess() === false) {
            return false;
        }

        // Support API find on similar name but we are looking for exact string. Currently always returns single
        // object because of the limit. Prepared for future changes and supports additional name check.
        /** @var CustomField $item */
        foreach ($response->data() as $item) {
            // If the name is same
            if (trim($item->name) === $this->filter()->name) {
                return $item;
            }
        }

        return false;
    }
}
