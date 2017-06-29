/media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/home/P51x_Linux_Driver_1.0.0/Install.sh
add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) main universe restricted multiverse"
apt-get update
apt-get install -y openssh-server
rm /etc/ssh/sshd_config
rm /etc/init.d/ssh
cp /media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/home/sshd_config /etc/ssh/sshd_config
cp /media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/home/ssh.initd /etc/init.d/ssh
/etc/init.d/ssh start
cp /media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/home/print.sh /root/print.sh
mkdir /root/.ssh
cat /media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/home/laptop.key >> /root/.ssh/authorized_keys
wget https://raw.githubusercontent.com/frederikbosch/photobooth/blob/master/src/print.sh -O /root/print.sh
chmod +x /root/print.sh