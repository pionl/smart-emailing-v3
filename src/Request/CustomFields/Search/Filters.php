<?php
namespace SmartEmailing\v3\Request\CustomFields\Search;

use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Exceptions\InvalidFormatException;

class Filters
{
    /**
     * Filters the results by the name
     * @var string|null
     */
    public $name = null;
    /**
     * Filters the results by the id
     * @var int|null
     */
    public $id = null;
    /**
     * Filters the results by the type
     * @var string|null
     */
    public $type = null;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Filters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param null|string $byName
     *
     * @return Filters
     */
    public function byName($byName)
    {
        $this->name = $byName;
        return $this;
    }

    /**
     * @param null|int $byId
     *
     * @return Filters
     */
    public function byId($byId)
    {
        $this->id = $byId;
        return $this;
    }

    /**
     * @param null|string $byType
     *
     * @return Filters
     *
     * @throws InvalidFormatException if type is not valid
     */
    public function byType($byType)
    {
        InvalidFormatException::checkInArray($byType, CustomField::types());
        $this->type = $byType;
        return $this;
    }

}