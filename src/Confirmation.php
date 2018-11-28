<?php
declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

class Confirmation
{
    /**
     * The QueryString instance
     *
     * @var string
     */
    protected $queryString;

    public function __construct(QueryString $queryString)
    {
        $this->queryString = $queryString;
    }

    public function transformRedirectUrl($confirmation)
    {
        if (! is_array($confirmation) || empty($confirmation['redirect'])) {
            return $confirmation;
        }

        $confirmation['redirect'] = $this->queryString->saveAndTransformUrl($confirmation['redirect']);

        return $confirmation;
    }

    public function displayHelpMessage(array $uiSettings): array
    {
        $uiSettings['gf_cookie_monster'] = "
        <tr>
            <th><label for='gf_cookie_monster'>GF GDRP Confirmation</label></th>
            <td>
                <p name='gf_cookie_monster'>
                    Prefix query string with <code>{$this->queryString->getPrefix()}</code> to encrypt it.
                </p>
            </td>
        </tr>";

        return $uiSettings;
    }
}
