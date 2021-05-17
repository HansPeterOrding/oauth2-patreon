<?php

declare(strict_types=1);

namespace HansPeterOrding\OAuth2\Client\Provider;

use HansPeterOrding\OAuth2\Client\Provider\Exception\PatreonIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Patreon extends AbstractProvider
{
	use BearerAuthorizationTrait;

	public string $domain = 'https://www.patreon.com/';
	public string $apiDomain = 'https://api.patreon.com/';

	public function getBaseAuthorizationUrl()
	{
		return $this->domain . 'oauth2/authorize';
	}

	public function getBaseAccessTokenUrl(array $params)
	{
		return $this->domain . 'api/oauth2/token';
	}

	public function getResourceOwnerDetailsUrl(AccessToken $token)
	{
		return $this->domain . 'api/oauth2/v2/identity';
	}

	protected function getDefaultScopes()
	{
		return ['identity', 'identity.memberships'];
	}

	protected function checkResponse(ResponseInterface $response, $data)
	{
		if ($response->getStatusCode() >= 400) {
			throw PatreonIdentityProviderException::clientException($response, $data);
		} elseif (isset($data['error'])) {
			throw PatreonIdentityProviderException::oauthException($response, $data);
		}
	}

	protected function createResourceOwner(array $response, AccessToken $token)
	{
		$user = new PatreonResourceOwner($response);

		return $user->setDomain($this->domain);
	}
}
