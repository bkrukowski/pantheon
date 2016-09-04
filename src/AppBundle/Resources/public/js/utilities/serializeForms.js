window.utilities = window.utilities || {};

window.utilities.serializeForm = function ($form) {
    if (!($form instanceof jQuery)) {
        throw 'Object $form has to be instance of jQuery!';
    }
    var result = {};
    var serialized = $form.serializeArray();
    for (var i = 0; i < serialized.length; i++) {
        result[serialized[i].name] = serialized[i].value;
    }
    return result;
};