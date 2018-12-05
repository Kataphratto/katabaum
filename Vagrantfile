# -*- mode: ruby -*-
# vi: set ft=ruby :

# vagrant plugin install vagrant-hostsupdater

Vagrant.configure(2) do |config|
  config.vm.hostname = "katabaum.vg"
  config.vm.box = "centos/7"

  config.vm.network "private_network", ip: "172.28.128.3"
  # config.hostsupdater.aliases = ["wordpress2.vg", "wordpress3.vg"]

  config.ssh.forward_agent = true

  config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder ".", "/home/vagrant/sync", disabled: true
  config.vm.synced_folder "Vagrant/roots", "/srv/salt"
  config.vm.synced_folder ".", "/home/vagrant/sync"

  config.vm.provider "virtualbox" do |vb, override|
    vb.linked_clone = true
    vb.memory = 1024

    override.vm.synced_folder ".", "/home/vagrant/sync", :mount_options => ["dmode=777", "fmode=666"]
  end

  config.vm.provider "libvirt" do |domain, override|
    domain.cpu_mode = "host-passthrough"
    domain.memory = 1024
    domain.volume_cache = "none"

    override.vm.synced_folder ".", "/home/vagrant/sync", :nfs => true
  end

  config.vm.provision :salt, run: "always" do |salt|
    salt.masterless = true
    salt.minion_config = "Vagrant/minion"
    salt.minion_id = "vagrant"
    salt.run_highstate = true
    salt.bootstrap_options = "-p MySQL-python -p git"
    salt.log_level = 'warning'
    salt.verbose = true
    salt.colorize = true

    salt.pillar({
      "hostname" => config.vm.hostname,
      "oldhostname" => "www.baumgartnersa.ch",
      "phpver" => "56", # 56, 70, 71
      "database" => {
        "prefix": "wp_",
        "name": "wordpress",
        "user": "wordpress",
        "password": "wordpress",
      },
    })
  end
end
