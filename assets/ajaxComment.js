let editCommentBtn = document.querySelectorAll('.editBtn')


editCommentBtn.forEach((btn) => {
    btn.addEventListener('click',(e) => editComment(e) )
})

function editComment(e)
{
    let commentContainer = e.target.parentElement
    let commentContent = commentContainer.querySelector('.commentContent')
    let textarea = document.createElement('textarea');
    textarea.classList.add('commentContent');
    textarea.value = commentContent.textContent;
    commentContainer.replaceChild(textarea, commentContent);

    let submitBtn = document.createElement('button');
    submitBtn.textContent = 'Valider';
    commentContainer.insertBefore(submitBtn, textarea.nextSibling);
    submitBtn.addEventListener('click', (e) => {
        e.preventDefault();

        let commentContainer = e.target.parentElement
        let commentContent = commentContainer.querySelector('.commentContent')


        let formData = new FormData();
        formData.append('content', commentContent.value);
        formData.append('comment-id', commentContainer.getAttribute('comment-id'));

        const data = new URLSearchParams();
        for (const pair of formData) {
            data.append(pair[0], pair[1]);
        }

        fetch('/comment/editComment', {
            method: 'PUT',
            body: data
        })
        .then(response => {
            if(response.code == 403)
            {
                throw new Error("Vous devez etre propriétaire du message pour le modifier");
            }
            return response.json()
        })
        .then(data => {
            if(data.error){
                throw new Error(data.error);
            }
            console.log('edited')
            let editedComment = document.createElement('p')
            editedComment.textContent = commentContent.value;
            editedComment.classList.add('commentContent');
            console.log(editedComment);
            console.log(textarea);
            textarea.replaceWith(editedComment);
            submitBtn.remove()
            commentContainer.querySelector('.commentError').textContent = ''
        })
        .catch(error => {
            commentContainer.querySelector('.commentError').textContent = error.message
        });
    });
}