<?php
namespace SmartEmailing\v3\Request\CustomFields\Create;

use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\Response as BaseResponse;

/**
 * Class Response
 *
 * @package SmartEmailing\v3\Request\CustomFields\Responses
 */
class Response extends BaseResponse
{
    /**
     * Parses the CustomField data
     * @inheritdoc
     */
    protected function setupData()
    {
        parent::setupData();

        $data = $this->value($this->json, 'data');

        // Import the data
        if (!is_null($data)) {
            $this->data = CustomField::fromJSON($data);
        }
    }

    /**
     * @return CustomField|null
     */
    public function data()
    {
        return parent::data();
    }


}