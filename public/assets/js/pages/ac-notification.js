'use strict';
document.addEventListener("DOMContentLoaded", function () {
    if($("#btn-default").length > 0) {    
        document.querySelector("#btn-default").addEventListener('click', function () {
            notifier.show('Hello!', 'I am a default notification.', '', '', 0);
        });
    }
    if($("#btn-info").length > 0) {    
        document.querySelector("#btn-info").addEventListener('click', function () {
            notifier.show('Reminder!', 'You have a meeting at 10:30 AM.', 'info', '', 0);
        });
    }
    if($("#btn-success").length > 0) {    
        document.querySelector("#btn-success").addEventListener('click', function () {
            notifier.show('Well Done!', 'You just submit your resume successfuly.', 'success', '', 0);
        });
    }
    if($("#btn-warning").length > 0) {    
        document.querySelector("#btn-warning").addEventListener('click', function () {
            notifier.show('Warning!', 'The data presented here can be change.', 'warning', '', 0);
        });
    }
    if($("#btn-danger").length > 0) {    
        document.querySelector("#btn-danger").addEventListener('click', function () {
            notifier.show('Sorry!', 'Could not complete your transaction.', 'danger', '', 0);
        });
    }

    if($("#btn-default-i").length > 0) {    
        document.querySelector("#btn-default-i").addEventListener('click', function () {
            notifier.show('Default!', 'I am a default notification.', '', '../assets/images/notification/clock-48.png', 0);
        });
    }
    if($("#btn-info-i").length > 0) {    
        document.querySelector("#btn-info-i").addEventListener('click', function () {
            notifier.show('Reminder!', 'You have a meeting at 10:30 AM.', 'info', '../assets/images/notification/survey-48.png', 0);
        });
    }
    if($("#btn-success-i").length > 0) {    
        document.querySelector("#btn-success-i").addEventListener('click', function () {
            notifier.show('Well Done!', 'You just submit your resume successfuly.', 'success', '../assets/images/notification/ok-48.png', 0);
        });
    }
    if($("#btn-warning-i").length > 0) {    
        document.querySelector("#btn-warning-i").addEventListener('click', function () {
            notifier.show('Warning!', 'The data presented here can be change.', 'warning', '../assets/images/notification/medium_priority-48.png', 0);
        });
    }
    if($("#btn-danger-i").length > 0) {    
        document.querySelector("#btn-danger-i").addEventListener('click', function () {
            notifier.show('Sorry!', 'Could not complete your transaction.', 'danger', '../assets/images/notification/high_priority-48.png', 0);
        });
    }

    if($("#btn-default-ac").length > 0) {    
        document.querySelector("#btn-default-ac").addEventListener('click', function () {
            notifier.show('Default!', 'I am a default notification.', '', '../assets/images/notification/clock-48.png', 4000);
        });
    }
    if($("#btn-info-ac").length > 0) {    
        document.querySelector("#btn-info-ac").addEventListener('click', function () {
            notifier.show('Reminder!', 'You have a meeting at 10:30 AM.', 'info', '../assets/images/notification/survey-48.png', 4000);
        });
    }
    if($("#btn-success-ac").length > 0) {    
        document.querySelector("#btn-success-ac").addEventListener('click', function () {
            notifier.show('Well Done!', 'You just submit your resume successfuly.', 'success', '../assets/images/notification/ok-48.png', 4000);
        });
    }
    if($("#btn-warning-ac").length > 0) {    
        document.querySelector("#btn-warning-ac").addEventListener('click', function () {
            notifier.show('Warning!', 'The data presented here can be change.', 'warning', '../assets/images/notification/medium_priority-48.png', 4000);
        });
    }
    if($("#btn-danger-ac").length > 0) {    
        document.querySelector("#btn-danger-ac").addEventListener('click', function () {
            notifier.show('Sorry!', 'Could not complete your transaction.', 'danger', '../assets/images/notification/high_priority-48.png', 4000);
        });
    }

    var notificationId;

    var showNotification = function () {
        notificationId = notifier.show('Reminder!', 'You have a meeting at 10:30 AM.', 'info', '../assets/images/notification/survey-48.png', 4000);
    };

    var hideNotification = function () {
        notifier.hide(notificationId);
    };
    if($("#btn-nt-show").length > 0) {
        document.querySelector('#btn-nt-show').addEventListener('click', showNotification);
    }
    if($("#btn-nt-hide").length > 0) {
        document.querySelector('#btn-nt-hide').addEventListener('click', hideNotification);
    }
});