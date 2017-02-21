<?php
namespace SmartEmailing\v3\Request\CustomFields\Search;

use SmartEmailing\v3\Exceptions\JsonDataInvalidException;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\Response as BaseResponse;

/**
 * Class SearchResponse
 *
 * @package SmartEmailing\v3\Request\CustomFields\Search
 */
class Response extends BaseResponse
{
    /**
     * @return array<CustomField>
     */
    public function data()
    {
        return parent::data();
    }

    /**
     * @return MetaDataInterface
     */
    public function meta()
    {
        return parent::meta();
    }

    /**
     * @inheritDoc
     */
    protected function setupData()
    {
        parent::setupData();

        if ($this->isSuccess()) {
            JsonDataInvalidException::throwIfInValid($this->json, 'data', 'is_array');
            $this->data = [];
            foreach ($this->json->data as $customField) {
                $this->data[] = CustomField::fromJSON($customField);
            }
        }
        return $this;
    }

}