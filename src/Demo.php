<?php


namespace YaangVu\RabbitMQ;


use Exception;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Demo
 * Documentation: https://github.com/php-amqplib/php-amqplib/blob/master/demo
 * @package YaangVu\RabbitMQ
 */
class Demo
{
    /**
     * @throws Exception
     */
    function sendEmailTest()
    {
        $exchange   = 'rabbit-exchange';
        $host       = '127.0.0.1';
        $port       = 5672;
        $user       = 'rabbit-user';
        $password   = 'rabbit-password';
        $vHost      = 'rabbit-vhost';
        $routingKey = 'routing-key';

        $connection = (new RabbitMQConnection())
            //->setHost($host)
            //->setPort($port)
            //->setUser($user)
            //->setPassword($password)
            //->setVHost($vHost)
            ->connect();

        $channel = $connection->channel();

        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $messageBody = json_encode(
            [
                "subject"    => "Title here",
                "body"       => "Email body here",
                "recipients" => [
                    "giangvt@toprate.io",
                    "tungnd@toprate.io"
                ],
                "ccs"        => [
                ],
                "bccs"       => [
                ]
            ]
        );

        $message = new AMQPMessage($messageBody, ['content_type' => 'text/plain']);
        $channel->basic_publish($message, $exchange, $routingKey);

        $channel->close();
        $connection->close();
    }
}
