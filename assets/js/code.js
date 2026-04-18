(function () {
  function decorate() {
    const codes = document.querySelectorAll('pre > code[class*="language-"]');

    codes.forEach(code => {
      const pre = code.parentElement;
      if (!pre || pre.parentElement.classList.contains("highlight")) return;

      const match = code.className.match(/language-([\w-]+)/);
      const lang = match ? match[1] : "";
      if (!lang) return;

      const wrapper = document.createElement("div");
      wrapper.className = "highlight";
      pre.parentElement.insertBefore(wrapper, pre);

      const title = document.createElement("div");
      title.className = "code-title";
      title.innerText = lang;

      if (navigator.clipboard !== undefined) {
        const button = document.createElement("button");
        button.className = "copy-button";
        button.type = "button";
        button.innerText = "Copy";

        button.addEventListener("click", () => {
          const content = code.innerText.split("\n").filter(Boolean).join("\n");
          button.innerText = "Copied";
          setTimeout(() => { button.innerText = "Copy"; }, 1000);
          navigator.clipboard.writeText(content);
        });

        title.append(button);
      }

      wrapper.appendChild(title);
      wrapper.appendChild(pre);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", decorate);
  } else {
    decorate();
  }
})();
