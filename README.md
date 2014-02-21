Symfony2-MembershipBundle
=========================

A bundle for managing a membership list.

Installation
============
to composer.json add:
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Tom-Davidson/Symfony2-MembershipBundle"
        }
    ],

and to the require:
	"tomdavidson/membershipbundle": "*"

and to /app/AppKernel.php's bundles section:
	new TomDavidson\MembershipBundle\TomDavidsonMembershipBundle(),

and to app/config/routing.yml:
TomDavidsonMembershipBundle:
    resource: "@TomDavidsonMembershipBundle/Controller/"
    type:     annotation
    prefix:   /members
