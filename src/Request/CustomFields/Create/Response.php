<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\CustomFields\Create;

use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * @return CustomField|null
     */
    public function data()
    {
        /** @var CustomField|null */
        return parent::data();
    }

    /**
     * Parses the CustomField data
     *
     * @inheritdoc
     */
    protected function setupData()
    {
        parent::setupData();

        $data = $this->value($this->json, 'data');

        // Import the data
        if ($data !== null) {
            $this->data = CustomField::fromJSON($data);
        }

        return $this;
    }
}
