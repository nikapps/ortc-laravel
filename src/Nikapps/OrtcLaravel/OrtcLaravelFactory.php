<?php

namespace Nikapps\OrtcLaravel;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Channel;
use Nikapps\OrtcPhp\Models\Requests\AuthRequest;
use Nikapps\OrtcPhp\Models\Requests\SendMessageRequest;
use Nikapps\OrtcPhp\Ortc;

class OrtcLaravelFactory
{
    /**
     * @var OrtcConfig
     */
    protected $ortcConfig;

    /**
     * constructor
     *
     * @param ConfigRepository $config
     */
    public function __construct(array $config)
    {
        $this->createOrtcConfig($config);
    }

    /**
     * @return OrtcConfig
     */
    public function getOrtcConfig()
    {
        return $this->ortcConfig;
    }

    /**
     * create OrtcConfig from laravel config
     */
    protected function createOrtcConfig(array $config)
    {
        $ortcConfig = new OrtcConfig();

        $ortcConfig->setApplicationKey(
                $config['credentials']['application_key']
        );
        $ortcConfig->setPrivateKey(
                $config['credentials']['private_key']
        );
        $ortcConfig->setBalancerUrl(
                $config['api']['balancer_url']
        );
        $ortcConfig->setAuthenticationPath(
                $config['api']['authentication']['path']
        );
        $ortcConfig->setSendPath(
                $config['api']['send_message']['path']
        );
        $ortcConfig->setMaxChunkSize(
                $config['api']['send_message']['max_chunk_size']
        );
        $ortcConfig->setBatchPoolSize(
                $config['api']['send_message']['batch_pool_size']
        );
        $ortcConfig->setPreMessageString(
                $config['api']['send_message']['pre_message_string']
        );
        $ortcConfig->setVerifySsl(
                $config['api']['verify_ssl']
        );

        $this->ortcConfig = $ortcConfig;
    }

    /**
     * authenticate user
     *
     * @param string|AuthRequest $authTokenOrAuthRequest
     * @param array $channels
     * @param int $ttl = 3600
     * @param bool $isPrivate = false
     * @throws \Nikapps\OrtcPhp\Exceptions\NetworkErrorException
     * @throws \Nikapps\OrtcPhp\Exceptions\UnauthorizedException
     * @return \Nikapps\OrtcPhp\Models\Responses\AuthResponse
     */
    public function authenticate(
        $authTokenOrAuthRequest,
        $channels = [],
        $ttl = 3600,
        $isPrivate = false
    ) {
        if (!($authTokenOrAuthRequest instanceof AuthRequest)) {
            $authToken = $authTokenOrAuthRequest;

            $channelObjects = [];

            //create channel objects
            foreach ($channels as $channelName => $permission) {
                $channel = new Channel();
                $channel->setName($channelName);
                $channel->setPermission($permission);

                $channelObjects[] = $channel;
            }

            $authRequest = new AuthRequest();
            $authRequest->setAuthToken($authToken);
            $authRequest->setExpireTime($ttl);
            $authRequest->setPrivate($isPrivate);
            $authRequest->setChannels($channelObjects);
        } else {
            $authRequest = $authTokenOrAuthRequest;
        }

        $ortc = new Ortc($this->ortcConfig);

        return $ortc->authenticate($authRequest);
    }

    /**
     * get new balancer url
     *
     * @throws \Nikapps\OrtcPhp\Exceptions\NetworkErrorException
     * @throws \Nikapps\OrtcPhp\Exceptions\UnauthorizedException
     * @throws \Nikapps\OrtcPhp\Exceptions\InvalidBalancerUrlException
     * @return \Nikapps\OrtcPhp\Models\Responses\BalancerUrlResponse
     */
    public function getBalancerUrl()
    {
        $ortc = new Ortc($this->ortcConfig);

        return $ortc->getBalancerUrl();
    }

    /**
     * send (push) message to a channel
     *
     * @param string|SendMessageRequest $channelOrSendMessageRequest
     * @param string $authToken
     * @param string $message
     * @throws \Nikapps\OrtcPhp\Exceptions\BatchRequestException
     * @return \Nikapps\OrtcPhp\Models\Responses\SendMessageResponse
     */
    public function send(
        $channelOrSendMessageRequest,
        $authToken = '',
        $message = ''
    ) {
        if (!($channelOrSendMessageRequest instanceof SendMessageRequest)) {
            $channelName = $channelOrSendMessageRequest;

            $sendMessageRequest = new SendMessageRequest();
            $sendMessageRequest->setAuthToken($authToken);
            $sendMessageRequest->setChannelName($channelName);
            $sendMessageRequest->setMessage($message);
        } else {
            $sendMessageRequest = $channelOrSendMessageRequest;
        }

        $ortc = new Ortc($this->ortcConfig);

        return $ortc->sendMessage($sendMessageRequest);
    }

    /**
     * same as send() but pusher-way!
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @throws \Nikapps\OrtcPhp\Exceptions\BatchRequestException
     * @return \Nikapps\OrtcPhp\Models\Responses\SendMessageResponse
     */
    public function trigger($channels, $event, array $payload = [])
    {
        $message_arr = array(
            'event' => $event,
            'payload' => $payload
        );
        $message = json_encode($message_arr);
        
        foreach ($channels as $channel) {
            return $this->send($channel, '', $message);
        }
    }
}
