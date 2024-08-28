<?php

namespace Metracore\Helper;

use function password_hash;
use function password_verify;
use function random_bytes;
use function bin2hex;
use function hash_hmac;
use function hash;
use function preg_match;
use function strlen;
use function password_needs_rehash;
use function hash_pbkdf2;
use function hash_equals;
use function openssl_encrypt;
use function openssl_decrypt;
use function openssl_cipher_iv_length;
use function base64_encode;
use function base64_decode;
use function substr;
use function openssl_random_pseudo_bytes;

/**
 * Hash class for nitrovel framework helper component
 * Development Date : Aug 29, 2024
 */
class Hash {

    private $defaultAlgorithm = 'aes-256-cbc';


    /*
    | Generate a secure hash for a given string using the bcrypt algorithm.
    |
    | @param string $input
    | @return string
    */
    public function generateHash($input) {
        return password_hash($input, PASSWORD_BCRYPT);
    }


    /*
    | Verify if the given hash matches the input string.
    |
    | @param string $input
    | @param string $hash
    | @return bool
    */
    public function verifyHash($input, $hash) {
        return password_verify($input, $hash);
    }


    /*
    | Generate a random token using a cryptographic secure method.
    |
    | @param int $length
    | @return string
    | @throws Exception
    */
    public function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }


    /*
    | Create a HMAC (Hash-based Message Authentication Code).
    |
    | @param string $data
    | @param string $key
    | @param string $algo
    | @return string
    */
    public function createHMAC($data, $key, $algo = 'sha256') {
        return hash_hmac($algo, $data, $key);
    }


    /*
    | Generate a hash using a specified algorithm.
    |
    | @param string $input
    | @param string $algo
    | @return string
    */
    public function generateHashWithAlgo($input, $algo = 'sha256') {
        return hash($algo, $input);
    }


    /*
    | Check if the given string is a valid hash.
    |
    | @param string $hash
    | @param string $algo
    | @return bool
    */
    public function isValidHash($hash, $algo = 'sha256') {
        $expectedLength = strlen(hash($algo, '', false));
        return (bool) preg_match('/^[a-f0-9]{' . $expectedLength . '}$/', $hash);
    }


    /*
    | Generate a secure hash for a password with a custom cost.
    |
    | @param string $password
    | @param int $cost
    | @return string
    */
    public function generatePasswordHash($password, $cost = 10) {
        $options = ['cost' => $cost];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }


    /*
    | Rehash a password if the cost parameter changes.
    |
    | @param string $hash
    | @param int $cost
    | @return bool
    */
    public function needsRehash($hash, $cost = 10) {
        $options = ['cost' => $cost];
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $options);
    }


    /*
    | Generate a random salt for hashing.
    |
    | @param int $length
    | @return string
    | @throws Exception
    */
    public function generateSalt($length = 16) {
        return bin2hex(random_bytes($length / 2));
    }


    /*
    | Generate a keyed hash (e.g., HMAC) using the PBKDF2 algorithm.
    |
    | @param string $password
    | @param string $salt
    | @param int $iterations
    | @param int $length
    | @param string $algo
    | @return string
    */
    public function pbkdf2($password, $salt, $iterations = 1000, $length = 64, $algo = 'sha256') {
        return hash_pbkdf2($algo, $password, $salt, $iterations, $length, false);
    }


    /*
    | Validate a token by comparing it with a stored hash.
    |
    | @param string $token
    | @param string $hash
    | @return bool
    */
    public function validateToken($token, $hash) {
        return hash_equals($hash, hash('sha256', $token));
    }


    /*
    | Encrypt a string using a secure key with an optional cipher algorithm.
    |
    | @param string $plaintext
    | @param string $key
    | @param string $algorithm
    | @return string
    | @throws Exception
    */
    public function encrypt($plaintext, $key, $algorithm = null) {
        $algorithm = $algorithm ?: $this->defaultAlgorithm;
        $iv = random_bytes(openssl_cipher_iv_length($algorithm));
        $ciphertext = openssl_encrypt($plaintext, $algorithm, $key, 0, $iv);
        return base64_encode($iv . $ciphertext);
    }


    /*
    | Decrypt a string using a secure key with an optional cipher algorithm.
    |
    | @param string $ciphertext
    | @param string $key
    | @param string $algorithm
    | @return string
    | @throws Exception
    */
    public function decrypt($ciphertext, $key, $algorithm = null) {
        $algorithm = $algorithm ?: $this->defaultAlgorithm;
        $ciphertext = base64_decode($ciphertext);
        $ivLength = openssl_cipher_iv_length($algorithm);
        $iv = substr($ciphertext, 0, $ivLength);
        $ciphertext = substr($ciphertext, $ivLength);
        return openssl_decrypt($ciphertext, $algorithm, $key, 0, $iv);
    }


    /*
    | Securely compare two strings to prevent timing attacks.
    |
    | @param string $str1
    | @param string $str2
    | @return bool
    */
    public function secureCompare($str1, $str2) {
        return hash_equals($str1, $str2);
    }


    /*
    | Generate a secure hash using Argon2id algorithm.
    |
    | @param string $input
    | @return string
    */
    public function generateArgon2idHash($input) {
        return password_hash($input, PASSWORD_ARGON2ID);
    }


    /*
    | Generate a random token and store its hash for secure validation.
    |
    | @param int $length
    | @param string $algo
    | @return array [token, tokenHash]
    | @throws Exception
    */
    public function generateAndHashToken($length = 32, $algo = 'sha256') {
        $token = bin2hex(random_bytes($length));
        $tokenHash = hash($algo, $token);
        return ['token' => $token, 'tokenHash' => $tokenHash];
    }


    /*
    | Derive a key using a password and a salt with Argon2id algorithm.
    |
    | @param string $password
    | @param string $salt
    | @param int $memoryCost
    | @param int $timeCost
    | @param int $threads
    | @return string
    */
    public function deriveKey($password, $salt, $memoryCost = 1024, $timeCost = 2, $threads = 2) {
        return sodium_crypto_pwhash(
            32,
            $password,
            $salt,
            $timeCost,
            $memoryCost * 1024,
            $threads
        );
    }


    /*
    | Generate a cryptographically secure pseudorandom string of a given length.
    |
    | @param int $length
    | @return string
    | @throws Exception
    */
    public function generateRandomString($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }


    /*
    | Encrypt data using AES-256-GCM for authenticated encryption.
    |
    | @param string $plaintext
    | @param string $key
    | @param string $aad Additional authenticated data
    | @return string
    | @throws Exception
    */
    public function encryptAuthenticated($plaintext, $key, $aad = '') {
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-gcm'));
        $tag = '';
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, 0, $iv, $tag, $aad);
        return base64_encode($iv . $tag . $ciphertext);
    }


    /*
    | Decrypt data encrypted with AES-256-GCM for authenticated encryption.
    |
    | @param string $ciphertext
    | @param string $key
    | @param string $aad Additional authenticated data
    | @return string
    | @throws Exception
    */
    public function decryptAuthenticated($ciphertext, $key, $aad = '') {
        $ciphertext = base64_decode($ciphertext);
        $ivLength = openssl_cipher_iv_length('aes-256-gcm');
        $iv = substr($ciphertext, 0, $ivLength);
        $tag = substr($ciphertext, $ivLength, 16);
        $ciphertext = substr($ciphertext, $ivLength + 16);
        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, 0, $iv, $tag, $aad);
    }
}

?>
