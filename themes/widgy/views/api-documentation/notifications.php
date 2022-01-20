<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('api-documentation') ?>"><?= language()->api_documentation->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= language()->api_documentation->notifications->breadcrumb ?></li>
        </ol>
    </nav>

    <h1 class="h4"><?= language()->api_documentation->notifications->header ?></h1>

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
                                <td>
                                    <span class="badge badge-info"><?= language()->api_documentation->optional ?></span>
                                    <span class="badge badge-secondary"><?= language()->api_documentation->int ?></span>
                                </td>
                                <td><?= language()->api_documentation->filters->page ?></td>
                            </tr>
                            <tr>
                                <td>results_per_page</td>
                                <td>
                                    <span class="badge badge-info"><?= language()->api_documentation->optional ?></span>
                                    <span class="badge badge-secondary"><?= language()->api_documentation->int ?></span>
                                </td>
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
