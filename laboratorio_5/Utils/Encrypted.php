<?php
declare(strict_types=1);

namespace Root\Program\Utils;


class Encrypted 
{

    public static function hashPassword(string $password): string 
    {
        return password_hash(
            $password, 
            ARGON2ID_ALGO,
            ARGON2ID_OPTIONS,
        );
    }

    public static function verifyPassword(string $password, string $hash): bool 
    {
        return password_verify($password, $hash);
    }


    // https://www.php.net/manual/en/function.hash.php
    // https://www.php.net/manual/en/ref.hash.php
    public static function hashMessageAuthenticationCodeData(string $data): string 
    {
        return hash_hmac
        (
            algo: ALGO_SHA256, 
            data: $data, 
            key:  getenv('SECRET_KEY_3')
        );
    }

    public static function hashVerifyFingerprintIP(): bool
    {
        $to_check = hash_hmac
        (
            algo: ALGO_SHA256, 
            data: $_SERVER['REMOTE_ADDR'],
            key:  getenv('SECRET_KEY_3')
        );

        return hash_equals
            ( 
                known_string: $_SESSION['fingerprint_ip'], 
                user_string: $to_check 
            );
    }

    public static function needsRehash(string $hash): bool 
    {
        return password_needs_rehash(
            $hash, 
            ARGON2ID_ALGO, 
            ARGON2ID_OPTIONS
        );
    }

    // https://www.php.net/manual/en/function.openssl-encrypt.php
    public static function securedEncrypt(string $data): string
    {
    
        $first_key = base64_decode(getenv('SECRET_KEY_1'));
        $second_key = base64_decode(getenv('SECRET_KEY_2'));


        // random bytes for encryption        
        $initialization_vector_length = 
            openssl_cipher_iv_length(
                cipher_algo: ENCRYPT_METHOD
            );
        $initialization_vector = 
            openssl_random_pseudo_bytes(
               length: $initialization_vector_length
            );

        // the encrypted data 
        $first_encrypted = openssl_encrypt(
            data: $data,
            cipher_algo: ENCRYPT_METHOD,
            passphrase: $first_key, 
            options: OPENSSL_RAW_DATA, 
            iv: $initialization_vector
        );
        
        // HMAC "fingerprint" (64 bytes)
        // Check integrity (Altered)
        // For => Tampered, Manipulado, Alterado
        // Untapered
        $second_encrypted = hash_hmac(
            algo: 'sha3-512',
            data: $first_encrypted,
            key: $second_key,
            binary: true
        );
                    
        $output = 
            base64_encode
            (
                $initialization_vector . $second_encrypted . $first_encrypted
            );

        return $output; 

    }

    public static function securedDecrypt(string $data): string
    {

        $first_key = base64_decode(getenv('SECRET_KEY_1'));
        $second_key = base64_decode(getenv('SECRET_KEY_2'));
        $mix = base64_decode($data);
                
        $initialization_vector_length = 
            openssl_cipher_iv_length(ENCRYPT_METHOD);

        $initialization_vector =
            substr
            (
                string: $mix,
                offset: 0,
                length: $initialization_vector_length
            );

        $second_encrypted = 
            substr
            (
                string: $mix,
                offset: $initialization_vector_length,
                length: 64
            );

        $first_encrypted =
            substr(
                string: $mix,
                offset: $initialization_vector_length + 64,
            );
     

        $second_encrypted_new = hash_hmac(
            algo: 'sha3-512',
            data: $first_encrypted,
            key: $second_key,
            binary: TRUE
        );

    
        if 
        (
            !hash_equals
            (
                $second_encrypted,
                $second_encrypted_new
            )
        )
        {
            return 'incorrect';
        }

        $info = 
            openssl_decrypt
            (
                $first_encrypted,
                ENCRYPT_METHOD,
                $first_key,
                OPENSSL_RAW_DATA,
                $initialization_vector
            );
 
        return $info;
    }
}
