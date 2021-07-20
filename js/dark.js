$(document).ready(function () {  // j'utilise ready (page rafraichie) pour pouvoir check si le local storage contient dark ou non dans les autres pages 
    const chk = $("#chk");
    let save = localStorage.getItem("dark");
    if(save == "true") {
        chk.prop('checked', true)  
        document.body.classList.add("dark");
    }else {
        chk.prop('checked', false) 
        document.body.classList.remove("dark");
    }
  chk.on("click", function (e) {
    // check = nuit
    let save = localStorage.getItem("dark");

    if (!save) {  
      localStorage.setItem("dark", "true");  
      document.body.classList.toggle("dark");
    } else {
      document.body.classList.toggle('dark')

      if(save == "true"){
        localStorage.setItem("dark", "false");
      }else {
        localStorage.setItem("dark", "true");
      }
    }
  });
});
