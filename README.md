sixpack-bundle
===========

Symfony2 wrapper around sixpack-php

This is not ready yet for public use.

Currently assumes that there will be 2 clients, 1 for anonymous and 1 for users.
Sixpack stats will be much more accurate if you use the user client for cases when you know there is a user.
 **Highly recommended that you use a naming standard to easily identify which case is which**
Anon client conversion will convert all clients_id associated with a user.
Listener will automatically associate a client_id with a user on login.


Assumes:
---
* FOSUserBundle
* Doctrine

Usage:
---
    * Participate
        ```
        $sixpack_client = $this->get('sixpack.client.anon');
        $option = $sixpack_client->participate('LazyRegText', array('Signup', 'Create'))->getAlternative();
        ...
        $sixpack_client->setCookie($response);
        ```

    * Convert
        ```
        $sixpack_client = $this->get('sixpack.client.anon');
        $sixpack_client->convert('LazyRegText');
        ```


Installation
---
composer.json
---
```
"require": {
    ...
    "peerj/sixpack-bundle": "dev-master",
    ...
}

"repositories": [
    ...
    {
        "type": "git",
        "url": "https://github.com/PeerJ/sixpack-bundle.git"
    },
    ...
]
```

config.yml
--
```
peerj_six_pack:
    defaultClient: anon
    clients:
        - { name: anon, baseUrl: %sixpack_url%, cookiePrefix: sixpack, timeout: 500 }
        - { name: user, baseUrl: %sixpack_url%, cookiePrefix: user, timeout: 500, isUser: true }
    userClass: Acme\UserBundle\Entity\User
```

AppKernel.php
--
```
$bundles = array(
    ...
    new Peerj\Bundle\SixPackBundle\PeerjSixPackBundle(),
    ...
)
```

will also need to run ```app/console doctrine:migrations:diff```
