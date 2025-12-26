document.addEventListener('DOMContentLoaded', () => {
    let offset = 3;
    const limit = 5;
    const button = document.getElementById('loadMore');
    const container = document.getElementById('posts-container');

    button.addEventListener('click', () => {
        fetch(`/ajax/user/loadmore?offset=${offset}&limit=${limit}`, {method: 'POST'})
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
                            <div class="title"><a class="title_name" href="/post?post_id=${post.post_id}">${post.title}</a></div>
                            <div class="text">${post.content}...</div>
                            <div class="edit">
                                <a class="editbutton" href="/posts/edit?post_id=${post.post_id}">Edit</a> 
                                <a class="deletebutton" href="/posts/delete?post_id=${post.post_id}">Delete</a>
                            </div>
                    </div>`
                );
            });
            console.log(data);
            offset += limit;

            if(!data.hasMore) {
                
                button.remove();
            }
        });
    });
});