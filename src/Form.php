<?php
declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

class Form
{
    /**
     * The storage instance
     *
     * @var Storage
     */
    protected $storage;

    /**
     * The QueryString instance
     *
     * @var QueryString
     */
    protected $queryString;

    public function __construct(Storage $storage, QueryString $queryString)
    {
        $this->storage = $storage;
        $this->queryString = $queryString;
    }

    public function populateFields($oldValue, $_field, $key)
    {
        $newValue = null;
        if ($this->queryString->isPrefixed($key) && $this->storage->has($key)) {
            $newValue = $this->storage->get($key);
        }

        return empty($newValue) ? $oldValue : $newValue;
    }
}
