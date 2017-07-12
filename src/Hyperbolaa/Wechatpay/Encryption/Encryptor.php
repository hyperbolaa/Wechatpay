<?php

namespace Hyperbolaa\Wechatpay\Encryption;

use Exception;
use Hyperbolaa\Wechatpay\Exception\FaultException;

class Encryptor
{
	/**
	 * Decrypt data.
	 * @param $sessionKey
	 * @param $iv
	 * @param $encrypted
	 * @return mixed
	 * @throws FaultException
	 */
    public function decryptData($sessionKey, $iv, $encrypted)
    {
        try {
            $decrypted = openssl_decrypt(
                base64_decode($encrypted, true), 'aes-128-cbc', base64_decode($sessionKey, true),
                OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, base64_decode($iv, true)
            );
        } catch (Exception $e) {
            throw new FaultException($e->getMessage());
        }

        if (is_null($result = json_decode($this->decode($decrypted), true))) {
            throw new FaultException('ILLEGAL_BUFFER');
        }

        return $result;
    }


	/**
	 * Decode string.
	 *
	 * @param string $decrypted
	 *
	 * @return string
	 */
	public function decode($decrypted)
	{
		$pad = ord(substr($decrypted, -1));

		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}

		return substr($decrypted, 0, (strlen($decrypted) - $pad));
	}

}
