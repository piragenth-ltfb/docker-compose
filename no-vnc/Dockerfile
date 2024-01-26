from ubuntu:latest

label maintainer='piragenth'
ARG DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install apt-transport-https -y && apt install plasma-workspace -y && apt install  python3 bash zsh nano tigervnc* firefox git sudo -y && adduser --disabled-password --gscop o
piragenth && echo -e '3559\n3559' | passwd piragenth \
 && echo 'piragenth ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers\
 && git clone https://github.com/novnc/noVNC /opt/noVNC/ \
 && git clone https://github.com/novnc/websockify /opt/noVNC/utils/websockify

user piragenth
workdir /home/piragenth

run mkdir -p /home/piragenth/.vnc \
    && echo -e "#!/bin/bash\nstartplasma-x11 &" > /home/piragenth/.vnc/xstartup \
    && echo -e "piragenth\npiragenth\nn\n" | vncpasswd

copy entry.sh /entry.sh

cmd ['/bin/bash', '/entry.sh']
