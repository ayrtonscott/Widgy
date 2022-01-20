<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('api-documentation') ?>"><?= language()->api_documentation->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= language()->api_documentation->users_logs->breadcrumb ?></li>
        </ol>
    </nav>

    <h1 class="h4"><?= language()->api_documentation->users_logs->header ?></h1>

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
