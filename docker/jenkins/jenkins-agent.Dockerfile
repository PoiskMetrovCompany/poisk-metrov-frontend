FROM jenkins/inbound-agent:latest

ENV JENKINS_AGENT_WORKDIR=/home/jenkins/agent

LABEL maintainer="dev@example.com"
LABEL description="Custom Jenkins agent with Docker, Node.js, Python, Git, kubectl"

USER root

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    zip \
    vim \
    net-tools \
    iproute2 \
    docker.io \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    python3 \
    python3-pip \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

RUN mkdir -p $JENKINS_AGENT_WORKDIR

WORKDIR $JENKINS_AGENT_WORKDIR
USER jenkins