<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fa fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="admin/language-update/<?= $data->id ?>"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= l('global.edit') ?></a>
        <?php if($data->id != 'en'): ?>
        <a href="#" data-toggle="modal" data-target="#language_delete_modal" data-language-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= l('global.delete') ?></a>
        <?php endif ?>
    </div>
</div>
