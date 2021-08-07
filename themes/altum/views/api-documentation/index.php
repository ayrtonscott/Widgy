<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= language()->api_documentation->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="row mb-7">
        <div class="col-12 col-lg-7 mb-4 mb-lg-0">
            <h1 class="h4"><?= language()->api_documentation->header ?></h1>
            <p class="text-muted"><?= language()->api_documentation->subheader ?></p>
        </div>

        <div class="col-12 col-lg-4 offset-lg-1">

            <div class="mb-3">
                <a href="<?= url('account-api') ?>" target="_blank" class="btn btn-block btn-outline-primary"><?= language()->api_documentation->api_key ?></a>
            </div>

            <div class="form-group">
                <label for="base_url"><?= language()->api_documentation->base_url ?></label>
                <input type="text" id="base_url" value="<?= SITE_URL . 'api' ?>" class="form-control" readonly="readonly" />
            </div>

        </div>
    </div>

    <div class="mb-7">

        <div class="mb-4">
            <h2 class="h5"><?= language()->api_documentation->authentication->header ?></h2>
            <p class="text-muted"><?= language()->api_documentation->authentication->subheader ?></p>
        </div>

        <div class="form-group">
            <label><?= language()->api_documentation->example ?></label>
            <div class="card bg-gray-50 border-0">
                <div class="card-body">
                    curl --request GET \<br />
                    --url '<?= SITE_URL . 'api/' ?><span class="text-primary">{endpoint}</span>' \<br />
                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-7" />

    <div data-api="user">
        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->user->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link text-decoration-none" data-toggle="collapse" data-target="#user_read" aria-expanded="true" aria-controls="user_read">
                            <?= language()->api_documentation->user->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="user_read" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/user</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/user' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                    <pre class="card-body">
{
    "data": {
        "id":"1",
        "type":"users",
        "email":"example@example.com",
        "billing":{
            "type":"personal",
            "name":"John Doe",
            "address":"Lorem Ipsum",
            "city":"Dolor Sit",
            "county":"Amet",
            "zip":"5000",
            "country":"",
            "phone":"",
            "tax_id":""
        },
        "is_enabled":true,
        "plan_id":"custom",
        "plan_expiration_date":"2025-12-12 00:00:00",
        "plan_settings":{
            ...
        },
        "plan_trial_done":false,
        "language":"english",
        "timezone":"UTC",
        "country":null,
        "date":"2020-01-01 00:00:00",
        "last_activity":"2020-01-01 00:00:00",
        "total_logins":10
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="campaigns">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->campaigns->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#campaigns_read_all" aria-expanded="true" aria-controls="campaigns_read_all">
                            <?= language()->api_documentation->campaigns->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="campaigns_read_all" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/campaigns/</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/campaigns/' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->filters->page ?></td>
                                </tr>
                                <tr>
                                    <td>results_per_page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "pixel_key": "1234567890abcdef",
            "name": "Example",
            "domain": "example.com",
            "include_subdomains": true,
            "branding": {
                "name": "",
                "url": ""
            },
            "is_enabled": true,
            "last_datetime": null,
            "datetime": "2019-05-22 23:40:17"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/campaigns?&page=1",
        "last": "<?= SITE_URL ?>api/campaigns?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/campaigns?&page=1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#campaigns_read" aria-expanded="true" aria-controls="campaigns_read">
                            <?= language()->api_documentation->campaigns->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="campaigns_read" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/campaigns/</span><span class="text-primary">{campaign_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/campaigns/<span class="text-primary">{campaign_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "pixel_key": "1234567890abcdef",
        "name": "Example",
        "domain": "example.com",
        "include_subdomains": true,
        "branding": {
            "name": "",
            "url": ""
        },
        "is_enabled": true,
        "last_datetime": null,
        "datetime": "2019-05-22 23:40:17"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="notifications">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->notifications->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#notifications_read_all" aria-expanded="true" aria-controls="notifications_read_all">
                            <?= language()->api_documentation->notifications->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="notifications_read_all" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/notifications/</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/notifications/' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->filters->page ?></td>
                                </tr>
                                <tr>
                                    <td>results_per_page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "campaign_id": 1,
            "notification_key": "d4752d29a557a9fdc67b0a9a27cbe3b1",
            "name": "Email Collector",
            "type": "EMAIL_COLLECTOR",
            "settings": {
                ...
            },
            "is_enabled": false,
            "last_datetime": null,
            "datetime": "2019-05-29 22:30:29"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/notifications?&page=1",
        "last": "<?= SITE_URL ?>api/notifications?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/notifications?&page=1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#notifications_read" aria-expanded="true" aria-controls="notifications_read">
                            <?= language()->api_documentation->notifications->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="notifications_read" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/notifications/</span><span class="text-primary">{notification_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/notifications/<span class="text-primary">{notification_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "campaign_id": 1,
        "notification_key": "d4752d29a557a9fdc67b0a9a27cbe3b1",
        "name": "Email Collector",
        "type": "EMAIL_COLLECTOR",
        "settings": {
            ...
        },
        "is_enabled": false,
        "last_datetime": null,
        "datetime": "2019-05-29 22:30:29"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="payments">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->payments->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#payments_read_all" aria-expanded="true" aria-controls="payments_read_all">
                            <?= language()->api_documentation->payments->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="payments_read_all" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/payments/</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/payments/' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->filters->page ?></td>
                                </tr>
                                <tr>
                                    <td>results_per_page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "plan_id": 1,
            "processor": "stripe",
            "type": "one_time",
            "frequency": "monthly",
            "email": "example@example.com",
            "name": null,
            "total_amount": "4.99",
            "currency": "USD",
            "status": true,
            "date": "2021-03-25 15:08:58"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/payments?&page=1",
        "last": "<?= SITE_URL ?>api/payments?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/payments?&page=1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#payments_read" aria-expanded="true" aria-controls="payments_read">
                            <?= language()->api_documentation->payments->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="payments_read" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/payments/</span><span class="text-primary">{payment_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/payments/<span class="text-primary">{payment_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "plan_id": 1,
        "processor": "stripe",
        "type": "one_time",
        "frequency": "monthly",
        "email": "example@example.com",
        "name": null,
        "total_amount": "4.99",
        "currency": "USD",
        "status": true,
        "date": "2021-03-25 15:08:58"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="logs">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->users_logs->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#logs_read_all" aria-expanded="true" aria-controls="logs_read_all">
                            <?= language()->api_documentation->users_logs->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="logs_read_all" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/logs/</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/logs/' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->filters->page ?></td>
                                </tr>
                                <tr>
                                    <td>results_per_page</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "type": "login.success",
            "ip": "127.0.0.1",
            "date": "2021-02-03 12:21:40"
        },
        {
            "type": "login.success",
            "ip": "127.0.0.1",
            "date": "2021-02-03 12:23:26"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/payments?&page=1",
        "last": "<?= SITE_URL ?>api/payments?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/payments?&page=1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
