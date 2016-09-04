galleryApp.factory('flashMessages', function () {
    var
        messages = {},
        counter = 0,
        toRemove = [];
    return {
        pushError: function (message, title) {
            messages[counter] = {
                message: message,
                title: title,
                index: counter
            };
            counter++;
        },
        getAll: function () {
            var result = [];
            for (var i in messages) {
                result.push(messages[i]);
                toRemove.push(i);
            }
            return result;
        },
        clearDisplayed: function () {
            for (var i = 0; i < toRemove.length; i++) {
                delete messages[i];
            }
            toRemove = [];
        },
        remove: function (index) {
            delete messages[index];
        }
    };
});