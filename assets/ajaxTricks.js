function addEventOnLoadMore(){
    document.querySelector('.pagination').addEventListener('click',(event) => {
        event.preventDefault()
        event.stopPropagation()
        let link = document.querySelector('.pagination a')
    
        let nextPageUrl = link.getAttribute('href')

        let tricksContainer = document.querySelector('.tricks-container')
    
        fetch(nextPageUrl).then((response) => {
            tricksContainer.removeChild(document.querySelector('.pagination'))
            return response.json()
        }).then(data => {            
            tricksContainer.innerHTML += data.content
            addEventOnLoadMore()
        })
    })
}

window.onload = () => {
    addEventOnLoadMore()
}