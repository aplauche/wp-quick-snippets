document.addEventListener('DOMContentLoaded', () => {

  function handleFavoriteButton(button, postId) {
    fetch(favoriteAjax.ajax_url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        action: 'toggle_favorite',
        nonce: favoriteAjax.nonce,
        post_id: postId,
      }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log(data)
          if (data.data.favorites.includes(Number(postId))) {
            button.classList.add('favorited');
            //button.innerText = "Unfavorite"
          } else {
            button.classList.remove('favorited');
            //button.innerText = "Favorite"
          }
        } else {
          alert(data.data);
        }
      });
  }

  // Handle Favorite AJAX
  document.querySelectorAll('.favorite-button').forEach(button => {
      button.addEventListener('click', function () {
          handleFavoriteButton(button, button.dataset.post);
      });
  });

  // handle the favorite ajax in dynamically generated lists
  document.querySelectorAll('.posts-list').forEach(section => {
      section.addEventListener('click', function (e) {
          if(e.target.classList.contains('favorite-button')) {
              handleFavoriteButton(e.target, e.target.dataset.post);
          }
      });
  });

});