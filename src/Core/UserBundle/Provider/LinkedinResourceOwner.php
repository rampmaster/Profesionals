<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\UserBundle\Provider;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth1ResourceOwner;

/**
 * LinkedinResourceOwner
 *
 * @author Francisco Facioni <fran6co@gmail.com>
 */
class LinkedinResourceOwner extends GenericOAuth1ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url'   => 'https://www.linkedin.com/uas/oauth/authenticate',
        'request_token_url'   => 'https://api.linkedin.com/uas/oauth/requestToken',
        'access_token_url'    => 'https://api.linkedin.com/uas/oauth/accessToken',
        'infos_url'           => 'http://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,picture-url,email-address,skills)',
        'user_response_class' => '\HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse',
        'realm'               => 'http://api.linkedin.com',
        'signature_method'    => 'HMAC-SHA1',
        'scope'               => null
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier' => 'id',
        'nickname'   => 'formattedName',
        'realname'   => 'formattedName',
    );

    /**
     * {@inheritDoc}
     */
    protected function httpRequest($url, $content = null, $parameters = array(), $headers = array(), $method = null)
    {
        $headers[] = 'x-li-format: json';

        return parent::httpRequest($url, $content, $parameters, $headers, $method);
    }

    /**
     * Add scope (Required by linkedin API if email address is needed)
     *
     * {@inheritDoc}
     */
    protected function getRequestToken($redirectUri, array $extraParameters = array())
    {
        return parent::getRequestToken($redirectUri, array_merge(array('scope' => $this->getOption('scope')), $extraParameters));
    }
}
