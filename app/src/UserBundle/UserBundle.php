<?php
/**
 * User Bundle.
 */

namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class UserBundle.
 *
 */
class UserBundle extends Bundle
{
    /**
     * Get Parent Bundle
     *
     * @return bundle FOSUserBundle
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
