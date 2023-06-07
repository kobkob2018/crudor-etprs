<table id="current_phone_calls_return">
    <?php foreach($info['phone_list'] as $phone_data): ?>
        <tr  class="phone_list_row" data-state="phone" data-uniqid="<?php echo $phone_data['id']; ?>" >
            <td class="current_call_td_userid hide"><?php echo $phone_data['user_id']; ?></td>
            <td class="current_call_td_from"><a target="new" href="<?= get_config('master_url') ?>/?aff_id=18&custom_phone=<?php echo $phone_data['call_from']; ?>&custom_cat=<?php echo $phone_data['custom_cat']; ?>&link_uniq=<?php echo $phone_data['id']; ?>"><?php echo $phone_data['call_from']; ?></a></td>
            <td class="current_call_td_to"><?php echo $phone_data['call_to']; ?></td>
            <td class="current_call_td_did hide"><?php echo $phone_data['did']; ?></td>
            <td class="current_call_td_date hide"><?php echo $phone_data['call_date']; ?></td>
            <td class="current_call_th_hour"><?php echo $phone_data['call_hour']; ?></td>
            <td class="current_call_td_link_sys_id hide"><?php echo $phone_data['link_sys_identity']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="phone_list_row" data-state="done">

    </tr>
</table>