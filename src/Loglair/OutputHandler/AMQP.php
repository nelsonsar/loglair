<?php

namespace Loglair\OutputHandler;

class AMQP
{
    private $exchange = null;

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

        //Declarando o exchange
        $this->exchange = new \AMQPExchange($channel);

        $this->exchange->setName('amq.direct');
        //Direct exchange
        $this->exchange->setType(AMQP_EX_TYPE_DIRECT);
        $this->exchange->setFlags(AMQP_DURABLE);

        $this->exchange->declareExchange();
    }

    public function send($message)
    {
        //Publicando a mensagem com o routing key: error
        $this->exchange->publish($message, 'error');
    }
}
