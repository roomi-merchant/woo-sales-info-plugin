jQuery(document).ready(function ($) {
    $('.add-more-btn a').click(function () {
        $('.fields').clone().insertAfter('.fields');
    })
})