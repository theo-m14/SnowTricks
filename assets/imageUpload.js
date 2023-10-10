addListenerOnAddBtn();
listenerOnUpload();
listenerOnDelete();

function addListenerOnAddBtn() {
  document.querySelector(".addImage").addEventListener("click", () => {
    setTimeout(() => {
      listenerOnUpload();
      listenerOnDelete();
      addListenerOnAddBtn();
    }, 1000);
  });
}

function listenerOnUpload() {
  document.querySelectorAll(".uploadBtn").forEach((uploadBtn) => {
    uploadBtn.addEventListener("change", (e) => {
      let imageId = e.target.getAttribute("imageId");
      console.log(imageId);
      let image = document.querySelector(".tricksImage" + imageId);
      console.log("test=" + imageId);
      image.src = URL.createObjectURL(e.target.files[0]);
    });
  });
}

function listenerOnDelete(){
    document.querySelectorAll('button.deleteBtn').forEach((deleteBtn) => {
        deleteBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            e.target.parentElement.remove()
        })
    })
}
