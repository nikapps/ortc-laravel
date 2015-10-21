<?php
namespace Nikapps\OrtcLaravel;

use Illuminate\Config\Repository as ConfigRepository;
use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Channel;
use Nikapps\OrtcPhp\Models\Requests\AuthRequest;
use Nikapps\OrtcPhp\Models\Requests\BalancerUrlRequest;
use Nikapps\OrtcPhp\Models\Requests\SendMessageRequest;
use Nikapps\OrtcPhp\Ortc;

class OrtcLaravelFactory
{

    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var OrtcConfig
     */
    protected $ortcConfig;

    /**
     * constructor
     *
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;

        $this->createOrtcConfig();
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
    protected function createOrtcConfig()
    {
        $ortcConfig = new OrtcConfig();

        $ortcConfig->setApplicationKey(
            $this->config->get('ortc-laravel::credentials.application_key')
        );
        $ortcConfig->setPrivateKey(
            $this->config->get('ortc-laravel::credentials.private_key')
        );
        $ortcConfig->setBalancerUrl(
            $this->config->get('ortc-laravel::api.balancer_url')
        );
        $ortcConfig->setAuthenticationPath(
            $this->config->get('ortc-laravel::api.authentication.path')
        );
        $ortcConfig->setSendPath(
            $this->config->get('ortc-laravel::api.send_message.path')
        );
        $ortcConfig->setMaxChunkSize(
            $this->config->get('ortc-laravel::api.send_message.max_chunk_size')
        );
        $ortcConfig->setBatchPoolSize(
            $this->config->get('ortc-laravel::api.send_message.batch_pool_size')
        );
        $ortcConfig->setPreMessageString(
            $this->config->get('ortc-laravel::api.send_message.pre_message_string')
        );
        $ortcConfig->setVerifySsl(
            $this->config->get('ortc-laravel::api.verify_ssl')
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
     * @param string $channel
     * @param string $authToken
     * @param string $message
     * @throws \Nikapps\OrtcPhp\Exceptions\BatchRequestException
     * @return \Nikapps\OrtcPhp\Models\Responses\SendMessageResponse
     */
    public function trigger($channel, $authToken, $message)
    {
        return $this->send($channel, $authToken, $message);
    }
}
