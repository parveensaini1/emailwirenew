jQuery(".responsivemenu-main-menu-button-wrapper, .responsivemenu-main-menu-activator").on("touchstart click",function(a){"use strict";a.preventDefault(),jQuery(".responsivemenu-main-wrapper").hasClass("responsivemenu-main-wrapper-active")?(jQuery(".responsivemenu-main-wrapper").removeClass("responsivemenu-main-wrapper-active"),jQuery(".responsivemenu-main-background").removeClass("responsivemenu-main-background-active"),jQuery(".responsivemenu-background-overlay").removeClass("responsivemenu-background-overlay-active"),jQuery(".responsivemenu-main-menu-button-wrapper").removeClass("responsivemenu-menu-active"),jQuery(".responsivemenu-menu-wrapper").removeClass("responsivemenu-menu-wrapper-active"),jQuery(".responsivemenu-search-close-wrapper").removeClass("responsivemenu-search-close-wrapper-active"),jQuery(".responsivemenu-search-wrapper").removeClass("responsivemenu-search-wrapper-active"),jQuery(".responsivemenu-search-wrapper #searchform #s").blur(),jQuery(".responsivemenu-search-button").removeClass("responsivemenu-search-button-hidden"),jQuery(".responsivemenu-secondary-menu-wrapper").removeClass("responsivemenu-secondary-menu-wrapper-active"),jQuery(".responsivemenu-secondary-menu-button").removeClass("responsivemenu-secondary-menu-button-active")):(jQuery(".responsivemenu-main-wrapper").addClass("responsivemenu-main-wrapper-active"),jQuery(".responsivemenu-main-background").addClass("responsivemenu-main-background-active"),jQuery(".responsivemenu-background-overlay").addClass("responsivemenu-background-overlay-active"),jQuery(".responsivemenu-main-menu-button-wrapper").addClass("responsivemenu-menu-active"),jQuery(".responsivemenu-menu-wrapper").addClass("responsivemenu-menu-wrapper-active"))}),jQuery(".responsivemenu-secondary-menu-button svg").on("touchstart click",function(a){"use strict";a.preventDefault(),jQuery(".responsivemenu-secondary-menu-wrapper").hasClass("responsivemenu-secondary-menu-wrapper-active")?(jQuery(".responsivemenu-secondary-menu-wrapper").removeClass("responsivemenu-secondary-menu-wrapper-active"),jQuery(".responsivemenu-secondary-menu-button").removeClass("responsivemenu-secondary-menu-button-active")):(jQuery(".responsivemenu-secondary-menu-wrapper").addClass("responsivemenu-secondary-menu-wrapper-active"),jQuery(".responsivemenu-secondary-menu-button").addClass("responsivemenu-secondary-menu-button-active"),jQuery(".responsivemenu-search-close-wrapper").removeClass("responsivemenu-search-close-wrapper-active"),jQuery(".responsivemenu-search-wrapper").removeClass("responsivemenu-search-wrapper-active"),jQuery(".responsivemenu-search-wrapper #searchform #s").blur(),jQuery(".responsivemenu-search-button").removeClass("responsivemenu-search-button-hidden"))}),jQuery(".responsivemenu-background-overlay").on("touchstart click",function(a){"use strict";a.preventDefault(),jQuery(".responsivemenu-main-wrapper").removeClass("responsivemenu-main-wrapper-active"),jQuery(".responsivemenu-main-background").removeClass("responsivemenu-main-background-active"),jQuery(".responsivemenu-background-overlay").removeClass("responsivemenu-background-overlay-active"),jQuery(".responsivemenu-main-menu-button-wrapper").removeClass("responsivemenu-menu-active"),jQuery(".responsivemenu-secondary-menu-wrapper").removeClass("responsivemenu-secondary-menu-wrapper-active"),jQuery(".responsivemenu-secondary-menu-button").removeClass("responsivemenu-secondary-menu-button-active"),jQuery(".responsivemenu-search-close-wrapper").removeClass("responsivemenu-search-close-wrapper-active"),jQuery(".responsivemenu-search-wrapper").removeClass("responsivemenu-search-wrapper-active"),jQuery(".responsivemenu-search-wrapper #searchform #s").blur(),jQuery(".responsivemenu-search-button").removeClass("responsivemenu-search-button-hidden"),jQuery(".responsivemenu-menu-wrapper").removeClass("responsivemenu-menu-wrapper-active")});