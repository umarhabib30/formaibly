(function ($) {
  "use strict";

  //============================ Scroll To Top Js Start ========================
  var btn = $(".scroll-top");

  $(window).on("scroll", function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({
        scrollTop: 0,
      },
      "300"
    );
  });
  //============================ Scroll To Top Js End ========================

  //============================ faq js stat ========================

  $(document).ready(function () {
    $(".faq__item.open").find(".faq__body").show();

    $(".faq__item .faq__title, .faq__item .faq__number").on(
      "click",
      function () {
        const $item = $(this).closest(".faq__item");
        const $body = $item.find(".faq__body");
        const $allItems = $(".faq__item");

        if ($item.hasClass("open")) {
          $body.stop(true).slideUp(300);
          $item.removeClass("open");
          return;
        }

        $allItems.each(function () {
          const $openItem = $(this);
          if ($openItem.hasClass("open")) {
            $openItem.find(".faq__body").stop(true).slideUp(300);
            $openItem.removeClass("open");
          }
        });

        $item.addClass("open");
        $body.stop(true).slideDown(300);
      }
    );
  });
  //============================ faq js end ========================
  // ========================= Service Section Hover Js Start ===============
  $(".use-case__list-item").hover(function () {
    var serviceId = $(this).attr("data-use-case-id");
    $(this).addClass("active").siblings().removeClass("active");
    $("#" + serviceId)
      .removeClass("d-none")
      .siblings()
      .addClass("d-none");
    $("#" + serviceId)
      .addClass("show")
      .siblings()
      .removeClass("show");
  });
  // ========================= Service Section Hover Js End ===================

  // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 300) {
      $(".header__area").addClass("fixed-header");
    } else {
      $(".header__area").removeClass("fixed-header");
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Filter Js Start ============================
  $(document).on("click", ".filter__btn", function () {
    $(".filter__main, .overlay").addClass("active");
  });

  $(document).on("click", ".filter__close, .overlay", function () {
    $(".filter__main, .overlay").removeClass("active");
  });

  //============================ Filter Js End ==============================

  // ========================= Range Slider Js Start ===================
  const rangeInput = document.querySelectorAll(".range-input input");
  const priceInput = document.querySelectorAll(".price-input span");
  const progress = document.querySelector(".sliderr .progresss");
  let priceGap = 1000;

  rangeInput.forEach((input) => {
    input.addEventListener("input", (e) => {
      let minValue = parseInt(rangeInput[0].value);
      let maxValue = parseInt(rangeInput[1].value);

      if (maxValue - minValue < priceGap) {
        if (e.target.classList.contains("range-min")) {
          rangeInput[0].value = maxValue - priceGap;
        } else {
          rangeInput[1].value = minValue + priceGap;
        }
      } else {
        priceInput[0].textContent = minValue;
        priceInput[1].textContent = maxValue;
        const maxRange = parseInt(rangeInput[0].max);
        progress.style.left = (minValue / maxRange) * 100 + "%";
        progress.style.right = 100 - (maxValue / maxRange) * 100 + "%";
      }
    });
  });

  // ========================= Range Slider Js End ===================

  //============================ Offcanvas Js Start ============================
  $(document).on("click", ".menu__open", function () {
    $(".offcanvas__area, .overlay").addClass("active");
  });

  $(document).on("click", ".menu__close, .overlay", function () {
    $(".offcanvas__area, .overlay").removeClass("active");
  });

  //============================ Offcanvas Js End ==============================

  // ========================== Add Attribute For Bg Image Js Start =====================
  $(".bg--img").css("background-image", function () {
    var bg = "url(" + $(this).data("background-image") + ")";
    return bg;
  });
  // ========================== Add Attribute For Bg Image Js End =====================
  // ========================== Add Attribute For Mask Image Js Start =====================
  $(".mask-box").css("mask-image", function () {
    var bg = "url(" + $(this).data("mask") + ")";
    return bg;
  });
  // ========================== Add Attribute For Mask Image Js End =====================

  // ========================= Odometer Js Start ===================
  if ($(".odometer").length > 0) {
    $(window).on("scroll", function () {
      $(".odometer").each(function () {
        if ($(this).isInViewport()) {
          if (!$(this).data("odometer-started")) {
            $(this).data("odometer-started", true);
            this.innerHTML = $(this).data("odometer-final");
          }
        }
      });
    });
  }
  // isInViewport helper function
  $.fn.isInViewport = function () {
    let elementTop = $(this).offset().top;
    let elementBottom = elementTop + $(this).outerHeight();
    let viewportTop = $(window).scrollTop();
    let viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
  };
  // ========================= Odometer Js End ===================

  // ========================= Magnific Popup Js Start ===================
  $(".promo__video__play").magnificPopup({
    type: "iframe",
  });
  // ========================= Magnific Popup Js End ===================
  // ========================= Brand Js Start ===================

  // brand slider
  $(".brand-logo__slider").bxSlider({
    minSlides: 4,
    maxSlides: 4,
    slideWidth: 170,
    slideMargin: 10,
    ticker: true,
    speed: 20000,
  });
  // testimonial slider
  $(".testimonial__slider").bxSlider({
    mode: "vertical",
    slideMargin: 5,
  });
  // ========================= Brand Js End ===================


  // ========================= Show Hide Password Js End ===================
  if ($(".password-show-hide").length) {
    $(".password-show-hide").each(function () {
      let container = $(this);
      let inputField = container.closest(".password__field").find("input");
      let showIcon = container.find(".open-eye-icon");
      let hideIcon = container.find(".close-eye-icon");


      showIcon.show();
      hideIcon.hide();
      showIcon.on("click", function () {
        inputField.attr("type", "text");
        showIcon.hide();
        hideIcon.show();
      });
      hideIcon.on("click", function () {
        inputField.attr("type", "password");
        hideIcon.hide();
        showIcon.show();
      });
    });
  }
  // ========================= Show Hide Password Js End ===================

  
  // ========================= Select2 Js Start =====================
  if ($('.select2').length) {
    $('.select2').select2();
  }
  // ========================= Select2 Js End =====================


  //============================ Sidebar Js Start ============================
  $(document).on("click", ".sidebar__open", function () {
    $(".dashboard__sidebar, .overlay").addClass("active");
  });

  $(document).on("click", ".sidebar__close, .overlay", function () {
    $(".dashboard__sidebar, .overlay").removeClass("active");
  });

  //============================ Sidebar Js End ==============================

  // ========================= Scroll Reveal Js Start ===================
  const sr = ScrollReveal({
    origin: "top",
    distance: "60px",
    duration: 1200,
    delay: 100,
    reset: false,
  });
  sr.reveal(
    ".bottom-reveal, .banner__title, .section-heading__title, .pricing", {
      delay: 60,
      interval: 100,
      origin: "bottom",
    }
  );
  sr.reveal(
    ".banner__desc, .section-heading__desc, .testimonial, .blog__card", {
      delay: 150,
      interval: 100,
      origin: "bottom",
    }
  );
  sr.reveal(".banner__btns", {
    delay: 200,
    origin: "bottom",
  });
  sr.reveal(".right-reveal, .faq__thumb-wrap", {
    delay: 60,
    origin: "right",
  });
  sr.reveal(".left-reveal, .faq__content-wrap", {
    delay: 60,
    interval: 100,
    origin: "left",
  });
  sr.reveal(".banner__subtitle, .section-heading__name", {
    delay: 60,
    origin: "top",
  });

  // ========================= Scroll Reveal Js End ===================

  // ========================== Table Data Label Js Start =====================
  Array.from(document.querySelectorAll("table")).forEach((table) => {
    let heading = table.querySelectorAll("thead tr th");
    Array.from(table.querySelectorAll("tbody tr")).forEach((row) => {
      let columArray = Array.from(row.querySelectorAll("td"));
      if (columArray.length <= 1) return;
      columArray.forEach((colum, i) => {
        colum.setAttribute("data-label", heading[i].innerText);
      });
    });
  });
  // ========================== Table Data Label Js End =====================

  // ========================== Label Required Js Start =====================
  $.each($("input, select, textarea"), function (i, element) {
    if (element.hasAttribute("required")) {
      $(element)
        .closest(".form-group")
        .find("label")
        .first()
        .addClass("required");
    }
  });
  // ========================== Label Required Js End =====================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader").fadeOut();
  });
  // ========================= Preloader Js End=====================
})(jQuery);