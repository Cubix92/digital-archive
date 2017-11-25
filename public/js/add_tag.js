function add_tag() {
    var currentCount = $('form fieldset#tag-collection > fieldset').length;
    var template = $('form fieldset#tag-collection > span').data('template');
    template = template.replace(/__index__/g, currentCount);

    $('form fieldset#tag-collection').append(template);

    return false;
}