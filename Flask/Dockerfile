FROM python:alpine3.16
COPY ./requirements.txt /home/requirements.txt
workdir /home
copy . /home
RUN echo 'nameserver 8.8.8.8' > /etc/resolv.conf && pip3 install -r requirements.txt
cmd python app.py
