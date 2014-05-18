# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "fgrehm/trusty64-lxc"

  config.vm.define :lxc do |lxc_config|
    lxc_config.vm.network :forwarded_port, guest: 8080, host: 3020
    #lxc_config.vm.network :forwarded_port, guest: 80, host: 3001
  end

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "playbook.yml"
  end

end
