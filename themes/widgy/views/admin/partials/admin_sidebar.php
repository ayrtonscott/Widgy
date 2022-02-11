<?php defined('ALTUMCODE') || die() ?>

<section class="admin-sidebar">
    <div class="admin-sidebar-title">
        <a href="<?= url() ?>" class="text-decoration-none text-truncate">
            <?php if(settings()->logo != ''): ?>
                <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid admin-navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <span class="admin-navbar-brand"><?= settings()->main->title ?></span>
            <?php endif ?>
        </a>
    </div>

    <ul class="admin-sidebar-links">
        <li class="<?= \Altum\Routing\Router::$controller == 'AdminIndex' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-tv"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_index.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminUsers', 'AdminUserUpdate', 'AdminUserCreate', 'AdminUserView']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/users') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-users"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_users.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminUsersLogs']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/users-logs') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-scroll"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_users_logs.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminCampaigns' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/campaigns') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-server"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_campaigns.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminNotifications' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/notifications') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-bell"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_notifications.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminPages', 'AdminPageCreate', 'AdminPageUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/pages') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-copy"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_pages.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminPlans', 'AdminPlanCreate', 'AdminPlanUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/plans') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-box-open"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_plans.menu') ?></span>
                </div>
            </a>
        </li>

        <?php if(in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminCodes', 'AdminCodeCreate', 'AdminCodeUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/codes') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-tags"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_codes.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminTaxes', 'AdminTaxCreate', 'AdminTaxUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/taxes') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-paperclip"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_taxes.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminPayments' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/payments') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-credit-card"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_payments.menu') ?></span>
                </div>
            </a>
        </li>

            <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                <li class="<?= \Altum\Routing\Router::$controller == 'AdminAffiliatesWithdrawals' ? 'active' : null ?>">
                    <a class="nav-link d-flex flex-row" href="<?= url('admin/affiliates-withdrawals') ?>">
                        <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-wallet"></i></div>
                        <div class="col">
                            <span class="d-inline"><?= l('admin_affiliates_withdrawals.menu') ?></span>
                        </div>
                    </a>
                </li>
            <?php endif ?>
        <?php endif ?>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminStatistics' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/statistics') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-chart-line"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_statistics.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminApiDocumentation' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/api-documentation') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-code"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_api_documentation.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminPlugins' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/plugins') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-puzzle-piece"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_plugins.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminLanguages' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/languages') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-language"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_languages.menu') ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminSettings' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/settings') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-wrench"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('admin_settings.menu') ?></span>
                </div>
            </a>
        </li>
    </ul>

    <hr />

    <ul class="admin-sidebar-links">
        <li>
            <a class="nav-link d-flex flex-row" target="_blank" href="<?= url('dashboard') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-home"></i></div>
                <div class="col">
                    <span class="d-inline"><?= l('global.menu.website') ?></span>
                </div>
            </a>
        </li>

        <li class="dropdown">
            <a class="nav-link d-flex flex-row dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                <div class="col-1 d-flex align-items-center"><img src="<?= get_gravatar($this->user->email) ?>" class="admin-avatar" loading="lazy" /></div>
                <div class="col text-truncate">
                    <span class="d-inline"><?= $this->user->name ?></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-sm fa-wrench mr-1"></i> <?= l('account.menu') ?></a>
                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sm fa-sign-out-alt mr-1"></i> <?= l('global.menu.logout') ?></a>
            </div>
        </li>
    </ul>
</section>
