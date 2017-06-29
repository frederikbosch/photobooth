/media/$HITI_HD/P51x_Linux_Driver_1.0.0/Install.sh
add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) main universe restricted multiverse"
apt-get install -y openssh-server
echo "    PermitRootLogin without-password" >> /etc/ssh/sshd_config
echo "    PermitRootLogin yes" >> /etc/ssh/sshd_config
service restart openssh
