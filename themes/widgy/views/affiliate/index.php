<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('affiliate.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-lg-7 mb-4 mb-lg-0">
            <h1 class="h3"><?= l('affiliate.header') ?></h1>
            <p class="text-muted m-0"><?= l('affiliate.subheader') ?></p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 col-lg mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center">
                <img src="<?= ASSETS_FULL_URL . 'images/affiliate.svg' ?>" class="img-fluid col-10" />
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-gray-50 border-0 h-100">
                        <div class="card-body d-flex flex-column">
                            <span class="h3 text-primary"><?= sprintf(l('affiliate.commission_percentage.header'), settings()->affiliate->commission_percentage . '%') ?></span>
                            <span class="text-muted"><?= l('affiliate.commission_percentage.subheader_' . settings()->affiliate->commission_type) ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card bg-gray-50 border-0 h-100">
                        <div class="card-body d-flex flex-column">
                            <span class="h3 text-primary"><?= sprintf(l('affiliate.minimum_withdrawal_amount.header'), settings()->affiliate->minimum_withdrawal_amount . ' ' . settings()->payment->currency) ?></span>
                            <span class="text-muted"><?= l('affiliate.minimum_withdrawal_amount.subheader') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="h4 mb-5"><?= l('affiliate.how.header') ?></h2>

        <div class="row justify-content-between">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card bg-gray-50 border-0 h-100">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-gray-100 text-gray-800 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-user-plus fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">1. <?= l('affiliate.how.one') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.one_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card bg-gray-50 border-0 h-100">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-gray-100 text-gray-800 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-link fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">2. <?= l('affiliate.how.two') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.two_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card bg-gray-50 border-0 h-100">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-gray-100 text-gray-800 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-wallet fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">3. <?= l('affiliate.how.three') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.three_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card bg-gray-50 border-0 h-100">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-gray-100 text-gray-800 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-money-bill fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">4. <?= l('affiliate.how.four') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.four_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="mt-8 bg-primary-100 py-6">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-around align-items-lg-center">
                <div>
                    <h2 class="text-primary-900"><?= l('affiliate.cta.header') ?></h2>
                    <p class="text-primary-800"><?= l('affiliate.cta.subheader') ?></p>
                </div>

                <div>
                    <a href="<?= url('register') ?>" class="btn btn-primary index-button"><?= l('affiliate.cta.register') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
