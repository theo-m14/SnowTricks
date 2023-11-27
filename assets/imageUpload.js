function listenerOnUpload() {
  document.querySelectorAll('input[type="file"]').forEach((uploadBtn) => {
    uploadBtn.addEventListener("change", (e) => {
      let image = e.target.parentElement.parentElement.parentElement.parentElement.querySelector(".tricksImage");
      image.src = URL.createObjectURL(e.target.files[0]);
    });
  });
}

document.querySelectorAll("ul.tricksVideos li").forEach((tricksVideos) => {
  addTagFormDeleteLink(tricksVideos);
});

document.querySelectorAll("ul.tricksImages li").forEach((tricksImages) => {
  addTagFormDeleteLink(tricksImages);
});

document.querySelectorAll(".addTricksVideo").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    addFormToCollection(e);
  });
});

document.querySelectorAll(".addTricksImage").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    addFormToCollection(e);
    setTimeout(() => {
      listenerOnUpload();
    }, 1000);
  });
});

function addFormToCollection(e) {
  let collectionName = e.target.dataset.collectionHolderClass;
  const collectionHolder = document.querySelector("." + collectionName);

  const item = document.createElement("li");

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
