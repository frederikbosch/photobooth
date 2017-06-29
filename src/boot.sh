/media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/P51x_Linux_Driver_1.0.0/Install.sh
add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) main universe restricted multiverse"
apt-get update
apt-get install -y openssh-server
rm /etc/ssh/sshd_config
cp /media/dc7aa23f-297e-43c3-a1f4-64765c52c43c/sshd_config /etc/ssh/sshd_config
service restart ssh
