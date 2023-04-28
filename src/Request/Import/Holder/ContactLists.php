<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Import\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Request\Import\ContactList;

class ContactLists extends AbstractMapHolder
{
    /**
     * Inserts contact list into the items. Unique items only.
     *
     * @return $this
     */
    public function add(ContactList $list)
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates ContactList entry and inserts it to the array
     *
     * @param int         $id
     * @param string|null $status Contact's status in Contactlist. Allowed values: "confirmed", "unsubscribed",
     * "removed"
     *
     * @return ContactList
     */
    public function create($id, $status = null)
    {
        $list = new ContactList($id, $status);
        $this->add($list);
        return $list;
    }
}
