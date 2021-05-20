<?php


namespace YaangVu\RabbitMQ;


use Exception;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Demo
 * Documentation: https://github.com/php-amqplib/php-amqplib/blob/master/demo/amqp_publisher.php
 * @package YaangVu\RabbitMQ
 */
class Demo
{
    /**
     * @throws Exception
     */
    function sendEmailTest()
    {
        $exchange = env('RABBITMQ_EXCHANGE');
        $queue    = env('RABBITMQ_QUEUE');

        $connection = (new RabbitMQConnection())
            ->setHost('127.0.0.1')
            ->setPort(5672)
            ->setUser('rabbit-user')
            ->setPassword('rabbit-password')
            ->setVHost('rabbit-vhost')
            ->connect();

        $connection = (new RabbitMQConnection())->createConnection()->connection();

        $channel = $connection->channel();

        $channel->exchange_declare(env('RABBITMQ_EXCHANGE'), AMQPExchangeType::DIRECT, false, true, false);

        $messageBody = json_encode(
            [
                "subject"    => "Title here",
                "body"       => "Email body here",
                "recipients" => [
                    "giangvt@toprate.io"
                ],
                "ccs"        => [
                ],
                "bccs"       => [
                ]
            ]
        );

        $message = new AMQPMessage($messageBody, ['content_type' => 'text/plain']);
        $channel->basic_publish($message, env('RABBITMQ_EXCHANGE'), env('RABBITMQ_EXCHANGE_ROUTING_KEY'));

        $channel->close();
        $connection->close();
    }
}
