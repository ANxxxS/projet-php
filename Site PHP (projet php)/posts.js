document.addEventListener('DOMContentLoaded', function() {
    loadPosts();
    
    const postForm = document.getElementById('postForm');
    postForm.addEventListener('submit', function(e) {
        e.preventDefault();
        createPost();
    });
});

function loadPosts() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_posts.php', true);
    
    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        displayPosts(response.posts);
    };
    
    xhr.send();
}

function createPost() {
    const form = document.getElementById('postForm');
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'create_post.php', true);
    
    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        form.reset();
        loadPosts();
        alert('Post créé avec succès!');
    };
    
    xhr.send(formData);
}

function displayPosts(posts) {
    const container = document.getElementById('postsContainer');
    container.innerHTML = '';
    
    if (posts.length === 0) {
        container.innerHTML = '<p>Aucun post disponible</p>';
        return;
    }
    
    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.setAttribute('data-post-id', post.id);
        
        const date = new Date(post.date);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        
        let profilePhoto = 'images/noimage.jpg';
        if (post.user && post.user.photo && post.user.photo !== 'noimage.jpg') {
            profilePhoto = 'user_data/' + post.user.id + '/' + post.user.photo;
        }
        
        postElement.innerHTML = `
            <div class="post-header">
                <img src="${profilePhoto}" alt="Profile">
                <h3>${post.user ? post.user.name : 'Utilisateur inconnu'}</h3>
            </div>
            <div class="post-content">
                ${post.text ? `<p>${post.text}</p>` : ''}
                ${post.image ? `<img src="user_post/${post.user ? post.user.id : 'unknown'}/${post.image}" alt="Post image">` : ''}
            </div>
            <div class="post-footer">
                <div class="post-actions">
                    <button type="button" class="like-button" data-post-id="${post.id}">
                        <i class="material-icons">thumb_up</i> ${post.likes || 0}
                    </button>
                    <button type="button" class="dislike-button" data-post-id="${post.id}">
                        <i class="material-icons">thumb_down</i> ${post.dislikes || 0}
                    </button>
                    <button type="button" class="comments-toggle" data-post-id="${post.id}">
                        <i class="material-icons">comment</i> Commentaires
                    </button>
                </div>
                <div class="post-date">${formattedDate}</div>
            </div>
            <div class="comments-section" id="comments-${post.id}" style="display: none;">
                <div class="comments-list" id="comments-list-${post.id}"></div>
                <form id="comment-form-${post.id}" class="comment-form">
                    <input type="text" name="comment_text" placeholder="Ajouter un commentaire..." required>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        `;
        
        container.appendChild(postElement);
    });
    
    addPostEventListeners();
}

function addPostEventListeners() {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            reactToPost(postId, 'like');
        });
    });
    
    document.querySelectorAll('.dislike-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            reactToPost(postId, 'dislike');
        });
    });
    
    document.querySelectorAll('.comments-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            toggleComments(postId);
        });
    });
    
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.id.replace('comment-form-', '');
            addComment(postId);
        });
    });
}

function reactToPost(postId, reactionType) {
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('reaction_type', reactionType);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'react_post.php', true);
    
    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        
        const postElement = document.querySelector(`[data-post-id="${postId}"]`);
        const likeButton = postElement.querySelector('.post-actions .like-button');
        const dislikeButton = postElement.querySelector('.post-actions .dislike-button');
        
        likeButton.innerHTML = `<i class="material-icons">thumb_up</i> ${response.likes}`;
        dislikeButton.innerHTML = `<i class="material-icons">thumb_down</i> ${response.dislikes}`;
    };
    
    xhr.send(formData);
}

function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    
    if (commentsSection.style.display === 'none') {
        commentsSection.style.display = 'block';
        loadComments(postId);
    } else {
        commentsSection.style.display = 'none';
    }
}

function loadComments(postId) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `get_comments.php?post_id=${postId}`, true);
    
    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        displayComments(postId, response.comments);
    };
    
    xhr.send();
}

function displayComments(postId, comments) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    commentsList.innerHTML = '';
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<p>Aucun commentaire</p>';
        return;
    }
    
    comments.forEach(comment => {
        let profilePhoto = 'images/noimage.jpg';
        if (comment.user && comment.user.photo && comment.user.photo !== 'noimage.jpg') {
            profilePhoto = 'user_data/' + comment.user.id + '/' + comment.user.photo;
        }
        
        const date = new Date(comment.date);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        
        const commentElement = document.createElement('div');
        commentElement.className = 'comment';
        commentElement.innerHTML = `
            <div class="comment-header">
                <img src="${profilePhoto}" alt="Profile" width="30" height="30" style="border-radius: 50%;">
                <strong>${comment.user ? comment.user.name : 'Utilisateur inconnu'}</strong>
                <span>${formattedDate}</span>
            </div>
            <div class="comment-content">
                <p>${comment.text || ''}</p>
            </div>
        `;
        
        commentsList.appendChild(commentElement);
    });
}

function addComment(postId) {
    const form = document.getElementById(`comment-form-${postId}`);
    const commentInput = form.querySelector('input[name="comment_text"]');
    const commentText = commentInput.value.trim();
    
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('comment_text', commentText);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_comment.php', true);
    
    xhr.onload = function() {
        commentInput.value = '';
        loadComments(postId);
    };
    
    xhr.send(formData);
}