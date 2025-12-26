document.addEventListener('DOMContentLoaded', () => {

    const limit = 5;
    const words = 60;



    const button = document.getElementById('loadMore');
    const container = document.getElementById('posts-container');

    button.addEventListener('click', () => {
        fetch(`/ajax/home/loadmore?limit=${limit}&words=${words}&shownids=` + window.shownPostIds.join(','), {method: 'POST'})
            .then(res => res.json())
            .then(data => {
                if(!data.success) {
                    console.error(data.error);
                    return;
                }
        
            data.posts.forEach(post => {
                container.insertAdjacentHTML(
                    'beforeend',
                    `<div class="post">

                        <div class="title">
                            <a class="title_name" href="/post?post_id=${post.post_id}">${post.title}</a>
                            <a class="author_name" href="/userprofile?id=${post.author_id}">${post.author}</a>
                        </div>

                        <div class="content">
                            ${post.content}...
                        </div>

                        <div class="readButton">
                            <a href="/post?post_id=${post.post_id}">Read more</a>
                        </div>

                    </div>`
                );

                window.shownPostIds.push(post.post_id);
            });
            console.log(data);

            if(!data.hasMore) {
                button.remove();
            }
        });
    });
});