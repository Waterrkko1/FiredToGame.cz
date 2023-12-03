document.addEventListener("DOMContentLoaded", function () {
  const headerElement = document.getElementById("animated-header");
  const text = "FiredToGame.cz";
  const delay = 100;

  function animateText(text, delay) {
    let index = 0;
    const timer = setInterval(function () {
      headerElement.innerText += text[index];
      index++;
      if (index === text.length) {
        clearInterval(timer);
      }
    }, delay);
  }

  animateText(text, delay);
});
