<?php

namespace RavuAlHemio\UserFriendlyPassword;


use RavuAlHemio\UserFriendlyPassword\VerificationResult;


/**
 * Hashes passwords in a user-friendly way.
 *
 * A user experience (UX) issue with standard password authentication implementations is that a
 * single typo makes the whole password invalid. This hasher improves upon password UX by
 * immediately detecting a typo while remaining secure by using state-of-the-art password hashing
 * algorithms.
 */
class PasswordHasher {
    /**
     * Hashes the given password, returning its hash.
     *
     * @param string $password The password to hash.
     * @return string The hashed password.
     */
    public static function hash(string $password): string {
        // hash using Argon2id
        // (currently recommended by OWASP: https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

        $hashes = [];
        for ($i = 1; $i <= \strlen($password); $i++) {
            $subpassword = \substr($password, 0, $i);
            $subhash = \password_hash($subpassword, PASSWORD_ARGON2ID);
            $hashes[] = $subhash;
        }
        return \implode(":", $hashes);
    }

    /**
     * Verifies if the given password matches the given hash.
     *
     * @param string $password The password to verify against the hash.
     * @param string $hash The hash against which to verify the password.
     * @return VerificationResult Correct if the password is correct, Incorrect if the password is
     * incorrect, and Continue if the password is not yet complete but, until now, correct.
     */
    public static function verify(string $password, string $hash): VerificationResult {
        if (\strlen($password) === 0) {
            // an empty password is always a good start
            return VerificationResult::Continue;
        }

        $hashes = \explode(":", $hash);
        if (\strlen($password) > \count($hashes)) {
            // this cannot be the password, it is too long
            return VerificationResult::Incorrect;
        }
        $myHash = $hashes[\strlen($password) - 1];

        if (\password_verify($password, $myHash)) {
            if (\strlen($password) === \count($hashes)) {
                // this is the full password
                return VerificationResult::Correct;
            } else {
                // the password is not yet complete
                return VerificationResult::Continue;
            }
        } else {
            return VerificationResult::Incorrect;
        }
    }
}
