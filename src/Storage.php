<?php
declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

use Defuse\Crypto\Crypto;

class Storage
{
    protected const KEY_PREFIX = 'gf_gdrp_v1_';
    protected const VALUE_PREFIX = 'gf_gdrp_confirmation_';

    /**
     * The random ID of this confirmation
     *
     * @var string
     */
    protected $id;

    /**
     * The encryption password
     *
     * @var string
     */
    protected $password;

    public function __construct(string $id, string $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function set(string $key, string $value): void
    {
        // Ensure the value is long enough to encrypt.
        $prefixedValue = static::VALUE_PREFIX . $value;
        $encryptValue = Crypto::encryptWithPassword($prefixedValue, $this->password);

        set_transient(
            $this->prefixKey($key),
            $encryptValue,
            3600 // 1 hour.
        );
    }

    protected function prefixKey(string $key): string
    {
        return static::KEY_PREFIX . $this->getId() . $key;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function has($key): bool
    {
        $value = $this->get($key);
        return ! empty($value);
    }

    public function get($key): ?string
    {
        $value = (string) get_transient(
            $this->prefixKey($key)
        );

        if (empty($value) || ! is_string($value)) {
            return null;
        }

        $value = Crypto::decryptWithPassword($value, $this->password);
        if (0 === strpos($value, static::VALUE_PREFIX)) {
            $value = substr($value, strlen(static::VALUE_PREFIX));
        }

        return empty($value) ? null : $value;
    }
}
