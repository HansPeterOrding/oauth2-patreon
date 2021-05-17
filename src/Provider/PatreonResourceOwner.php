<?php

declare(strict_types=1);

namespace HansPeterOrding\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class PatreonResourceOwner implements ResourceOwnerInterface
{
	use ArrayAccessorTrait;

	protected string $domain;

	protected array $response;

	public function __construct(array $response = [])
	{
		$this->response = $response;
	}

	public function getId()
	{
		return $this->getValueByKey($this->response['data'], 'id');
	}

	public function setDomain(string $domain): self
	{
		$this->domain = $domain;

		return $this;
	}

	public function toArray()
	{
		return $this->response;
	}
}
