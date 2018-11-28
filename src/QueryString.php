<?php
declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

class QueryString
{
    public const PASSWORD_QUERY = 'gf_gdrp_pw';
    public const ID_QUERY = 'gf_gdrp_id';
    /**
     * Prefix of the parameter names
     *
     * @var string
     */
    protected $prefix;
    /**
     * The storage instance
     *
     * @var Storage
     */
    protected $storage;

    public function __construct(string $prefix, Storage $storage)
    {
        $this->prefix = $prefix;
        $this->storage = $storage;
    }

    public function saveAndTransformUrl(string $url): string
    {
        $queries = $this->parse($url);

        // TODO: Refactor!
        foreach ($queries as $key => $value) {
            if (! $this->isPrefixed($key)) {
                continue;
            }

            $this->storage->set($key, $value);
            $url = remove_query_arg($key, $url);
        }

        return add_query_arg([
            static::ID_QUERY => $this->storage->getId(),
            static::PASSWORD_QUERY => $this->storage->getPassword(),
        ], $url);
    }

    protected function parse(string $url): array
    {
        $queries = [];
        $queryString = wp_parse_url($url, PHP_URL_QUERY);
        wp_parse_str($queryString, $queries);

        return $queries;
    }

    public function isPrefixed(string $key): bool
    {
        return 0 === strpos($key, $this->prefix);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
