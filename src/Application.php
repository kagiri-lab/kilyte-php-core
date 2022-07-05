<?php

namespace kilyte;

use Exception;
use kilyte\database\Database;
use kilyte\exception\DatabaseException;
use kilyte\exception\DynamicException;
use kilyte\exception\KiLyteException;
use kilyte\http\Request;
use kilyte\http\Response;
use kilyte\http\Session;
use kilyte\model\UserModel;
use kilyte\route\Router;
use Throwable;


class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];

    public static Application $app;
    public static string $ROOT_DIR;
    public string $userClass;
    public string $layout;
    public Router $router;
    public ?Request $request;
    public ?Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public View $view;
    public ?UserModel $user;

    public function __construct($rootDir, $customLayout = 'auth')
    {
        $this->layout = $customLayout;
        set_exception_handler(array($this, 'kilyteExceptionHandler'));
        $this->user = null;
        $this->userClass = \app\models\User::class;
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->view = new View();
        $this->db = new Database($this->loadConfig($rootDir)['db']);
        $userId = Application::$app->session->get('user');
        if ($userId) {
            $key = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$key => $userId]);
        }
    }

    public function loadConfig($rootDir)
    {
        try {
            $dotenv = \Dotenv\Dotenv::createImmutable($rootDir);
            $dotenv->load();
            if (!isset($_ENV['DB_DSN']))
                throw new KiLyteException("Setup DB Connection");
            if (!isset($_ENV['DB_USER']))
                throw new KiLyteException("Setup DB Connection");
            if (!isset($_ENV['DB_PASSWORD']))
                throw new KiLyteException("Setup DB Connection");
            $config = [
                'db' => [
                    'dsn' => $_ENV['DB_DSN'],
                    'user' => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                ]
            ];

            return $config;
        } catch (Exception $ex) {
            throw new KiLyteException($ex->getMessage());
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey();
        $value = $user->{$primaryKey};
        Application::$app->session->set('user', $value);

        return true;
    }

    public function logout()
    {
        $this->user = null;
        self::$app->session->remove('user');
    }

    public function run()
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            $results = $this->router->resolve();
            $this->response->print_response($results);
        } catch (Throwable $e) {
            $results = $this->router->renderError('error.view', $e);
            $this->response->print_response($results);
        }
    }

    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    public function kilyteExceptionHandler(Throwable $exception)
    {
        $results = $this->router->renderError('error.view', $exception);
        $this->response->print_response($results);
    }
}
