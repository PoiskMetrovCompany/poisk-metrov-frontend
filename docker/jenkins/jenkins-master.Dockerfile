FROM jenkins/jenkins:lts

LABEL maintainer="dev@example.com"
LABEL description="Custom Jenkins LTS with Docker, Git, plugins and pre-configured setup"

USER root
RUN apt update && apt install -y \
    curl \
    git \
    sudo \
    iproute2 \
    net-tools \
    procps \
    vim \
    gnupg2 \
    lsb-release \
    && rm -rf /var/lib/apt/lists/*

RUN echo "jenkins ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers
RUN curl -fsSL https://get.docker.com | sh

USER jenkins

COPY ./conf/plugins.txt /usr/share/jenkins/ref/plugins.txt
RUN jenkins-plugin-cli -f /usr/share/jenkins/ref/plugins.txt

ENV JAVA_OPTS="-Djenkins.install.runSetupWizard=false"
