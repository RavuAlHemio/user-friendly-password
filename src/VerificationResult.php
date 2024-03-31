<?php

namespace RavuAlHemio\UserFriendlyPassword;

/**
 * The result of verifying a password.
 */
enum VerificationResult {
    /** The password is correct. */
    case Correct;

    /** The password is incorrect. */
    case Incorrect;

    /** The start of the password is correct but more characters are needed. */
    case Continue;
}
