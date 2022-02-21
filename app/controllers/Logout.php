<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Middlewares\Authentication;

class Logout extends Controller {

    public function index() {

        /* Exit admin impersonation */
        if(isset($_GET['admin_impersonate_user'])) {
            $admin_user_id = $_SESSION['admin_user_id'];

            /* Logout of the current users */
            Authentication::logout(false);

            /* Login as the admin */
            session_start();
            $_SESSION['user_id'] = $admin_user_id;

            redirect('admin/users');
        }

        /* Normal logout */
        else {
            Authentication::logout();
        }

    }

}
