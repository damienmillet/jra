<?php

/**
 * Core file for defining the Auth class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

use Core\Clock\Clock;

/**
 * Class Auth
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Auth
{
    /**
     * Generates a JWT token.
     *
     * @param array $data The data to be encoded in the token.
     *
     * @return string The generated JWT token.
     */
    public static function generateToken($data)
    {
        $config = Config::getInstance();
        $key    = $config->get('TOKEN_KEY', 'TOKEN_KEY');

        $header           = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload          = json_encode($data);
        $base64UrlHeader  = self::_base64UrlEncode($header);
        $base64UrlPayload = self::_base64UrlEncode($payload);
        $dataToSign       = "$base64UrlHeader.$base64UrlPayload";
        $rawSignature     = hash_hmac('sha256', $dataToSign, $key, true);
        $signature        = self::_base64UrlEncode($rawSignature);

        return "$base64UrlHeader.$base64UrlPayload.$signature";
    }


    // /**
    //  * Verifies a JWT token.
    //  *
    //  * @param string $token The JWT token to be verified.
    //  *
    //  * @return boolean True if the token is valid, false otherwise.
    //  */
    // public static function verifyToken($token)
    // {
    //     $config = Config::getInstance();
    //     $key    = $config->get('TOKEN_KEY', 'TOKEN_KEY');

    //     list($header, $payload, $signature) = explode('.', $token);
    //     $dataToSign     = "$header.$payload";
    //     $rawSignature   = hash_hmac('sha256', $dataToSign, $key, true);
    //     $validSignature = self::_base64UrlEncode($rawSignature);
    //     return hash_equals($validSignature, $signature);
    // }

    /**
     * Verifies and decodes a JWT token.
     *
     * @param string $token The JWT token to be verified and decoded.
     *
     * @return array The decoded payload if the token is valid,
     *                     error otherwise.
     */
    public static function verifyAndDecodeToken(string $token)
    {
        $config = Config::getInstance();
        $key    = $config->get('TOKEN_KEY', 'TOKEN_KEY');

        // Split the token into its components
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return ['error' => 'Invalid token'];
            // Invalid token format
        }

        list($header, $payload, $signature) = $parts;

        // Decode header and payload
        $decodedHeader  = json_decode(base64_decode($header), true);
        $decodedPayload = json_decode(base64_decode($payload), true);

        if (!$decodedHeader || !$decodedPayload) {
            return ['error' => 'Invalid token'];
            // Invalid JSON in header or payload
        }

        // Verify the signature
        $dataToSign     = "$header.$payload";
        $rawSignature   = hash_hmac('sha256', $dataToSign, $key, true);
        $validSignature = self::_base64UrlEncode($rawSignature);

        if (!hash_equals($validSignature, $signature)) {
            return ['error' => 'Invalid token'];
            // Signature does not match
        }

        $time = (new Clock())->now()->getTimestamp();

        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < $time
        ) {
            return ['error' => 'Expired token'];
            // Token has expired
        }

        if (!isset($decodedPayload['exp'])
            || !isset($decodedPayload['id'])
            || !isset($decodedPayload['name'])
            || !isset($decodedPayload['roles'])
        ) {
            return ['error' => 'Invalid token'];
            // Invalid payload
        }

        return ['payload' => $decodedPayload];
        // Return the payload if valid
    }

    /**
     * Verifies the JWT token's signature and validity.
     *
     * @param string $token The JWT token to verify.
     *
     * @return array An array containing either the valid payload or an error message.
     */
    public static function verifyToken(string $token): array
    {
        // Decode the token
        $decoded = self::decodeToken($token);
        if (isset($decoded['error'])) {
            return $decoded; // Return the decoding error
        }

        $config = Config::getInstance();
        $key    = $config->get('TOKEN_KEY', 'TOKEN_KEY');

        $header    = base64_encode(json_encode($decoded['header']));
        $payload   = base64_encode(json_encode($decoded['payload']));
        $signature = $decoded['signature'];

        // Verify the signature
        $dataToSign     = "$header.$payload";
        $rawSignature   = hash_hmac('sha256', $dataToSign, $key, true);
        $validSignature = self::_base64UrlEncode($rawSignature);

        if (!hash_equals($validSignature, $signature)) {
            return ['error' => 'Invalid token'];
            // Signature does not match
        }

        $time = (new Clock())->now()->getTimestamp();

        if (isset($decoded['payload']['exp']) 
            && $decoded['payload']['exp'] < $time
        ) {
            return ['error' => 'Expired token'];
            // Token has expired
        }

        if (!isset($decoded['payload']['exp'])
            || !isset($decoded['payload']['id'])
            || !isset($decoded['payload']['name'])
            || !isset($decoded['payload']['roles'])
        ) {
            return ['error' => 'Invalid token'];
            // Missing required fields in the payload
        }

        return ['payload' => $decoded['payload']];
        // Return the valid payload
    }

    /**
     * Decodes the JWT token.
     *
     * @param string $token The JWT token to decode.
     *
     * @return array The decoded components (header, payload) if successful,
     *               or an error array if invalid.
     */
    public static function decodeToken(string $token): array
    {
        // Split the token into its components
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return ['error' => 'Invalid token'];
            // Invalid token format
        }

        list($header, $payload, $signature) = $parts;

        // Decode header and payload
        $decodedHeader  = json_decode(base64_decode($header), true);
        $decodedPayload = json_decode(base64_decode($payload), true);

        if (!$decodedHeader || !$decodedPayload) {
            return ['error' => 'Invalid token'];
            // Invalid JSON in header or payload
        }

        return [
            'header'    => $decodedHeader,
            'payload'   => $decodedPayload,
            'signature' => $signature,
        ];
    }

    /**
     * Encodes data with Base64 URL encoding.
     *
     * @param string $data The data to be encoded.
     *
     * @return string The Base64 URL encoded data.
     */
    private static function _base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }


    /**
     * Verifies a password against a hash.
     *
     * @param string $password The password to verify.
     * @param string $hash     The hash to verify against.
     *
     * @return boolean True if the password matches the hash, false otherwise.
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }


    /**
     * Verify the CSRF token.
     *
     * @param string $csrfToken The CSRF token to verify.
     *
     * @return boolean
     */
    public static function verifyCsrfToken(string $csrfToken): bool
    {
        $storedToken = $_SESSION['csrf_token'] ?? null;
        // Exemple avec session
        return hash_equals($storedToken, $csrfToken);
    }


    /**
     * Verifies the origin of the request.
     *
     * @return boolean
     */
    public static function verifyRequestOrigin(): bool
    {
        $config         = Config::getInstance();
        $allowedOrigins = $config->get('ALLOWED_ORIGIN', default: '*');

        if (!empty($allowedOrigins)) {
            $origin = ($_SERVER['HTTP_ORIGIN'] ?? '');

            if (!in_array($origin, $allowedOrigins)) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'Invalid origin']);
                exit;
            }
        }

        return true;
    }
}
