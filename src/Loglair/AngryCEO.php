<?php

namespace Loglair;

class AngryCEO
{
    private $queue = null;

    public function __construct()
    {
        $connection = new \AMQPConnection(array(
            'host' => 'localhost',
            'port' => 5672,
            'login' => 'guest',
            'password' => 'guest',
            'vhost' => '/'
        ));

        $connection->connect();

        $channel = new \AMQPChannel($connection);

        $this->queue = new \AMQPQueue($channel);
        $this->queue->setFlags(AMQP_EXCLUSIVE);

        //Criando fila com nome dado pelo servidor
        $this->queue->declareQueue();
        //Fazendo o bind da fila com o exchange. Essa fila consumirá mensagens
        //publicadas no exchange "amq.direct" com o routing key "error"
        $this->queue->bind('amq.direct', 'error');
    }

    public function consume()
    {
        $messages = array(
            'Errors are going thru the roof',
            'Is anybody there?',
            'Who\'s checking this?',
        );

        //Consumindo a mensagem
        $this->queue->consume(function($envelope, $queue) use ($messages) {
            static $count;
            //Avisando o RabbitMQ que eu consegui processar a mensagem
            if ($queue->ack($envelope->getDeliveryTag())) {
                $count++;
            }

            if ($count > 3) {
                echo $messages[rand(0, 2)] . PHP_EOL;
            }
        });
    }

}
