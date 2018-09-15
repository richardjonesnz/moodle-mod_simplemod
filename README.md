A template for Moodle modules.  Updated from Moodle HQ's moodle-mod_NEWMODULE template.

Added:

 - Custom renderer
 - Mustache template
 - Backup/restore functionality

Instructions for installing:
============================

Download the zip file or clone the repository into your moodle/mod folder using the instructions given under the button "Clone or download".

Assuming you are going to change your module name from wodget to something more relevant, do the following.

Rename these files:
===================
All 4 files in backup/moodle2 should have the name of your new module.

The lang/en/widget.php file should be renamed to the name of your new module.

Replace widget with your new module name
========================================
Carry out a search and replace for "widget" replacing it with the name of your new module.  You can do this in a number of ways depending on your text editor.  If you don't have one handy, download Brackets (http://brackets.io/) which is free, open source and handles this stuff well.

Navigate to your admin dashboard and install the new module.

For git users (that should be you)
==================================
You may notice the .gitignore file and a reference to a local class debugging.  This is a simple script that allows you to output debugging information to file.

It looks like this"
<?php
namespace mod_widget\local;

class debugging {
    public static function logit($message, $value) {
        global $CFG;

        $file = fopen('mylog.log', 'a');

        if ($file) {
            fwrite($file, print_r($message, true));
            fwrite($file, print_r($value, true));
            fwrite($file, "\n");
            fclose($file);
        }
    }
}

Place the above code in a file called debugging.php.

Modify the file location (mylog.log) if desired.  Anywhere you want to view the contents of an object use:

\mod_widget\local\debugging::logit("What is in a widegt: ", $widget);

Using Xdebug
============
Brackets, Sublime, PHP Storm and many other editors or IDEs use this.

Windows users
=============
Whether by choice or not, many people are stuck with MS.  Xampp is a workable development environment.  Install the basic Xampp rather than the Moodle/Xampp package.  Install Moodle under htdocs and change the existing index file if desired.

Also install, at minumum, Git for Windows (even if you don't use it - and you should - you can use the git bash command line for many tasks).

This article is helpful for installing xdebug on xampp:
https://gist.github.com/odan/1abe76d373a9cbb15bed

