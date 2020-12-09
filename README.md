# simplesamlphp-module-webauthn

## Installation

```
cd /var/simplesamlphp/modules
git clone https://github.com/CESNET/simplesamlphp-module-webauthn.git webauthn
```

## Example of config

```
50 => [
    'class' => 'webauthn:WebAuthn',
    'redirect_url' => 'https://flask.example.com/webauthn/authentication_request',
    'api_url' => 'https://flask.example.com/webauthn/request',
    'signing_key' => '/var/webauthn_private.pem',
    'user_id' => 'uid',
],
```
