import $ from 'jquery';

class Like {
    constructor() {
        this.events();
    }

    events() {
        $('.like-box').on('click', this.ourClickDispatcher.bind(this))
    }

    ourClickDispatcher(e) {
        var currentLikeBox = $(e.target).closest('.like-box');
        // The jquery data method only checks for data attributes that were loaded the first time a page rendered.
        // Hence to toggle between liking and unliking which would change the data attributes we need to use attr.
        if (currentLikeBox.attr('data-exists') == 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                // For destructive requests we need to send this nonce when user is authenticated
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/university/v1/manageLike`,
            type: 'POST',
            data: {
                'professorId': currentLikeBox.data('professor')
            },
            success: (response) => {
                currentLikeBox.attr('data-exists', 'yes');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10);
                likeCount++;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like', response);
            },
            error: (response) => {
                console.log(response)
            }
        });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                // For destructive requests we need to send this nonce when user is authenticated
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            data: {
                'likeId': currentLikeBox.attr('data-like')
            },
            url: `${universityData.root_url}/wp-json/university/v1/manageLike`,
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10);
                likeCount--;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like', '');
            },
            error: (response) => {
                console.log(response)
            }
        });
    }
}

export default Like;