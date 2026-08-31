<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Creator AI - Gemini Live WebSocket Bridge
|--------------------------------------------------------------------------
|
| Browser
|    ↓
| Creator AI WebSocket
|    ↓
| Gemini Live API
|    ↓
| Creator AI WebSocket
|    ↓
| Browser
|
|--------------------------------------------------------------------------
|
| REQUIREMENTS
|
| composer require cboden/ratchet ratchet/pawl react/event-loop
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../../../auth/config.php';

require_once __DIR__ . '/../../../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

use SplObjectStorage;


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

const WS_HOST = '0.0.0.0';

const WS_PORT = 8090;


/*
|--------------------------------------------------------------------------
| Gemini Live endpoint
|--------------------------------------------------------------------------
*/

const GEMINI_WS_URL =
    'wss://generativelanguage.googleapis.com/ws/' .
    'google.ai.generativelanguage.v1beta.GenerativeService.BidiGenerateContent';


/*
|--------------------------------------------------------------------------
| Get Gemini API key
|--------------------------------------------------------------------------
*/

function getGeminiApiKey(): string
{
    if (
        defined('GEMINI_API_KEY') &&
        trim((string) GEMINI_API_KEY) !== ''
    ) {
        return trim((string) GEMINI_API_KEY);
    }

    if (
        defined('GOOGLE_API_KEY') &&
        trim((string) GOOGLE_API_KEY) !== ''
    ) {
        return trim((string) GOOGLE_API_KEY);
    }

    return '';
}


/*
|--------------------------------------------------------------------------
| Get Live model
|--------------------------------------------------------------------------
|
| Current official native-audio Live model.
|
*/

function getLiveModel(): string
{
    if (
        defined('GEMINI_LIVE_MODEL') &&
        trim((string) GEMINI_LIVE_MODEL) !== ''
    ) {
        return trim((string) GEMINI_LIVE_MODEL);
    }

    return 'gemini-2.5-flash-native-audio-preview-12-2025';
}


/*
|--------------------------------------------------------------------------
| Generate client id
|--------------------------------------------------------------------------
*/

function clientId(ConnectionInterface $connection): string
{
    return 'creator_' . spl_object_id($connection);
}


/*
|--------------------------------------------------------------------------
| Send JSON safely
|--------------------------------------------------------------------------
*/

function sendJson(
    ConnectionInterface $connection,
    array $data
): void {

    try {

        $connection->send(
            json_encode(
                $data,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );

    } catch (Throwable $e) {

        error_log(
            'Creator AI WS send error: ' .
            $e->getMessage()
        );
    }
}


/*
|--------------------------------------------------------------------------
| Bridge
|--------------------------------------------------------------------------
*/

class GeminiLiveBridge
{
    public ConnectionInterface $browser;

    public ?WebSocket $gemini = null;

    private LoopInterface $loop;

    private string $apiKey;

    private string $model;

    private bool $setupSent = false;

    private bool $closed = false;

    public function __construct(
        LoopInterface $loop,
        ConnectionInterface $browser
    ) {

        $this->loop =
            $loop;

        $this->browser =
            $browser;

        $this->apiKey =
            getGeminiApiKey();

        $this->model =
            getLiveModel();
    }


    /*
    |--------------------------------------------------------------------------
    | Connect Gemini
    |--------------------------------------------------------------------------
    */

    public function connect(): void
    {
        if ($this->apiKey === '') {

            sendJson(
                $this->browser,
                [
                    'type' => 'error',
                    'error' =>
                        'Gemini API key is not configured.'
                ]
            );

            return;
        }

        $connector =
            new Connector(
                $this->loop
            );

        /*
        |--------------------------------------------------------------------------
        | Gemini API key is sent only server-side.
        |--------------------------------------------------------------------------
        */

        $url =
            GEMINI_WS_URL .
            '?key=' .
            rawurlencode(
                $this->apiKey
            );

        $connector(
            $url
        )->then(

            function (
                WebSocket $connection
            ) {

                $this->gemini =
                    $connection;

                $this->setup();

                sendJson(
                    $this->browser,
                    [
                        'type' => 'ready',
                        'model' =>
                            $this->model
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Gemini messages
                |--------------------------------------------------------------------------
                */

                $connection->on(
                    'message',
                    function ($message) {

                        $this->fromGemini(
                            (string)$message
                        );

                    }
                );

                $connection->on(
                    'close',
                    function (
                        $code = null,
                        $reason = null
                    ) {

                        sendJson(
                            $this->browser,
                            [
                                'type' => 'closed',
                                'code' =>
                                    $code,
                                'reason' =>
                                    $reason
                            ]
                        );

                    }
                );

                $connection->on(
                    'error',
                    function (
                        Throwable $error
                    ) {

                        sendJson(
                            $this->browser,
                            [
                                'type' => 'error',
                                'error' =>
                                    'Gemini Live connection error.'
                            ]
                        );

                        error_log(
                            'Gemini Live error: ' .
                            $error->getMessage()
                        );

                    }
                );

            },

            function (
                Throwable $error
            ) {

                sendJson(
                    $this->browser,
                    [
                        'type' => 'error',
                        'error' =>
                            'Could not connect to Gemini Live API.'
                    ]
                );

                error_log(
                    'Gemini Live connection failed: ' .
                    $error->getMessage()
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Gemini setup
    |--------------------------------------------------------------------------
    */

    private function setup(): void
    {
        if (
            !$this->gemini ||
            $this->setupSent
        ) {
            return;
        }

        $this->setupSent =
            true;

        $setup = [

            'setup' => [

                'model' =>
                    'models/' .
                    $this->model,

                'generationConfig' => [

                    /*
                    |--------------------------------------------------------------------------
                    | Native audio response
                    |--------------------------------------------------------------------------
                    */

                    'responseModalities' => [
                        'AUDIO'
                    ],

                    'speechConfig' => [

                        'voiceConfig' => [

                            'prebuiltVoiceConfig' => [

                                /*
                                |--------------------------------------------------------------------------
                                | Natural female voice.
                                |--------------------------------------------------------------------------
                                */

                                'voiceName' =>
                                    'Kore'
                            ]
                        ]
                    ],

                    'temperature' =>
                        0.7,

                    'maxOutputTokens' =>
                        2048
                ],

                /*
                |--------------------------------------------------------------------------
                | Input/output transcription
                |--------------------------------------------------------------------------
                */

                'inputAudioTranscription' => [],

                'outputAudioTranscription' => [],

                /*
                |--------------------------------------------------------------------------
                | System instruction
                |--------------------------------------------------------------------------
                */

                'systemInstruction' => [

                    'parts' => [

                        [
                            'text' =>
                                'You are Creator AI, a helpful ' .
                                'real-time voice assistant. ' .
                                'Answer naturally and conversationally. ' .
                                'Be concise unless the user asks for detail. ' .
                                'You can speak in Hindi, Hinglish, English, ' .
                                'or another language naturally matching the user.'
                        ]

                    ]

                ]

            ]

        ];

        $this->gemini->send(
            json_encode(
                $setup,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Browser -> Gemini
    |--------------------------------------------------------------------------
    */

    public function fromBrowser(
        string $raw
    ): void {

        if (!$this->gemini) {

            sendJson(
                $this->browser,
                [
                    'type' => 'error',
                    'error' =>
                        'Gemini Live session is not ready.'
                ]
            );

            return;
        }

        $data =
            json_decode(
                $raw,
                true
            );

        if (
            !is_array($data)
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Audio
        |--------------------------------------------------------------------------
        */

        if (
            ($data['type'] ?? '') ===
            'audio'
        ) {

            $base64 =
                (string)(
                    $data['data']
                    ?? ''
                );

            if (
                $base64 === ''
            ) {
                return;
            }

            $payload = [

                'realtimeInput' => [

                    'audio' => [

                        'data' =>
                            $base64,

                        'mimeType' =>
                            'audio/pcm;rate=16000'
                    ]

                ]

            ];

            $this->gemini->send(
                json_encode(
                    $payload
                )
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | End audio stream
        |--------------------------------------------------------------------------
        */

        if (
            ($data['type'] ?? '') ===
            'audio_end'
        ) {

            $this->gemini->send(
                json_encode(
                    [
                        'realtimeInput' => [
                            'audioStreamEnd' => true
                        ]
                    ]
                )
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Text
        |--------------------------------------------------------------------------
        */

        if (
            ($data['type'] ?? '') ===
            'text'
        ) {

            $text =
                trim(
                    (string)(
                        $data['text']
                        ?? ''
                    )
                );

            if (
                $text === ''
            ) {
                return;
            }

            $payload = [

                'clientContent' => [

                    'turns' => [

                        [
                            'role' =>
                                'user',

                            'parts' => [

                                [
                                    'text' =>
                                        $text
                                ]

                            ]

                        ]

                    ],

                    'turnComplete' =>
                        true
                ]

            ];

            $this->gemini->send(
                json_encode(
                    $payload,
                    JSON_UNESCAPED_UNICODE
                )
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Ping
        |--------------------------------------------------------------------------
        */

        if (
            ($data['type'] ?? '') ===
            'ping'
        ) {

            sendJson(
                $this->browser,
                [
                    'type' =>
                        'pong'
                ]
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Gemini -> Browser
    |--------------------------------------------------------------------------
    */

    private function fromGemini(
        string $raw
    ): void {

        $data =
            json_decode(
                $raw,
                true
            );

        if (
            !is_array($data)
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Server setup response
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $data['setupComplete']
            )
        ) {

            sendJson(
                $this->browser,
                [
                    'type' =>
                        'setup_complete'
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Server content
        |--------------------------------------------------------------------------
        */

        $serverContent =
            $data['serverContent']
            ?? null;

        if (
            !is_array(
                $serverContent
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Gemini error
            |--------------------------------------------------------------------------
            */

            if (
                isset(
                    $data['error']
                )
            ) {

                sendJson(
                    $this->browser,
                    [
                        'type' =>
                            'error',

                        'error' =>
                            $data['error']['message']
                            ?? 'Gemini Live error.'
                    ]
                );

            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Model turn
        |--------------------------------------------------------------------------
        */

        $modelTurn =
            $serverContent['modelTurn']
            ?? null;

        if (
            is_array(
                $modelTurn
            )
        ) {

            $parts =
                $modelTurn['parts']
                ?? [];

            foreach (
                $parts
                as $part
            ) {

                if (
                    !is_array(
                        $part
                    )
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Inline audio
                |--------------------------------------------------------------------------
                */

                $inline =
                    $part['inlineData']
                    ?? null;

                if (
                    is_array(
                        $inline
                    )
                ) {

                    $mime =
                        (string)(
                            $inline['mimeType']
                            ?? ''
                        );

                    $audio =
                        (string)(
                            $inline['data']
                            ?? ''
                        );

                    if (
                        $audio !== ''
                    ) {

                        sendJson(
                            $this->browser,
                            [
                                'type' =>
                                    'audio',

                                'mimeType' =>
                                    $mime !== ''
                                        ? $mime
                                        : 'audio/pcm;rate=24000',

                                'data' =>
                                    $audio
                            ]
                        );

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Text part
                |--------------------------------------------------------------------------
                */

                if (
                    isset(
                        $part['text']
                    )
                ) {

                    sendJson(
                        $this->browser,
                        [
                            'type' =>
                                'text',

                            'text' =>
                                (string)
                                $part['text']
                        ]
                    );

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Input transcription
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $serverContent['inputTranscription']
            )
        ) {

            $transcription =
                $serverContent[
                    'inputTranscription'
                ];

            if (
                is_array(
                    $transcription
                )
            ) {

                sendJson(
                    $this->browser,
                    [
                        'type' =>
                            'input_transcript',

                        'text' =>
                            (string)(
                                $transcription['text']
                                ?? ''
                            )
                    ]
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Output transcription
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $serverContent['outputTranscription']
            )
        ) {

            $transcription =
                $serverContent[
                    'outputTranscription'
                ];

            if (
                is_array(
                    $transcription
                )
            ) {

                sendJson(
                    $this->browser,
                    [
                        'type' =>
                            'output_transcript',

                        'text' =>
                            (string)(
                                $transcription['text']
                                ?? ''
                            )
                    ]
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Turn complete
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $serverContent['turnComplete']
            )
        ) {

            sendJson(
                $this->browser,
                [
                    'type' =>
                        'turn_complete'
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Interrupted
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $serverContent['interrupted']
            )
        ) {

            sendJson(
                $this->browser,
                [
                    'type' =>
                        'interrupted'
                ]
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Close
    |--------------------------------------------------------------------------
    */

    public function close(): void
    {
        if (
            $this->closed
        ) {
            return;
        }

        $this->closed =
            true;

        if (
            $this->gemini
        ) {

            try {

                $this->gemini->close();

            } catch (
                Throwable $e
            ) {
                // Ignore.
            }

        }
    }
}


/*
|--------------------------------------------------------------------------
| WebSocket server
|--------------------------------------------------------------------------
*/

class CreatorLiveServer
    implements MessageComponentInterface
{
    private SplObjectStorage $clients;

    private LoopInterface $loop;

    /**
     * @var array<int,GeminiLiveBridge>
     */
    private array $bridges = [];


    public function __construct(
        LoopInterface $loop
    ) {

        $this->loop =
            $loop;

        $this->clients =
            new SplObjectStorage();

        echo PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo " Creator AI Gemini Live WebSocket Server" . PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo "Host: " . WS_HOST . PHP_EOL;
        echo "Port: " . WS_PORT . PHP_EOL;
        echo "Model: " . getLiveModel() . PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo PHP_EOL;
    }


    public function onOpen(
        ConnectionInterface $connection
    ): void {

        $this->clients->attach(
            $connection
        );

        $id =
            spl_object_id(
                $connection
            );

        echo
            '[' .
            date('Y-m-d H:i:s') .
            '] Browser connected #' .
            $id .
            PHP_EOL;

        $bridge =
            new GeminiLiveBridge(
                $this->loop,
                $connection
            );

        $this->bridges[$id] =
            $bridge;

        sendJson(
            $connection,
            [
                'type' =>
                    'connecting'
            ]
        );

        $bridge->connect();
    }


    public function onMessage(
        ConnectionInterface $from,
        $message
    ): void {

        $id =
            spl_object_id(
                $from
            );

        if (
            !isset(
                $this->bridges[$id]
            )
        ) {
            return;
        }

        $this->bridges[$id]
            ->fromBrowser(
                (string)$message
            );
    }


    public function onClose(
        ConnectionInterface $connection
    ): void {

        $id =
            spl_object_id(
                $connection
            );

        echo
            '[' .
            date('Y-m-d H:i:s') .
            '] Browser disconnected #' .
            $id .
            PHP_EOL;

        if (
            isset(
                $this->bridges[$id]
            )
        ) {

            $this->bridges[$id]
                ->close();

            unset(
                $this->bridges[$id]
            );
        }

        $this->clients->detach(
            $connection
        );
    }


    public function onError(
        ConnectionInterface $connection,
        Exception $e
    ): void {

        error_log(
            'Creator Live WebSocket error: ' .
            $e->getMessage()
        );

        try {

            $connection->close();

        } catch (
            Throwable $ignored
        ) {
        }
    }
}


/*
|--------------------------------------------------------------------------
| Start
|--------------------------------------------------------------------------
*/

$loop =
    Loop::get();

$server =
    new CreatorLiveServer(
        $loop
    );

$socket =
    new React\Socket\SocketServer(
        WS_HOST . ':' . WS_PORT,
        [],
        $loop
    );

$websocketServer =
    new Ratchet\WebSocket\WsServer(
        $server
    );

$httpServer =
    new Ratchet\Http\HttpServer(
        $websocketServer
    );

$ioServer =
    new Ratchet\Server\IoServer(
        $httpServer,
        $socket,
        $loop
    );

echo
    'Creator AI Live WebSocket running on ' .
    WS_HOST .
    ':' .
    WS_PORT .
    PHP_EOL;

$loop->run();