<div id="relationManagePopup">
    <?= Form::open() ?>
        <input type="hidden" name="_relation_field" value="<?= $relationField ?>" />
        <input type="hidden" name="_relation_mode" value="list" />

        <div class="modal-header">
            <h4 class="modal-title"><?= e($relationManageTitle) ?></h4>
            <button type="button" class="btn-close" data-dismiss="popup"></button>
        </div>

        <div class="list-flush" data-list-linkage="<?= $relationManageWidget->getId() ?>">
            <?php if ($relationSearchWidget): ?>
                <?= $relationSearchWidget->render() ?>
            <?php endif ?>
            <?php if ($relationManageFilterWidget): ?>
                <?= $relationManageFilterWidget->render() ?>
            <?php endif ?>
            <?= $relationManageWidget->render() ?>
        </div>

        <div class="modal-footer">
            <?php if ($relationManageWidget->showCheckboxes): ?>
                <button
                    type="button"
                    class="btn btn-primary"
                    data-request="onRelationManageAdd"
                    data-dismiss="popup"
                    data-request-success="oc.relationBehavior.changed('<?= e($relationField) ?>', 'added')"
                    data-stripe-load-indicator>
                    <?= e(trans('backend::lang.relation.add_selected')) ?>
                </button>
            <?php endif ?>
            <button
                type="button"
                class="btn btn-default"
                data-dismiss="popup">
                <?= e(trans('backend::lang.relation.cancel')) ?>
            </button>
        </div>
    <?= Form::close() ?>
</div>
