<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\NotificationModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    // Declare notificationCount property
    protected $notificationCount = 0;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');

        // Debug: Log session and notification count
        log_message('debug', 'Session userID: ' . session()->get('userID'));
        log_message('debug', 'Session isLoggedIn: ' . session()->get('isLoggedIn'));

        // Fetch unread notification count for logged-in user
        if (session()->get('isLoggedIn')) {
            $notificationModel = new NotificationModel();
            $userId = session()->get('userID');
            $this->notificationCount = $notificationModel->getUnreadCount($userId);

            // Debug: Log notification count
            log_message('debug', 'Notification count for user ' . $userId . ': ' . $this->notificationCount);
        }
    }
}
