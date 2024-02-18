$(document).ready(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

/**
 * Добавление/удаление избранного тикера по западу
 */
$('.favorites').on('click', function (e) {
    e.preventDefault();
    var buttonIcon = $(this).find('i'),
        buttonLink = $(this),
        action = $(this).attr('data-action');
    let link;

    if (action == 'add') {
        link = '/west/favorites-add';
    } else {
        link = '/west/favorites-delete';
    }
    $.ajax({
        url: link,
        method: 'post',
        dataType: 'json',
        data: {
            id: $(this).attr('data-id')
        },
        success: function () {
            if (action == 'add') {
                buttonIcon.removeClass('bi-star');
                buttonIcon.addClass('bi-star-fill');
                buttonIcon.addClass('added');
                buttonLink.attr('data-original-title', 'Из избранного');
                buttonLink.attr('data-action', 'delete');
            } else {
                buttonIcon.removeClass('added');
                buttonIcon.removeClass('bi-star-fill');
                buttonIcon.addClass('bi-star');
                buttonLink.attr('data-original-title', 'В избранное');
                buttonLink.attr('data-action', 'add');
            }
        }
    });
});