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
            ->createConnection()
            ->connection();

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

        $message = new AMQPMessage($messageBody, [
            'content_type'  => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel = $connection->channel();

        /*
            The following code is the same both in the consumer and the producer.
            In this way we are sure we always have a queue to consume from and an
                exchange where to publish messages.
        */

        /*
            name: $queue
            passive: false
            durable: true // the queue will survive server restarts
            exclusive: false // the queue can be accessed in other channels
            auto_delete: false //the queue won't be deleted once the channel is closed.
        */
        $channel->queue_declare($queue, false, true, false, false);

        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */

        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        $channel->basic_publish($message, $exchange, env('RABBITMQ_ROUTING_KEY'));

        $channel->close();
        $connection->close();
    }
}
