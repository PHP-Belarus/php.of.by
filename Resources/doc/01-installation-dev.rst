Installation on Dev server
==========================

Installation on Debian 10
-------------------------

Install sudo, in case in required
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Install sudo by issuing command ``apt-get install -y sudo``.
Log in as root. Create account for performing administrative tasks.
Allow new user to perform all commands as root without password:

  ::

    echo "<username> ALL=(ALL) NOPASSWD:ALL" | tee -a /etc/sudoers.d/<username> > /dev/null

``<username>`` stands for login name of new user.


Log in as newly created user

Install common packages
~~~~~~~~~~~~~~~~~~~~~~~
``sudo apt-get install -y git bash-completion mc python-docutils lsb-release curl``


Install web server
~~~~~~~~~~~~~~~~~~
Add nginx repo key using ``wget http://nginx.org/keys/nginx_signing.key && sudo apt-key add nginx_signing.key``.

Add nginx repos:

  ::

    echo "deb http://nginx.org/packages/debian/ $(lsb_release -sc) nginx" | sudo tee -a /etc/apt/sources.list.d/nginx.list > /dev/null
    echo "deb-src http://nginx.org/packages/debian/ $(lsb_release -sc) nginx" | sudo tee -a /etc/apt/sources.list.d/nginx.list > /dev/null


Install nginx via ``sudo apt-get update && sudo apt-get install -y nginx``


Install PHP 7.4
~~~~~~~~~~~~~~~
Add repository for php 7.4:

  ::

    sudo apt-get -y install -y apt-transport-https ca-certificates
    sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
    sudo sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'


Install required php modules ``sudo apt-get update && sudo apt-get install -y php7.4 php7.4-cli php7.4-intl php7.4-xdebug php7.4-mysqlnd php7.4-xml php7.4-mbstring php7.4-zip php7.4-fpm php7.4-curl`` Create and enable ``common.ini``:

  ::

    echo "; priority=99" | sudo tee /etc/php/7.4/mods-available/common.ini > /dev/null
    echo "date.timezone=Europe/Minsk" | sudo tee -a /etc/php/7.4/mods-available/common.ini > /dev/null
    echo "short_open_tag=0" | sudo tee -a /etc/php/7.4/mods-available/common.ini > /dev/null
    echo "xdebug.max_nesting_level=250" | sudo tee -a /etc/php/7.4/mods-available/xdebug.ini > /dev/null
    echo "xdebug.var_display_max_depth=5" | sudo tee -a /etc/php/7.4/mods-available/xdebug.ini > /dev/null
    sudo phpenmod common


Open php fpm config file ``sudo mcedit /etc/php/7.4/fpm/pool.d/www.conf``, find ``listen = /run/php/php7.4-fpm.sock`` and replace with ``listen = 127.0.0.1:9000``.

Restart ``php-fpm`` using command ``sudo service php7.4-fpm restart``.


Install node.js and Yarn
~~~~~~~~~~~~~~~~~~~~~~~~
Here we are using version 12 of Node.js.
We need to add repo via ``curl -sL https://deb.nodesource.com/setup_12.x | sudo -E bash -``. After that install node via ``sudo apt-get install -y nodejs``.

Install Yarn:

    ::

      curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
      echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
      sudo apt-get update && sudo apt-get install yarn


Samba
~~~~~
Install samba using command ``sudo apt-get install -y samba``.
Edit samba config ``sudo mcedit /etc/samba/smb.conf``

  ::

    [global]
    workgroup = PHPOFBY
    security = user
    unix password sync = yes
    [homes]
    comment = Home Directories
    valid users = @www-data
    force group = www-data
    read only = No
    create mask = 0644
    force create mode = 0644
    directory mask = 0775
    force directory mode = 0775
    directory security mask = 0775
    force directory security mode = 0775
    nt acl support = No
    map archive = No
    browseable = No

``sudo service smbd restart``


Sudo for developer account
~~~~~~~~~~~~~~~~~~~~~~~~~~

  ::

    echo "User_Alias DEVELOPERS = %www-data" | sudo tee -a /etc/sudoers.d/developers > /dev/null
    echo "Cmnd_Alias SERVICE_CMDS = /usr/sbin/service, /usr/bin/tail, /bin/cat" | sudo tee -a /etc/sudoers.d/developers > /dev/null
    echo "DEVELOPERS ALL=NOPASSWD: SERVICE_CMDS" | sudo tee -a /etc/sudoers.d/developers > /dev/null


Install MySQL
~~~~~~~~~~~~~
Install mysql 5.5 using command ``sudo apt-get install -y default-mysql-client default-mysql-server``. Set ``root`` as password for ``root`` account. Update ``my.cnf`` and restart mysql

  ::

    echo "[mysqld]" | sudo tee /etc/mysql/mariadb.conf.d/51-mysql.cnf > /dev/null
    echo "character-set-server              = cp1251" | sudo tee -a /etc/mysql/mariadb.conf.d/51-mysql.cnf > /dev/null
    echo "collation-server                  = cp1251_general_ci" | sudo tee -a /etc/mysql/mariadb.conf.d/51-mysql.cnf > /dev/null
    echo "default_storage_engine            = MyISAM" | sudo tee -a /etc/mysql/mariadb.conf.d/51-mysql.cnf > /dev/null
    echo "[mysql]" | sudo tee -a /etc/mysql/mariadb.conf.d//51-mysql.cnf > /dev/null
    echo "default-character-set             = cp1251" | sudo tee -a /etc/mysql/mariadb.conf.d/51-mysql.cnf > /dev/null
    sudo service mysql restart


Developers accounts
~~~~~~~~~~~~~~~~~~~

  ::

    USERNAME=<username>
    sudo adduser --ingroup www-data $USERNAME
    sudo smbpasswd -a $USERNAME
    sudo smbpasswd -e $USERNAME
    sudo service smbd restart
    mysql -uroot -proot -e "create database phpofby_$USERNAME DEFAULT CHARACTER SET utf8 ;\
        grant all on phpofby_$USERNAME.* to 'symfony'@'localhost' identified by 'symfony';\
        grant all on phpofby_$USERNAME.* to 'symfony'@'%' identified by 'symfony';"
    sudo ln -s /home/$USERNAME/www/<sitename>/<sitename>.nginx /etc/nginx/conf.d/<sitename>_$USERNAME.conf
    sudo service nginx restart

