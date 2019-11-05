<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Util;

use InvalidArgumentException;

final class SecureUtility
{
    /**
     * @param string $password
     * @param string $data
     * @param bool $isPasswordPlainText
     * @throws InvalidArgumentException
     * @return string
     */
    public static function encryptAes256(string $password, string $data, bool $isPasswordPlainText = false): string
    {
        if ($isPasswordPlainText) {
            $password = self::sha256String32Bytes($password);
        }

        if (strlen($password) !== 32) {
            throw new InvalidArgumentException('The password must be 32 bytes in length');
        }

        $iv = self::generate128BitsIV();

        return base64_encode(
            openssl_encrypt($data, 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv)
        );
    }

    /**
     * @param string $password
     * @param string $data
     * @param bool $isPasswordPlainText
     * @throws InvalidArgumentException
     * @return string
     */
    public static function decryptAes256(string $password, string $data, bool $isPasswordPlainText = false): string
    {
        if ($isPasswordPlainText) {
            $password = self::sha256String32Bytes($password);
        }

        if (strlen($password) !== 32) {
            throw new InvalidArgumentException('The password must be 32 bytes in length');
        }

        $iv = self::generate128BitsIV();

        return openssl_decrypt(
            base64_decode($data), 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv
        );
    }

    /**
     * @param string $string
     * @return string
     */
    private static function sha256String32Bytes(string $string): string
    {
        return substr(hash('sha256', $string, true), 0, 32);
    }

    /**
     * @return string
     */
    private static function generate128BitsIV(): string
    {
        return chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
                chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
                chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
    }
}
