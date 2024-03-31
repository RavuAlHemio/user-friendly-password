<?php

require("PasswordHasher.php");
require("VerificationResult.php");

use RavuAlHemio\UserFriendlyPassword\PasswordHasher;
use RavuAlHemio\UserFriendlyPassword\VerificationResult;


// this is the hash of the password "hunter2"
// do not leave comments like this in your actual application though!
$hash = '$argon2id$v=19$m=65536,t=4,p=1$SldldU1aMlRFUEQ0QzVkLg$1PA4f6JzpNoORjZ6UF6X9E3GMJ97PB0C7CSWvF/K4gE:$argon2id$v=19$m=65536,t=4,p=1$VE1oM1A4dnd3NDVhNDlLNA$MrGe6WDO3ayT4L+WNc4ECSjyp+igzf0zt6L7P6UF5wI:$argon2id$v=19$m=65536,t=4,p=1$UEVjWGg2Z1Z6dC41YnNuVA$7y26Oy/1QZ4EuP2JDljyRDcfVL/+ImTzID8Y3fHWk7c:$argon2id$v=19$m=65536,t=4,p=1$UjNiMHUzRXhTdUw5V016NQ$KDzoDNzxcmUMV03ctPSgS4F8t+A1QEXHuYV472mOo+c:$argon2id$v=19$m=65536,t=4,p=1$TUFtNEdBUjRYL0RVNXdZOQ$odbtfVnPLHlHhsD2PnoA1CL52qFxosOpcZ8UhmuMyb8:$argon2id$v=19$m=65536,t=4,p=1$ODN3bDFWaVN2cGRLSHNiSA$IzDbNaW9HBCLIFjOnjYXLJUmWpiUoP+oSc+BA1zHTAU:$argon2id$v=19$m=65536,t=4,p=1$emwuMHVlQnBralhldkxxdw$UufZ5MRSfbgqitmDUqC5UMPxL3Zy7mt6qAtMzZ5cjGA';

if (\array_key_exists("do", $_GET) && $_GET['do'] === 'verify') {
    header("Content-Type: text/plain; charset=us-ascii");
    $password = (\array_key_exists("password", $_GET) ? $_GET["password"] : "");
    $result = PasswordHasher::verify($password, $hash);
    if ($result === VerificationResult::Correct) {
        echo "C";
    } else if ($result === VerificationResult::Incorrect) {
        echo "I";
    } else if ($result === VerificationResult::Continue) {
        echo ".";
    }
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>User-Friendly Password</title>
<script type="text/javascript">
// <[CDATA[
document.addEventListener("DOMContentLoaded", function () {
    var pwinput = document.getElementById("pwinput");
    pwinput.addEventListener("input", function () {
        var indicator = document.getElementById("status-indicator");
        indicator.textContent = "gimme a sec...";
        fetch("?do=verify&password=" + encodeURIComponent(pwinput.value)).then(function (response) {
            return response.text();
        }).then(function (text) {
            if (text === "C") {
                indicator.textContent = "correct!";
            } else if (text === "I") {
                indicator.textContent = "wrong!";
            } else if (text === ".") {
                indicator.textContent = "keep typing...";
            } else {
                indicator.textContent = "?!";
            }
        });
    });
});
// ]]>
</script>
</head>
<body>
    <p><input type="password" id="pwinput" /></p>
    <p><span id="status-indicator"></span></p>
</body>
</html>
