Installation on Dev server
==========================

Installation on Debian 9
------------------------

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
``sudo apt-get install -y git openjdk-8-jre bash-completion mc python-docutils``


Install web server
~~~~~~~~~~~~~~~~~~
``sudo apt-get install -y apache2 && sudo /usr/sbin/a2enmod rewrite``


Install PHP 7.0
~~~~~~~~~~~~~~~
Install php 7.0 using command ``sudo apt-get install -y php7.0 php7.0-cli php7.0-intl php7.0-xdebug php7.0-mysqlnd php7.0-xml php7.0-mbstring php7.0-zip``. Create and enable ``common.ini``:

  ::

    echo "; priority=99" | sudo tee /etc/php/7.0/mods-available/common.ini > /dev/null
    echo "date.timezone=Europe/Minsk" | sudo tee -a /etc/php/7.0/mods-available/common.ini > /dev/null
    echo "short_open_tag=0" | sudo tee -a /etc/php/7.0/mods-available/common.ini > /dev/null
    echo "xdebug.max_nesting_level=250" | sudo tee -a /etc/php/7.0/mods-available/xdebug.ini > /dev/null
    echo "xdebug.var_display_max_depth=5" | sudo tee -a /etc/php/7.0/mods-available/xdebug.ini > /dev/null
    sudo phpenmod common


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
    echo "Cmnd_Alias SERVICE_CMDS = /usr/sbin/service, /usr/sbin/a2ensite, /usr/bin/tail, /bin/cat" | sudo tee -a /etc/sudoers.d/developers > /dev/null
    echo "DEVELOPERS ALL=NOPASSWD: SERVICE_CMDS" | sudo tee -a /etc/sudoers.d/developers > /dev/null


Install MySQL
~~~~~~~~~~~~~
Install mysql 5.5 using command ``sudo apt-get install -y mysql-client mysql-server``. Set ``root`` as password for ``root`` account. Update ``my.cnf`` and restart mysql

  ::

    echo "[mysqld]" | sudo tee /etc/mysql/my.cnf > /dev/null
    echo "character_set_server              = cp1251" | sudo tee -a /etc/mysql/my.cnf > /dev/null
    echo "default_storage_engine            = MyISAM" | sudo tee -a /etc/mysql/my.cnf > /dev/null
    echo "[mysql]" | sudo tee -a /etc/mysql/my.cnf > /dev/null
    echo "default-character-set             = cp1251" | sudo tee -a /etc/mysql/my.cnf > /dev/null
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
    sudo ln -s /home/$USERNAME/www/phpofby/phpofby.apache /etc/apache2/sites-available/phpofby_$USERNAME.conf
    sudo sudo a2ensite phpofby_$USERNAME
    sudo service apache2 restart

