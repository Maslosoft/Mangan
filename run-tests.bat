@ECHO OFF
SET BIN_TARGET=%~dp0/vendor/phpunit/phpunit/phpunit
php "%BIN_TARGET%" --colors --bootstrap vendor/maslosoft/mangantest/bootstrap.php vendor/maslosoft/mangantest/tests
