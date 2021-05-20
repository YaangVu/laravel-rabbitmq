<?php


namespace YaangVu\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;


class RabbitMQConnection
{
    public static AMQPStreamConnection $connection;

    private string $host;
    private string $port;
    private string $user;
    private string $password;
    private string $vHost;

    public function __construct()
    {
        $this->init();
    }

    public function createConnection(): static
    {
        self::$connection = new AMQPStreamConnection(
            $this->getHost(),
            $this->getPort(),
            $this->getUser(),
            $this->getPassword(),
            $this->getVHost()
        );

        return $this;
    }

    /**
     * @return AMQPStreamConnection
     */
    public function connection(): AMQPStreamConnection
    {
        return self::$connection;
    }

    public function init()
    {
        $this->setHost(env('RABBITMQ_HOST'));
        $this->setPort(env('RABBITMQ_PORT'));
        $this->setUser(env('RABBITMQ_USER'));
        $this->setPassword(env('RABBITMQ_PASSWORD'));
        $this->setVHost(env('RABBITMQ_VHOST'));
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $port
     *
     * @return $this
     */
    public function setPort(string $port): static
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $vHost
     *
     * @return $this
     */
    public function setVHost(string $vHost): static
    {
        $this->vHost = $vHost;

        return $this;
    }

    /**
     * @return string
     */
    public function getVHost(): string
    {
        return $this->vHost;
    }
}
