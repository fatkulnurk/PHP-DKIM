<?php

/**
 * DKIM is used to sign e-mails. If you change your RSA key, apply modifications to
 * the DNS DKIM record of the mailing (sub)domain too !
 * Disclaimer : the php openssl extension can be buggy with Windows, try with Linux first
 * 
 * To generate a new private key with Linux :
 * openssl genrsa -des3 -out private.pem 1024
 * Then get the public key
 * openssl rsa -in private.pem -out public.pem -outform PEM -pubout
 */

// Edit with your own info :

define('MAIL_RSA_PASSPHRASE', '');

define('MAIL_RSA_PRIV',
'-----BEGIN RSA PRIVATE KEY-----
c+KCpVQb7Z5VBrwJ3pITAmn+Q3HmXDR9y7y+Zp68O44CbbiH2Z4
...
c+KCpVQb7Z5VBrwJ3pITAmn+Q3HmXDR9y7y+Zp68O44CbbiH2Z4
-----END RSA PRIVATE KEY-----');

define('MAIL_RSA_PUBL','');

// Domain or subdomain of the signing entity (i.e. the domain where the e-mail comes from)
define('MAIL_DOMAIN', 'qflash.pl');  

// Allowed user, defaults is "@<MAIL_DKIM_DOMAIN>", meaning anybody in the MAIL_DKIM_DOMAIN
// domain. Ex: 'admin@mydomain.tld'. You'll never have to use this unless you do not
// control the "From" value in the e-mails you send.
define('MAIL_IDENTITY', NULL);

// Selector used in your DKIM DNS record, e.g. : selector._domainkey.MAIL_DKIM_DOMAIN
define('MAIL_SELECTOR', 'all');
