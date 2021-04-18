# Opositatest Coding Standards
> The coding standards in the Opositates way.

It's based on the ideas of [`LIN3S/CS`](https://github.com/LIN3S/CS).

## WHY?
This package is created to centralize all the checks style of Opositatest projects, in an easy way to install all the tools
and improving the maintainability. It is a flexible and customizable solution to automatize all related with coding
standards.

* Checks if [Composer][2] json has changes, the lock must be committed too.
* Fixes the PHP code with fully customizable [PHP-CS-Fixer][3].
* Checks mess detections with [PHPMD][4].

> This library is very focused to use as pre-commit hook. The checkers only validate the files that will be committed.

## Getting started
The best recommended and suitable way to install is through [Composer][2]. Be sure the tool is installed in
your system and execute the following command:
```
$ composer require opositatest/coding-standards --dev
```
Then you have to update the `composer.json` with the following code:
```
"scripts": {
    "opos-cs-scripts": [
        "Opositatest\\CodingStandards\\Tools\\Files::addHooks",
        "Opositatest\\CodingStandards\\Tools\\Files::addFiles"
    ]
},
"extra": {
    "scripts-dev": {
        "post-install-cmd": [
            "@opos-cs-scripts"
        ],
        "post-update-cmd": [
            "@opos-cs-scripts"
        ]
    }
}
```

> REMEMBER: The `.opos_cs.yml` file is generated dynamically with Composer. The best practices recommend that only
track the `.dist` file ignoring the `.opos_cs.yml` inside `.gitignore`.

[1]: http://php.net/
[2]: https://getcomposer.org/
[3]: http://cs.sensiolabs.org/
[4]: http://phpmd.org/
