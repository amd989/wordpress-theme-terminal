(function ($) {
  function setVar(name, value) {
    document.documentElement.style.setProperty(name, value);
  }

  wp.customize("terminal_accent", function (v) {
    v.bind(function (val) { setVar("--accent", val); });
  });
  wp.customize("terminal_background", function (v) {
    v.bind(function (val) { setVar("--background", val); });
  });
  wp.customize("terminal_foreground", function (v) {
    v.bind(function (val) { setVar("--foreground", val); });
  });
})(jQuery);
