
<div class="assign-select-form-wrap">
    <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
        <input type="hidden" name="sendAction" value="set_assignsSend" />
        <?php foreach($this->data['assign_trees'][$this->data['assign_info']['table']]['children'] as $assign_item): ?>
            <?php $this->add_recursive_assign_select_view_for_cat($assign_item); ?>
        <?php endforeach; ?>

        <div class="submit-wrap">
            <button type = "submit">שליחה</button>
        </div>
    </form>
</div>

