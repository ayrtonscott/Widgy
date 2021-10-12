<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="custom_branding_campaign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= language()->custom_branding_campaign_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="custom_branding_campaign" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="custom_branding" />
                    <input type="hidden" name="campaign_id" value="" />

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-random text-muted mr-1"></i> <?= language()->custom_branding_campaign_modal->input->name ?></label>
                        <input type="text" class="form-control" name="name" value="<?= $data->campaign->branding->name ?? '' ?>" />
                        <small class="form-text text-muted"><?= language()->custom_branding_campaign_modal->input->name_help ?></small>
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= language()->custom_branding_campaign_modal->input->url ?></label>
                        <input type="url" class="form-control" name="url" value="<?= $data->campaign->branding->url ?? '' ?>" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= language()->global->update ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#custom_branding_campaign').on('show.bs.modal', event => {
        let campaign_id = $(event.relatedTarget).data('campaign-id');
        let branding_name = $(event.relatedTarget).data('branding-name');
        let branding_url = $(event.relatedTarget).data('branding-url');

        $(event.currentTarget).find('input[name="campaign_id"]').val(campaign_id);
        $(event.currentTarget).find('input[name="name"]').val(branding_name);
        $(event.currentTarget).find('input[name="url"]').val(branding_url);
    });

    $('form[name="custom_branding_campaign"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'campaigns-ajax',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                if (data.status == 'error') {
                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Hide modal */
                    $('#custom_branding_campaign').modal('hide');

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
