var
    $inject = ['$uibModal'],
    dialogs = function ($modal) {
        return {
            confirm: function (onAccept, onReject) {
                return $modal.open({
                    animation: true,
                    templateUrl: "dialogs/confirm.html",
                    controller: "controller.dialogs.confirm",
                    size: "lg"
                }).result.then(onAccept, onReject);
            }
        };
};
dialogs.$inject = $inject;
galleryApp.factory('dialogs', dialogs);