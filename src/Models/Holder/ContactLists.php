<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\ContactListStatus;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractMapHolder<ContactListStatus>
 */
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
     * @param string|null $status Contact's status in Contactlist. Allowed values: "confirmed", "unsubscribed",
     * "removed"
     */
    public function create(int $id, ?string $status = null): ContactListStatus
    {
        $list = new ContactListStatus($id, $status);
        $this->add($list);
        return $list;
    }

    protected function entryKey(Model $entry): ?int
    {
        return $entry->getId();
    }
}
