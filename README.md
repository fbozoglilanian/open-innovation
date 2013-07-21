ZendSkeletonApplication
=======================

Introduction
------------
Daedalus is an open innovation tool based on Design Thinking process.

The goal is to create five tools:

 * Create: a tool for find and design challenges and it's solutions.
 * I+D: investigate and develop prototypes for the design ideas.
 * Test: a tool to let the users improve the solution.
 * Produce: this tool will allow to produce and sell the innovative products.


Installation
------------
GIT
------------
git clone https://fabian.bozoglian@code.google.com/p/open-innovation/ 

Virtual Host
------------
<VirtualHost *:80>
    ServerName daedalus.local
    DocumentRoot "path/to/daedalus/public"
    SetEnv APPLICATION_ENV "development"
    <Directory "path/to/daedalus/public">
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
