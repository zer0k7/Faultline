(function () {
  window.FLT_CDN_FLAG = "FLT{cdn_no_sri_8f2b}";
  function paint() {
    var el = document.getElementById("cdn-widget");
    if (el) {
      el.textContent = "Widget build 8f2b ready. " + window.FLT_CDN_FLAG;
    }
  }
  if (document.readyState !== "loading") { paint(); }
  else { document.addEventListener("DOMContentLoaded", paint); }
})();
