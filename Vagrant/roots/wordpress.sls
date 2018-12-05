{% set db = salt['pillar.get']('database') %}
{% set dumphostname = salt['pillar.get']('oldhostname', False) %}
{% set wpcli_ver = '1.4.1' %}

include:
  - lamp

wp:
  file.managed:
    - name: /usr/local/bin/wp
    - source: https://github.com/wp-cli/wp-cli/releases/download/v{{wpcli_ver}}/wp-cli-{{wpcli_ver}}.phar
    - source_hash: https://github.com/wp-cli/wp-cli/releases/download/v{{wpcli_ver}}/wp-cli-{{wpcli_ver}}.phar.sha512
    - mode: 755
    - show_changes: False

/home/vagrant/sync/wp-content/uploads:
  file:
    - directory
    - makedirs: True

/home/vagrant/sync/wp-config.php:
  cmd.run:
    - cwd: /home/vagrant/sync
    - user: vagrant
    - creates: /home/vagrant/sync/wp-config.php
    - name: wp core config --dbname={{db.name}} --dbuser={{db.user}} --dbpass={{db.password}} --dbhost=localhost --dbprefix={{db.prefix}} --locale=it_IT
    - require:
      - mysql_database: db
      - file: wp
      - pkg: php

{% if dumphostname and dumphostname != salt['pillar.get']('hostname') %}
wp search-replace:
  cmd.wait:
    - name: wp search-replace --network {{dumphostname}} {{salt['pillar.get']('hostname')}}
    - cwd: /home/vagrant/sync
    - user: vagrant
    - require:
      - file: wp
      - pkg: php
    - watch:
      - mysql_database: db
{% endif %}
