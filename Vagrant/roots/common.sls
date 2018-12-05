github_git-lfs:
  pkgrepo.managed:
    - humanname: Webtatic Repository EL$releasever - $basearch
    - baseurl: https://packagecloud.io/github/git-lfs/el/$releasever/$basearch
    - gpgcheck: 0
    - gpgkey: https://packagecloud.io/github/git-lfs/gpgkey

zsh:
  pkg.installed: []
  git.latest:
    - name: https://github.com/robbyrussell/oh-my-zsh.git
    - target: /home/vagrant/.oh-my-zsh
    - rev: master
    - depth: 1
    - user: vagrant
    - force_reset: True

/home/vagrant:
  file.directory:
    - mode: 711

/home/vagrant/.oh-my-zsh:
  file.directory:
    - user: vagrant
    - mode: 700
    - require:
      - git: zsh

/home/vagrant/.zshrc:
  file.managed:
    - source: salt://zshrc
    - user: vagrant
    - mode: 600

vagrant:
  user.present:
    - shell: /bin/zsh
    - require:
      - pkg: zsh

selinux:
  cmd.run:
    - name: setenforce Permissive
    - unless: getenforce | grep -q Permissive

utils:
  pkg.installed:
    - pkgs:
      - nano
      - htop
      - git
      - gitflow
      - git-lfs
      - the_silver_searcher
      - wget
    - require:
      - pkgrepo: github_git-lfs

postfix:
  pkg.installed: []

  service.running:
    - enable: True
    - reload: True
    - require:
      - pkg: postfix

timedatectl set-timezone Europe/Rome:
  cmd.run:
    - unless: "timedatectl status | grep -q 'Time zone: Europe/Rome'"
