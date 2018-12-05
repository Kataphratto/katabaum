{% set db = salt['pillar.get']('database') %}
{% set phpname = 'php%sw' % salt['pillar.get']('phpver', '56') %}

percona-release:
  pkgrepo.managed:
    - humanname: Percona-Release YUM repository - $basearch
    - baseurl: http://repo.percona.com/release/$releasever/RPMS/$basearch
    - gpgkey: https://www.percona.com/downloads/RPM-GPG-KEY-percona
    - gpgcheck: 1
    - require_in:
      - pkg: mysqld

mysqld:
  pkg.installed:
    - pkgs:
      - Percona-Server-server-56
      - Percona-Server-client-56
      - {{phpname}}-mysqlnd
      - {{phpname}}-pdo
    - require:
      - pkgrepo: percona-release

  service.running:
    - enable: True
    - reload: True
    - require:
      - pkg: mysqld

db_root:
  mysql_grants.present:
    - grant: all privileges
    - user: root
    - host: '%'
    - database: '*.*'
    - require:
      - service: mysqld

/home/vagrant/.my.cnf:
  file.managed:
    - user: vagrant
    - contents: |
        [mysql]
        database = {{db.name}}

        [client]
        user = {{db.user}}
        password = {{db.password}}

db:
  mysql_database.present:
    - name: {{db.name}}
    - require:
      - service: mysqld

  mysql_user.present:
    - name: {{db.user}}
    - host: localhost
    - password: {{db.password}}
    - require:
      - service: mysqld

  mysql_grants.present:
    - grant: all privileges
    - user: {{db.user}}
    - database: '{{db.name}}.*'
    - require:
      - mysql_user: db
      - mysql_database: db

  cmd.run:
    - name: |
        mysql -u root {{db.name}} < /home/vagrant/sync/Vagrant/db.sql
        mysql -u root {{db.name}} -e "UPDATE {{db.prefix}}options SET option_value='http://{{salt['pillar.get']('hostname')}}/' WHERE option_name='siteurl' OR option_name='home'"
    - unless: test "$(mysql -e 'show tables from {{db.name}}' 2>/dev/null | wc -l)" -ne 0
    - require:
      - mysql_database: db
