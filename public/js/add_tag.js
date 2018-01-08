function add_remove_button() {
    $('span.remove-element').remove();

    $('form fieldset#tag-collection fieldset').each(function(index, element){
        $(element).append("<span class='remove-element'>✖</span>");
    });

    $('span.remove-element').off('click').click(function() {
        $(this).closest('fieldset').remove();
    });
}

function add_tag() {
    var currentCount = $('form fieldset#tag-collection > fieldset').length;
    var template = $('form fieldset#tag-collection > span').data('template');
    template = template.replace(/__index__/g, currentCount);

    $('form fieldset#tag-collection').append(template);

    add_remove_button();

    return false;
}

$(document).ready(function() {

    $('form fieldset#tag-collection fieldset input.form-control').each(function(index, element) {
        $(element).prop('readonly', true);
    });

    add_remove_button();
});