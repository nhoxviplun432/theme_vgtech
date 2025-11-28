import * as Turbo from "@hotwired/turbo";
import { initVgtech } from "./vgtech.js";

Turbo.start();

// ================= LOADER =================
const loaderEl = () => document.querySelector("#turbo-loader");

const showLoader = () => loaderEl()?.removeAttribute("hidden");
const hideLoader = () => loaderEl()?.setAttribute("hidden", "");

// ================= INIT =================
document.addEventListener("DOMContentLoaded", () => {
  initVgtech();
  hideLoader();
});

document.addEventListener("turbo:load", () => {
  initVgtech();
  hideLoader();
});

// ================= CLEANUP =================
// document.addEventListener("turbo:before-render", () => {
//   window.cleanUpBootstrapModals?.();
// });

document.addEventListener("turbo:before-render", (event) => {
  const newDoc = event.detail.newBody || event.detail.newElement;

  if (!newDoc) return;

  // Lấy các script mới từ DOM Turbo trả về
  const newScripts = newDoc.querySelectorAll("script");

  newScripts.forEach((oldScript) => {
    const newScript = document.createElement("script");

    // Copy các thuộc tính (src, type…)
    for (let i = 0; i < oldScript.attributes.length; i++) {
      let attr = oldScript.attributes[i];
      newScript.setAttribute(attr.name, attr.value);
    }

    // Inline script
    if (oldScript.textContent) {
      newScript.textContent = oldScript.textContent;
    }

    // Replace script cũ để browser chạy lại
    oldScript.replaceWith(newScript);
  });
  window.cleanUpBootstrapModals?.();
});

// ================= LOADER EVENTS =================|

document.addEventListener("turbo:visit", showLoader);

window.addEventListener("resize", () => {
  window.fixBodyHeight?.();
});

// document.addEventListener("turbo:before-fetch-request", (event) => {
//   // Bắt Turbo dùng full reload thay vì snapshot navigation
//   event.preventDefault();
//   window.location.href = event.detail.url;
// });

// document.addEventListener("turbo:click", (event) => {
//     const link = event.target.closest("a");
//     if (link && link.dataset.turbo === "false") {
//         event.preventDefault();
//         window.location.href = link.href;
//     }
// });
