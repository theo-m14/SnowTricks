function addEventOnLoadMore(){
    document.querySelector('.pagination').addEventListener('click',(event) => {
        event.preventDefault()
        event.stopPropagation()
        let link = document.querySelector('.pagination a')
    
        let nextPageUrl = link.getAttribute('href')
        console.log(nextPageUrl)
    
        fetch(nextPageUrl).then((response) => {
            return response.json()
        }).then(data => {
            console.log(data)
            let tricksContainer = document.querySelector('.tricks-container')
            tricksContainer.removeChild(document.querySelector('.pagination'))
            tricksContainer.innerHTML += data.content
        })
    })
}

window.onload = () => {
    addEventOnLoadMore()
}