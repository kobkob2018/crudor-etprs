function add_hour_group(a_el){

    const place_holder = a_el.closest(".hour-groups-holder");
    const field_key = place_holder.dataset.field_key;
    let group_index = parseInt(place_holder.dataset.group_index);
    const template = document.querySelector("."+ field_key+"-apitoui .hour-groups-template");
    const new_group = template.cloneNode(true);

    new_group.querySelectorAll(".time-group-input").forEach(input_el => {
        let input_name = input_el.name;
        input_name = input_name.replaceAll("{{field_key}}", field_key);
        input_name = input_name.replaceAll("{{group_id}}", group_index);   
        input_el.name = input_name;
    });

    place_holder.insertBefore(new_group, a_el);
    group_index++;
    place_holder.dataset.group_index = group_index;
    
}

function remove_hour_group(a_el){
    const hour_group = a_el.closest(".hour-groups-template");
    hour_group.remove();
}