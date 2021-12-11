<?php defined('ALTUMCODE') || die() ?>

<?php if(count($data->codes)): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3"><i class="fa fa-fw fa-xs fa-tags text-primary-900 mr-2"></i> <?= language()->admin_codes->header ?></h1>

        <div class="col-auto p-0">
            <a href="<?= url('admin/code-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->admin_codes->create ?></a>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= language()->admin_codes->table->code ?></th>
                <th><?= language()->admin_codes->table->type ?></th>
                <th><?= language()->admin_codes->table->discount ?></th>
                <th><?= language()->admin_codes->table->quantity ?></th>
                <th><?= language()->admin_codes->table->redeemed_codes ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->codes as $row): ?>

                <tr data-code-id="<?= $row->code_id ?>">
                    <td>
                        <div class="d-flex flex-column">
                            <a href="<?= url('admin/code-update/' . $row->code_id) ?>"><?= $row->name ?></a>
                            <span><code><?= $row->code ?></code></span>
                        </div>
                    </td>
                    <td><?= $row->type ?></td>
                    <td><?= $row->discount . '%' ?></td>
                    <td><?= $row->quantity ?></td>
                    <td><?= nr($row->redeemed) ?></td>
                    <td><?= include_view(THEME_PATH . 'views/admin/codes/admin_code_dropdown_button.php', ['id' => $row->code_id]) ?></td>
                </tr>

            <?php endforeach ?>
            </tbody>
        </table>
    </div>

<?php else: ?>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center">
        <div class="mb-3 mb-md-0 mr-md-5">
            <i class="fa fa-fw fa-7x fa-tags text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3"><?= language()->admin_codes->header_no_data ?></h1>
            <p class="text-muted"><?= language()->admin_codes->subheader_no_data ?></p>

            <div>
                <a href="<?= url('admin/code-create') ?>" class="btn btn-primary"><i class="fa fa-fw fa-sm fa-plus-circle"></i> <?= language()->admin_codes->create ?></a>
            </div>
        </div>
    </div>

<?php endif ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/codes/code_delete_modal.php'), 'modals'); ?>

