FROM rabbitmq:3.10.1-alpine
LABEL authors="vuacheslav.mir@gmail.com"
RUN rabbitmq-plugins enable --offline rabbitmq_management
EXPOSE 5672 15672
