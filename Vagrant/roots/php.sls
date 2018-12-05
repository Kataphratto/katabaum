{% set phpname = 'php%sw' % salt['pillar.get']('phpver', '56') %}

webtatic:
  pkgrepo.managed:
    - humanname: Webtatic Repository EL$releasever - $basearch
    - mirrorlist: https://mirror.webtatic.com/yum/el$releasever/$basearch/mirrorlist
    - gpgcheck: 1
    - gpgkey: https://repo.webtatic.com/yum/RPM-GPG-KEY-webtatic-el$releasever

php:
  pkg.installed:
    - pkgs:
      - {{phpname}}-cli
      - {{phpname}}-devel
      - {{phpname}}-gd
      - {{phpname}}-intl
      - {{phpname}}-mbstring
      - {{phpname}}-mcrypt
      - {{phpname}}-pdo
      - {{phpname}}-pecl-xdebug
      - {{phpname}}-pspell
      - {{phpname}}-soap
      - {{phpname}}-opcache
      - {{phpname}}-xml
      - {{phpname}}-xmlrpc
    - require:
      - pkgrepo: webtatic

php.ini-memory_limit:
  file.replace:
    - name: /etc/php.ini
    - pattern: ^;?\s?memory_limit\s*=\s*128M *$
    - repl: memory_limit = 512M
    - require:
      - pkg: php

php /home/vagrant/sync/Vagrant/composer-installer --install-dir=/usr/local/bin --filename=composer:
  cmd.run:
    - creates: /usr/local/bin/composer
    - require:
      - pkg: php

/etc/php.d/xdebug.ini:
  file.managed:
    - require:
      - pkg: php
    - contents: |
        ; Enable xdebug extension module
        zend_extension=/usr/lib64/php/modules/xdebug.so
        xdebug.remote_enable=1
        xdebug.remote_host={{ salt['grains.get']('dns:nameservers:0') }}
        xdebug.remote_connect_back=1
        xdebug.remote_port=9000
        xdebug.remote_handler=dbgp
        xdebug.remote_mode=req
        xdebug.remote_autostart=true
