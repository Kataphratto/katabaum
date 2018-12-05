{% set phpname = 'php%sw' % salt['pillar.get']('phpver', '56') %}

include:
  - php
  - mysql

httpd:
  pkg.installed:
    - pkgs:
      - httpd
{%- if salt['pillar.get']('phpver', '56')|int < 71 %}
      - {{phpname}}
{%- else %}
      - mod_{{phpname}}
{%- endif %}
  service.running:
    - enable: True
    - reload: True
    - require:
      - pkg: httpd
    - watch:
      - file: /etc/httpd/conf/httpd.conf
      - file: php.ini-memory_limit
      - file: /etc/php.d/xdebug.ini
      - pkg: php
      - pkg: mysqld

/etc/httpd/conf/httpd.conf:
  file.managed:
    - source: salt://httpd.conf
