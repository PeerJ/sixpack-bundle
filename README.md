sixpack-bundle
===========

Symfony2 wrapper around sixpack-php

This is not ready yet for public use.

Currently assumes that there will be 2 clients, 1 for anonymous and 1 for users.
Sixpack stats will be much more accurate if you use the user client for cases when you know there is a user.
 * Highly recommended that you use a naming standard to easily identify which case is which
Anon client conversion will convert all clients_id associated with a user.
Listener will automatically associate a client_id with a user on login.

Outstanding issues:
* Should be option to remove assocation of user when logout occurs (multiple users on 1 computer) + conflict option
  to remove assocation if another user logins in with same client_id
* Currently its hardcode to join to PeerJ FOS User Entity class - should be parameterized
* Entity class annonations should be migrated to xml/yml config file to remove annonation dependency
* Remove dependency on Doctrine Mysql (orm)
* PeerJSixpackClient is PeerJ Specific and should be moved to internal class
* PeerJ has a controller wrapper to call sixpack (for use with ssl sites) that should be added to bundle
  * controller should also have ability to work with the sixpack.js client to handle anon client conversions
* Make possible for use of bundle without Doctrine (just a basic sixpack client)
* Improve documentation (include doctrine migrations)


Assumes:
* FOSUserBundle
* Doctrine

Usage:
    * Participate
        $sixpack_client = $this->get('sixpack.client.anon');
        $option = $sixpack_client->participate('LazyRegText', array('Signup', 'Create'))->getAlternative();

    * Convert
        $sixpack_client = $this->get('sixpack.client.anon');
        $sixpack_client->convert('LazyRegText');


Installation
---
composer.json
---
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

config.yml
--
(may not be required)
doctrine:
    orm:
        entity_managers:
            default:
                mappings:
                    PeerjSixPackBundle: ~

peerj_six_pack:
    defaultClient: anon
    clients:
        - { name: anon, baseUrl: %sixpack_url%, cookiePrefix: sixpack, timeout: 500 }
        - { name: user, baseUrl: %sixpack_url%, cookiePrefix: user, timeout: 500, isUser: true }

AppKernel.php
--
        $bundles = array(
                ....
                new Peerj\Bundle\SixPackBundle\PeerjSixPackBundle(),
                ....
                )
                

will also need to run app/console doctrine:migrations:diff
