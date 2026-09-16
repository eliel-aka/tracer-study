var pageName = window.location.pathname.split("/").pop().split(".")[0];

var STORAGE_KEY = "admin-theme-config";

var fixedPlugin = document.querySelector("[fixed-plugin]");
var fixedPluginButton = document.querySelector("[fixed-plugin-button]");
var fixedPluginButtonNav = document.querySelector("[fixed-plugin-button-nav]");
var fixedPluginCard = document.querySelector("[fixed-plugin-card]");
var fixedPluginCloseButton = document.querySelector("[fixed-plugin-close-button]");

var navbar = document.querySelector("[navbar-main]");
var buttonNavbarFixed = document.querySelector("[navbarFixed]");

var sidenav = document.querySelector("aside");
var sidenav_target = "../pages/" + pageName + ".html";
var sidenav_highlight = null;

if (sidenav) {
  sidenav_highlight = document.querySelector("a[href=" + CSS.escape(sidenav_target) + "]");
}

var whiteBtn = document.querySelector("[transparent-style-btn]");
var darkBtn = document.querySelector("[white-style-btn]");

var non_active_style = ["bg-none", "bg-transparent", "text-blue-500", "border-blue-500"];
var active_style = ["bg-gradient-to-tl", "from-blue-500", "to-violet-500", "bg-blue-500", "text-white", "border-transparent"];

var white_sidenav_classes = ["bg-white", "shadow-xl"];
var black_sidenav_classes = ["bg-slate-850", "shadow-none"];

var dark_mode_toggle = document.querySelector("[dark-toggle]");
var root_html = document.querySelector("html");

function loadConfig() {
  try {
    var raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) {
      return {};
    }
    return JSON.parse(raw);
  } catch (e) {
    return {};
  }
}

function saveConfig(nextConfig) {
  var currentConfig = loadConfig();
  var config = Object.assign({}, currentConfig, nextConfig);
  localStorage.setItem(STORAGE_KEY, JSON.stringify(config));
}

function setButtonState(activeButton, inactiveButton) {
  if (!activeButton || !inactiveButton) {
    return;
  }

  activeButton.setAttribute("active-style", "true");
  non_active_style.forEach(function (styleClass) {
    activeButton.classList.remove(styleClass);
  });
  active_style.forEach(function (styleClass) {
    activeButton.classList.add(styleClass);
  });

  inactiveButton.removeAttribute("active-style");
  active_style.forEach(function (styleClass) {
    inactiveButton.classList.remove(styleClass);
  });
  non_active_style.forEach(function (styleClass) {
    inactiveButton.classList.add(styleClass);
  });
}

function applySidenavStyle(style, persist) {
  if (!sidenav || !whiteBtn || !darkBtn) {
    return;
  }

  if (style === "dark") {
    white_sidenav_classes.forEach(function (styleClass) {
      sidenav.classList.remove(styleClass);
    });
    black_sidenav_classes.forEach(function (styleClass) {
      sidenav.classList.add(styleClass);
    });
    sidenav.classList.add("dark");
    setButtonState(darkBtn, whiteBtn);
  } else {
    black_sidenav_classes.forEach(function (styleClass) {
      sidenav.classList.remove(styleClass);
    });
    white_sidenav_classes.forEach(function (styleClass) {
      sidenav.classList.add(styleClass);
    });
    sidenav.classList.remove("dark");
    setButtonState(whiteBtn, darkBtn);
  }

  if (persist) {
    saveConfig({ sidenavType: style });
  }
}

function applyNavbarFixed(isFixed, persist) {
  if (!navbar || !buttonNavbarFixed) {
    return;
  }

  var checked = !!isFixed;
  buttonNavbarFixed.checked = checked;

  var white_elements = navbar.querySelectorAll(".text-white");
  var white_bg_elements = navbar.querySelectorAll("[sidenav-trigger] i.bg-white");
  var white_before_elements = navbar.querySelectorAll(".before\\:text-white");

  if (checked) {
    white_elements.forEach(function (element) {
      element.classList.remove("text-white");
      element.classList.add("dark:text-white");
    });
    white_bg_elements.forEach(function (element) {
      element.classList.remove("bg-white");
      element.classList.add("dark:bg-white");
      element.classList.add("bg-slate-500");
    });
    white_before_elements.forEach(function (element) {
      element.classList.add("dark:before:text-white");
      element.classList.remove("before:text-white");
    });
    navbar.setAttribute("navbar-scroll", "true");
    navbar.classList.add("sticky");
    navbar.classList.add("top-[1%]");
    navbar.classList.add("backdrop-saturate-200");
    navbar.classList.add("backdrop-blur-2xl");
    navbar.classList.add("dark:bg-slate-850/80");
    navbar.classList.add("dark:shadow-dark-blur");
    navbar.classList.add("bg-[hsla(0,0%,100%,0.8)]");
    navbar.classList.add("shadow-blur");
    navbar.classList.add("z-110");
  } else {
    navbar.setAttribute("navbar-scroll", "false");
    navbar.classList.remove("sticky");
    navbar.classList.remove("top-[1%]");
    navbar.classList.remove("backdrop-saturate-200");
    navbar.classList.remove("backdrop-blur-2xl");
    navbar.classList.remove("dark:bg-slate-850/80");
    navbar.classList.remove("dark:shadow-dark-blur");
    navbar.classList.remove("bg-[hsla(0,0%,100%,0.8)]");
    navbar.classList.remove("shadow-blur");
    navbar.classList.remove("z-110");
    white_elements.forEach(function (element) {
      element.classList.add("text-white");
      element.classList.remove("dark:text-white");
    });
    white_bg_elements.forEach(function (element) {
      element.classList.add("bg-white");
      element.classList.remove("dark:bg-white");
      element.classList.remove("bg-slate-500");
    });
    white_before_elements.forEach(function (element) {
      element.classList.remove("dark:before:text-white");
      element.classList.add("before:text-white");
    });
  }

  if (persist) {
    saveConfig({ navbarFixed: checked });
  }
}

function applyDarkMode(isDark, persist) {
  if (!root_html) {
    return;
  }

  var checked = !!isDark;

  if (dark_mode_toggle) {
    dark_mode_toggle.checked = checked;
    dark_mode_toggle.setAttribute("manual", "true");
  }

  if (checked) {
    root_html.classList.add("dark");
  } else {
    root_html.classList.remove("dark");
  }

  if (persist) {
    saveConfig({ darkMode: checked });
  }
}

function setupFixedPluginToggle() {
  if (!fixedPlugin || !fixedPluginButton || !fixedPluginCard || !fixedPluginCloseButton) {
    return;
  }

  var toggleOpenClass = pageName === "rtl" ? "left-0" : "right-0";
  var toggleCloseClass = pageName === "rtl" ? "-left-90" : "-right-90";

  fixedPluginButton.addEventListener("click", function () {
    fixedPluginCard.classList.toggle(toggleCloseClass);
    fixedPluginCard.classList.toggle(toggleOpenClass);
  });

  if (fixedPluginButtonNav) {
    fixedPluginButtonNav.addEventListener("click", function () {
      fixedPluginCard.classList.toggle(toggleCloseClass);
      fixedPluginCard.classList.toggle(toggleOpenClass);
    });
  }

  fixedPluginCloseButton.addEventListener("click", function () {
    fixedPluginCard.classList.toggle(toggleCloseClass);
    fixedPluginCard.classList.toggle(toggleOpenClass);
  });

  window.addEventListener("click", function (e) {
    var navButtonClicked = fixedPluginButtonNav ? fixedPluginButtonNav.contains(e.target) : false;
    if (!fixedPlugin.contains(e.target) && !fixedPluginButton.contains(e.target) && !navButtonClicked) {
      if (fixedPluginCard.classList.contains(toggleOpenClass)) {
        fixedPluginCloseButton.click();
      }
    }
  });
}

function setupSidenavColorHandler() {
  window.sidebarColor = function (a) {
    var color = a.getAttribute("data-color");
    var parent = a.parentElement.children;
    var activeColor;

    var activeSidenavIconColorClass;
    var checkedSidenavIconColor = "bg-" + color + "-500/30";

    var sidenavIcon = document.querySelector("a[href=" + CSS.escape(sidenav_target) + "]");

    for (var i = 0; i < parent.length; i++) {
      if (parent[i].hasAttribute("active-color")) {
        activeColor = parent[i].getAttribute("data-color");

        parent[i].classList.toggle("border-white");
        parent[i].classList.toggle("border-slate-700");

        activeSidenavIconColorClass = "bg-" + activeColor + "-500/30";
      }
      parent[i].removeAttribute("active-color");
    }

    var att = document.createAttribute("active-color");

    a.setAttributeNode(att);
    a.classList.toggle("border-white");
    a.classList.toggle("border-slate-700");

    if (sidenavIcon && activeSidenavIconColorClass) {
      sidenavIcon.classList.remove(activeSidenavIconColorClass);
      sidenavIcon.classList.add(checkedSidenavIconColor);
    }
  };
}

function setupEventHandlers() {
  if (whiteBtn) {
    whiteBtn.addEventListener("click", function () {
      applySidenavStyle("white", true);
    });
  }

  if (darkBtn) {
    darkBtn.addEventListener("click", function () {
      applySidenavStyle("dark", true);
    });
  }

  if (buttonNavbarFixed && navbar) {
    buttonNavbarFixed.addEventListener("change", function () {
      applyNavbarFixed(this.checked, true);
    });
  } else if (buttonNavbarFixed) {
    buttonNavbarFixed.setAttribute("disabled", "true");
  }

  if (dark_mode_toggle) {
    dark_mode_toggle.addEventListener("change", function () {
      applyDarkMode(this.checked, true);
    });
  }
}

function applyPersistedConfig() {
  var config = loadConfig();

  applyDarkMode(config.darkMode === true, false);
  applySidenavStyle(config.sidenavType === "dark" ? "dark" : "white", false);

  var navbarIsFixed = false;
  if (typeof config.navbarFixed === "boolean") {
    navbarIsFixed = config.navbarFixed;
  } else if (navbar) {
    navbarIsFixed = navbar.getAttribute("navbar-scroll") === "true";
  }
  applyNavbarFixed(navbarIsFixed, false);
}

setupFixedPluginToggle();
setupSidenavColorHandler();
setupEventHandlers();
applyPersistedConfig();
