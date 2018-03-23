(function () {
    var last_page = 1;

    function route() {
        let location = window.location.href.split('#');

        if (
            location.length >= 2 &&
            location[1] != '' &&
            location[1] != '/' &&
            location[1].indexOf('/page/') === -1
        ) {
            loadPost(location[1].replace(/\//g, ''));
        } else if (location.length >= 2 && location[1].indexOf('/page/') !== -1) {
            loadFeed(location[1].replace(/\//g, '').replace('page', ''));
        } else {
            loadFeed(1);
        }
    }

    function assemblePost(post) {
        var html = $('#postTemplate').html();
        return html
            .replace(/{{DATE}}/g, post._atto.date_display)
            .replace(/{{PERMALINK}}/g, post.url)
            .replace(/{{TITLE}}/g, post.title)
            .replace(/{{CONTENT}}/g, post.content_html);
    }

    function showLoading() {
        $('#loadingBlock').show();
        $('#pagingBlock').hide();
        $('#postsBlock').hide();
        $('#navBlock').hide();
    }

    function loadedFeed(page, max) {
        $('#pagingBlock .next').attr('href', '/#/page/' + (page + 1));
        if (page + 1 > max) {
            $('#pagingBlock .next').hide();
        } else {
            $('#pagingBlock .next').show();
        }

        $('#pagingBlock .prev').attr('href', '/#/page/' + (page - 1));
        if (page - 1 < 1) {
            $('#pagingBlock .prev').hide();
        } else {
            $('#pagingBlock .prev').show();
        }

        $('#pagingBlock .current').html(page);
        $('#pagingBlock .total').html(max);

        $('#loadingBlock').hide();
        $('#pagingBlock').show();
        $('#postsBlock').show();
        $('#navBlock').hide();
    }

    function loadedPost() {
        $('#navBlock .back').attr('href', '/#/page/' + last_page);

        $('#loadingBlock').hide();
        $('#pagingBlock').hide();
        $('#postsBlock').show();
        $('#navBlock').show();
    }

    function loadFeed(page) {
        last_page = page;

        showLoading();
        $.getJSON(window.base_url + 'data/feeds/' + page + '.json', function (data) {
            $('#postsBlock').html('');
            for (var post in data.items) {
                $('#postsBlock').append( assemblePost(data.items[post]) );
            }
            loadedFeed(data._atto.current_page, data._atto.total_pages);
        }).fail(function() {
            alert('Unable to load feed!');
        });
    }

    function loadPost(slug) {
        showLoading();
        $.getJSON(window.base_url + 'data/posts/' + slug + '.json', function (data) {
            $('#postsBlock').html( assemblePost(data) );
            loadedPost();
        }).fail(function() {
            alert('Post not found!');
        });
    }

    $(window).on('hashchange', function () {
        route();
    });

    route();
})();
