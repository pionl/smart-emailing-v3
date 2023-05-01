<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\ContactListStatus;

class ContactLists extends AbstractMapHolder
{
    /**
     * Inserts contact list into the items. Unique items only.
     *
     * @return $this
     */
    public function add(ContactListStatus $list): self
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
     */
    public function create($id, $status = null): ContactListStatus
    {
        $list = new ContactListStatus($id, $status);
        $this->add($list);
        return $list;
    }
}
