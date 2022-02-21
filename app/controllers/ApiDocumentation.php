<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Title;

class ApiDocumentation extends Controller {

    public function index() {

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function user() {

        Title::set(l('api_documentation.user.title'));

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/user', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function campaigns() {

        Title::set(l('api_documentation.campaigns.title'));

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/campaigns', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function notifications() {

        Title::set(l('api_documentation.notifications.title'));

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/notifications', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function payments() {

        Title::set(l('api_documentation.payments.title'));

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/payments', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function users_logs() {

        Title::set(l('api_documentation.users_logs.title'));

        /* Prepare the View */
        $view = new \Altum\Views\View('api-documentation/users_logs', (array) $this);

        $this->add_view_content('content', $view->run());

    }
}


