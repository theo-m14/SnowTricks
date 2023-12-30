//Change upload file name on btn on change
function listenerOnUpload() {
  document.querySelectorAll('input[type="file"]').forEach((uploadBtn) => {
    uploadBtn.addEventListener("change", (e) => {
      let image = e.target.parentElement.parentElement.parentElement.parentElement.querySelector(".tricksImage");
      image.src = URL.createObjectURL(e.target.files[0]);
    });
  });
}

//Add delete link on existing media
document.querySelectorAll("ul.tricksVideos li").forEach((tricksVideos) => {
  addTagFormDeleteLink(tricksVideos);
  createIframe(tricksVideos.querySelector('input'))
  tricksVideos.querySelector('input').addEventListener('focusout',(e) => {
    createIframe(e.target)
  })
});

document.querySelectorAll("ul.tricksImages li").forEach((tricksImages) => {
  addTagFormDeleteLink(tricksImages);
});

//Add new video input
document.querySelectorAll(".addTricksVideo").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    addFormToCollection(e);
  });
});

//Add new image input
document.querySelectorAll(".addTricksImage").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    addFormToCollection(e);
    setTimeout(() => {
      listenerOnUpload();
    }, 1000);
  });
});

//Adding collection item on input
function addFormToCollection(e) {
  console.log(e.target)
  let target = e.target
  if (e.target.tagName.toLowerCase() === 'i') {
    target = target.parentElement
  }
  let collectionName = target.dataset.collectionHolderClass;
  const collectionHolder = document.querySelector("." + collectionName);

  const item = document.createElement("li");

  console.log(collectionHolder)
  console.log(collectionName)
  item.innerHTML = collectionHolder.dataset.prototype.replace(
    /__name__/g,
    collectionHolder.dataset.index
  );

  if (collectionName == "tricksImages") {
    const image = document.createElement("img");
    image.classList.add("tricksImage");

    item.appendChild(image);
  }

  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
  addTagFormDeleteLink(item);
}

//create delete button
function addTagFormDeleteLink(item) {
  const removeFormButton = document.createElement("button");
  let removeIcon = document.createElement('i');
  removeIcon.classList.add('fa-solid');
  removeIcon.classList.add('fa-trash');
  removeFormButton.append(removeIcon);

  item.append(removeFormButton);

  removeFormButton.addEventListener("click", (e) => {
    e.preventDefault();
    // remove the li for the tag form
    item.remove();
  });
}

//Toggle media container display on mobile
let mobileMediaBtn = document.querySelector('.mobile-media')

mobileMediaBtn.addEventListener('click',() =>{
  mobileMediaBtn.classList.toggle('active')
  console.log('click')
  document.querySelector('.media-container').classList.toggle('displayNone')
  if(mobileMediaBtn.classList.contains('active')){
    mobileMediaBtn.textContent = "Cacher les médias"
  }else{
    mobileMediaBtn.textContent = "Voir les médias"
  }
})

  if (window.innerWidth < 1024) {
    document.querySelector('.media-container').classList.add('displayNone');
  }


//Link trick delete btn on hidding form
let tricksDeleteBtn = document.querySelector('.deleteBtn')

tricksDeleteBtn.addEventListener('click',(e) => {
  e.preventDefault()
  e.stopPropagation()
  let tricksFormDelete = document.querySelector('.deleteForm')
  tricksFormDelete.submit()
})


function createIframe(tricksVideo)
{
  let iframe = document.createElement('iframe')
  let link = tricksVideo.value
  iframe.setAttribute('src',link)
  iframe.setAttribute('frameborder',0)

  let container = tricksVideo.parentElement
  if (container.querySelector('iframe')) {
    console.log(container.querySelector('iframe'))
    container.querySelector('iframe').replaceWith(iframe);
  } else {
    container.appendChild(iframe);
  }
}

//Featured Image Edition
let editFeaturedImageBtn = document.querySelector('.trick-action .fa-pencil')

let deleteFeaturedImageBtn = document.querySelector('.trick-action .fa-trash')

let featuredImageInput = document.querySelector('.featuredImageInput')

let featuredImageDelete = featuredImageInput.parentElement.querySelector('div')

let featuredImage = document.querySelector('img.featuredImage')


editFeaturedImageBtn.addEventListener('click', (e) => {
  featuredImageInput.click()
})

deleteFeaturedImageBtn.addEventListener('click', (e) => {
  if(featuredImageDelete){
    featuredImageDelete.querySelector('input').checked = true;
  }
  featuredImageInput.value = null
  featuredImage.src = '/img/noimage.png'
})

featuredImageInput.addEventListener('change', (e) => {
  let file = featuredImageInput.files[0]
  if(file){
    featuredImage.src = URL.createObjectURL(file)
  }
})