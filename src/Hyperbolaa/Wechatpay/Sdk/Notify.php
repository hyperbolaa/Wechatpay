<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Exception\FaultException;
use Hyperbolaa\Wechatpay\Lib\Collection;
use Hyperbolaa\Wechatpay\Lib\XML;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Notify.
 */
class Notify
{


	/**
	 * @var
	 */
	protected $request;

	/**
	 * Payment notify (extract from XML).
	 *
	 * @var Collection
	 */
	protected $notify;


	public function __construct(Request $request = null)
	{
		$this->request = $request ?: Request::createFromGlobals();
	}

	/**
	 * Validate the request params.
	 *
	 * @return bool
	 */
	public function isValid($key)
	{
		$localSign = generate_sign($this->getNotify()->except('sign')->all(), $key);

		return $localSign === $this->getNotify()->get('sign');
	}

	/**
	 * Return the notify body from request.
	 */
	public function getNotify()
	{
		if (!empty($this->notify)) {
			return $this->notify;
		}
		try {
			$xml = XML::parse(strval($this->request->getContent()));
		} catch (\Throwable $t) {
			throw new FaultException('Invalid request XML: '.$t->getMessage(), 400);
		} catch (\Exception $e) {
			throw new FaultException('Invalid request XML: '.$e->getMessage(), 400);
		}

		if (!is_array($xml) || empty($xml)) {
			throw new FaultException('Invalid request XML.', 400);
		}

		return $this->notify = new Collection($xml);
	}
}
