Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.provision "shell", path: "worker_config.sh"
  config.vm.synced_folder "src/", "/vagrant"

  config.vm.define "worker01" do |worker|
    worker.vm.network "private_network", ip: "192.168.33.10"
  end

  config.vm.define "worker02" do |worker|
    worker.vm.network "private_network", ip: "192.168.33.11"
  end

  config.vm.define "worker03" do |worker|
    worker.vm.network "private_network", ip: "192.168.33.12"
  end

end
