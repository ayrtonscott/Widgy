<?php defined('ALTUMCODE') || die() ?>

<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/codes') ?>"><?= language()->admin_codes->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= language()->admin_code_create->breadcrumb ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fa fa-fw fa-xs fa-tags text-primary-900 mr-2"></i> <?= language()->admin_code_create->header ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= language()->admin_codes->main->name ?></label>
                <input type="text" id="name" name="name" class="form-control form-control-lg" required="required" />
            </div>

            <div class="form-group">
                <label for="type"><?= language()->admin_codes->main->type ?></label>
                <select id="type" name="type" class="form-control form-control-lg">
                    <option value="discount"><?= language()->admin_codes->main->type_discount ?></option>
                    <option value="redeemable"><?= language()->admin_codes->main->type_redeemable ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="code"><?= language()->admin_codes->main->code ?></label>
                <input type="text" id="code" name="code" class="form-control form-control-lg" required="required" />
            </div>

            <div id="discount_container" class="form-group">
                <label for="discount"><?= language()->admin_codes->main->discount ?></label>
                <input id="discount" type="number" min="1" max="99" name="discount" class="form-control form-control-lg" value="1" />
                <small class="form-text text-muted"><?= language()->admin_codes->main->discount_help ?></small>
            </div>

            <div id="days_container" class="form-group">
                <label for="days"><?= language()->admin_codes->main->days ?></label>
                <input id="days" type="number" min="1" max="999999" name="days" class="form-control form-control-lg" value="1" />
                <small class="form-text text-muted"><?= language()->admin_codes->main->days_help ?></small>
            </div>

            <div class="form-group">
                <label for="quantity"><?= language()->admin_codes->main->quantity ?></label>
                <input type="number" min="1" id="quantity" name="quantity" class="form-control form-control-lg" value="1" />
                <small class="form-text text-muted"><?= language()->admin_codes->main->quantity_help ?></small>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->create ?></button>
        </form>

    </div>
</div>

<?php ob_start() ?>
<script>
    let checker = () => {
        let type = document.querySelector('select[name="type"]').value;

        switch(type) {
            case 'discount':
                document.querySelector('#discount_container').style.display = 'block';
                document.querySelector('#days_container').style.display = 'none';
                break;

            case 'redeemable':
                document.querySelector('#discount_container').style.display = 'none';
                document.querySelector('#days_container').style.display = 'block';
                break;
        }
    };

    checker();

    document.querySelector('select[name="type"]').addEventListener('change', checker);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
